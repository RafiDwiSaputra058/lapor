<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AIService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.groq.com/openai/v1';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
    }

    public function analyzeDamage(string $imagePath): array
    {
        $fullPath = Storage::disk('public')->path($imagePath);

        if (!file_exists($fullPath)) {
            return $this->defaultResult();
        }

        $imageData = base64_encode(file_get_contents($fullPath));
        $mimeType  = mime_content_type($fullPath);
        $dataUrl   = "data:{$mimeType};base64,{$imageData}";

        $prompt = 'Kamu adalah AI analis kerusakan infrastruktur publik di Indonesia. '
            . 'Analisis foto kerusakan berikut dan berikan output HANYA dalam format JSON seperti ini: '
            . '{"infrastructure_type":"jenis infrastruktur (jalan/jembatan/trotoar/drainase/lampu/fasilitas umum/lainnya)",'
            . '"severity":"tingkat kerusakan (Ringan/Sedang/Berat)",'
            . '"suggested_category":"kategori laporan yang sesuai",'
            . '"reasoning":"penjelasan singkat dalam bahasa Indonesia maksimal 2 kalimat"}'
            . ' Jangan tambahkan teks apapun di luar JSON.';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->baseUrl . '/chat/completions', [
            'model'    => 'meta-llama/llama-4-scout-17b-16e-instruct',
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => [
                        [
                            'type'      => 'image_url',
                            'image_url' => ['url' => $dataUrl]
                        ],
                        [
                            'type' => 'text',
                            'text' => $prompt // INI YANG BENAR, TADI SAYA TYPO DI SINI
                        ]
                    ]
                ]
            ],
            'max_tokens'  => 500,
            'temperature' => 0,
        ]);

        if ($response->failed()) {
            Log::error('Groq failed', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);
            return $this->defaultResult();
        }

        $content = $response->json('choices.0.message.content');

        Log::info('Groq response', ['content' => $content]);

        $clean  = preg_replace('/```json|```/', '', $content);
        $result = json_decode(trim($clean), true);

        if (!$result || !isset($result['infrastructure_type'])) {
            return $this->defaultResult();
        }

        return $result;
    }

    public function calculateUrgencyScore(array $aiResult, float $latitude, float $longitude): int
    {
        $score = 0;

        // 1. Severity Score — Dioptimalkan penuh agar sinkron dengan threshold visual UI Admin
        $severityScores = [
            'Berat'  => 70, // Otomatis mengunci posisi di zona merah (Tinggi)
            'Sedang' => 45, // Mengunci posisi di zona kuning (Sedang)
            'Ringan' => 20, // Mengunci posisi di zona hijau (Rendah)
        ];
        $score += $severityScores[$aiResult['severity']] ?? 0;

        // 2. Crowdsource Score — Laporan serupa dalam radius ~1km
        $similarReports = \App\Models\Report::where('report_category_id', '!=', null)
            ->where('ai_infrastructure_type', $aiResult['infrastructure_type'])
            ->whereRaw("(
            6371 * acos(
                cos(radians(?)) * cos(radians(CAST(latitude AS FLOAT))) *
                cos(radians(CAST(longitude AS FLOAT)) - radians(?)) +
                sin(radians(?)) * sin(radians(CAST(latitude AS FLOAT)))
            )
        ) < 1", [$latitude, $longitude, $latitude])
            ->count();

        // Plafon disesuaikan menjadi max 15 agar distribusi nilai adil
        $crowdsourceScore = min($similarReports * 5, 15);
        $score += $crowdsourceScore;

        // 3. Safety Score — Infrastruktur Kritis
        // Memasukkan 'fasilitas umum' & 'trotoar' agar ruko roboh/gedung rusak terdeteksi sebagai bahaya keselamatan
        $criticalTypes = ['jalan', 'jembatan', 'drainase', 'fasilitas umum', 'trotoar'];
        if (in_array(strtolower($aiResult['infrastructure_type']), $criticalTypes)) {
            $score += 15; // Ditambah 15 poin (Total maksimal jika Berat + Ramai + Kritis = 70 + 15 + 15 = 100)
        }

        return min($score, 100);
    }

    private function defaultResult(): array
    {
        return [
            'infrastructure_type' => 'lainnya',
            'severity'            => 'Sedang',
            'suggested_category'  => 'Umum',
            'reasoning'           => 'Analisis AI tidak tersedia, mohon isi kategori secara manual.'
        ];
    }
}