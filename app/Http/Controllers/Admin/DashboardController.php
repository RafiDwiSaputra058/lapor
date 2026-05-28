<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.admin.dashboard');
    }

    // Fungsi baru untuk AI Summary Generator
    public function generateSummary()
    {
        // 1. Ambil semua laporan dari database
        $reports = Report::all(); 

        if ($reports->isEmpty()) {
            return back()->with('error', 'Belum ada laporan untuk diringkas oleh AI.');
        }

        // 2. Ubah data tabel menjadi teks rapi untuk dibaca AI
        $dataLaporan = "";
        foreach ($reports as $index => $report) {
            $nomor = $index + 1;
            $jenis = $report->ai_infrastructure_type ?? 'Infrastruktur';
            $tingkat = $report->ai_severity ?? 'Sedang';
            $lokasi = $report->address ?? 'Lokasi tidak diketahui';
            
            $dataLaporan .= "{$nomor}. Jenis: {$jenis}, Tingkat Kerusakan: {$tingkat}, Lokasi: {$lokasi}\n";
        }

        // 3. Buat perintah (Prompt) untuk AI
        $prompt = "Kamu adalah asisten ahli dari Kepala Dinas Pekerjaan Umum. "
                . "Tugasmu adalah membuat Ringkasan Eksekutif (maksimal 2 paragraf) berdasarkan data laporan kerusakan warga berikut ini. "
                . "Sebutkan tren kerusakan yang paling parah atau mendominasi agar kepala dinas bisa segera mengambil keputusan anggaran.\n\n"
                . "DATA LAPORAN:\n" . $dataLaporan;

        // 4. Tembak ke API Groq
        $apiKey = config('services.groq.key');
        $response = Http::withoutVerifying()
            ->withToken($apiKey)
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile', 
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.5,
            ]);

        if ($response->successful()) {
            $ringkasan = $response->json()['choices'][0]['message']['content'];
            
            // 5. Rakit PDF-nya menggunakan DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.admin.pdf_summary', [
                'ringkasan_ai' => $ringkasan,
                'reports' => $reports
            ]);

            // 6. Buat nama file dinamis dengan Carbon (Tanggal & Jam)
            // Hasilnya nanti misal: 28-05-2026_14-30
            $tanggal = \Carbon\Carbon::now()->format('d-m-Y_H-i');
            $namaFile = "Laporan Pengaduan Masyarakat-1 ({$tanggal}).pdf";

            // 7. Langsung download ke laptop admin dengan nama baru!
            return $pdf->download($namaFile);
        }

        return back()->with('error', 'Gagal membuat ringkasan AI: ' . $response->body());
    }
}