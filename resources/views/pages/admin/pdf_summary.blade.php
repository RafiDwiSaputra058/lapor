<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Eksekutif LENTERA</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .summary-box { background-color: #f1f5f9; padding: 15px; border-left: 4px solid #1e3a8a; margin-bottom: 20px; text-align: justify; }
        .report-item { margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; page-break-inside: avoid; }
        
        /* Trik Tabel agar gambar dan teks tidak bertabrakan */
        table.layout-table { width: 100%; border-collapse: collapse; }
        table.layout-table td { vertical-align: top; }
        .td-image { width: 35%; padding-right: 15px; }
        .td-content { width: 65%; }
        .report-image { width: 100%; max-width: 220px; height: auto; border-radius: 4px; }
        
        p { margin: 5px 0; }
    </style>
</head>
<body>

    <div class="header">
        <h2>SISTEM INFORMASI LENTERA</h2>
        <p>Laporan Eksekutif Mingguan Pengaduan Warga</p>
    </div>

    <h4>Ringkasan Eksekutif (AI Generated)</h4>
    <div class="summary-box">
        <p style="white-space: pre-wrap; margin: 0;">{{ $ringkasan_ai }}</p>
    </div>

    <h4>Daftar Detail Kerusakan:</h4>
    @foreach($reports as $index => $report)
        <div class="report-item">
            <table class="layout-table">
                <tr>
                    <td class="td-image">
                        <img src="{{ public_path('storage/' . $report->image) }}" class="report-image" alt="Foto Rusak">
                    </td>
                    <td class="td-content">
                        <h4 style="margin-top: 0; margin-bottom: 10px;">{{ $index + 1 }}. {{ $report->title ?? 'Tanpa Judul' }}</h4>
                        <p><strong>Lokasi:</strong> <br> {{ $report->address }}</p>
                        <p><strong>Tingkat Kerusakan:</strong> {{ $report->ai_severity ?? 'Sedang' }}</p>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 10px;">
                <p style="margin: 0;"><strong>Analisis AI:</strong> <br> {{ $report->ai_reasoning ?? 'Tidak ada catatan.' }}</p>
            </div>
        </div>
    @endforeach

</body>
</html>