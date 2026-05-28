@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <h1 class="h3 mb-0 text-gray-800"><i class="fa-solid fa-map-location-dot"></i> Peta Sebaran Laporan Warga</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div id="laporMap" style="height: 600px; width: 100%; border-radius: 8px;"></div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Inisialisasi Peta (Titik tengah saya set ke area Sleman / Jogja)
        var map = L.map('laporMap').setView([-7.7203, 110.3835], 12);

        // 2. Pasang Kulit Peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors | LENTERA'
        }).addTo(map);

        // 3. Ambil data laporan dari Controller yang sudah diubah ke format JSON
        var reports = @json($reports);

        // 4. Looping data laporan untuk membuat Pin/Marker
        reports.forEach(function(report) {
            
            // Siapkan URL gambar
            var imageUrl = report.image ? '/storage/' + report.image : 'https://via.placeholder.com/150';
            
            // Siapkan warna badge berdasarkan tingkat kerusakan (Severity)
            var badgeColor = 'bg-secondary';
            var severity = report.ai_severity ? report.ai_severity.toLowerCase() : 'sedang';
            if(severity.includes('ringan')) badgeColor = 'bg-success';
            else if(severity.includes('sedang')) badgeColor = 'bg-warning text-dark';
            else if(severity.includes('berat')) badgeColor = 'bg-orange'; // Asumsi ada CSS orange
            else if(severity.includes('kritis')) badgeColor = 'bg-danger';

            // Desain Popup saat Pin diklik
            var popupContent = `
                <div style="width: 220px; text-align:center;">
                    <img src="${imageUrl}" alt="Foto" style="width: 100%; height: 120px; object-fit: cover; border-radius: 6px; margin-bottom: 8px;">
                    <h6 style="font-weight: bold; margin-bottom: 5px; color: #333;">${report.title}</h6>
                    <p style="font-size: 11px; color: #666; margin-bottom: 8px; line-height: 1.3;">
                        <i class="fa-solid fa-location-dot"></i> ${report.address}
                    </p>
                    <span class="badge ${badgeColor}" style="padding: 5px 10px;">${report.ai_severity || 'Sedang'}</span>
                </div>
            `;

            // Pasang Pin di Peta
            L.marker([report.latitude, report.longitude])
                .addTo(map)
                .bindPopup(popupContent);
        });
        
    });
</script>
@endsection