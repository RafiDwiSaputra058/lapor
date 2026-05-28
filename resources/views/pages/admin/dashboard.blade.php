@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1 class="h3 mb-0 text-gray-800">Admin Dashboard</h1>
        
        <a href="{{ route('admin.summary') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Buat Ringkasan AI
        </a>
    </div>

    @if(session('summary_result'))
        <div class="alert alert-success shadow-sm mb-4">
            <h5 class="alert-heading fw-bold"><i class="fa-solid fa-file-signature"></i> Ringkasan Eksekutif (AI Generated)</h5>
            <hr>
            <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ session('summary_result') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm mb-4 border-left-primary">
                <div class="card-body">
                    <h6 class="card-title text-muted fw-bold">Total Kategori</h6>
                    <h3 class="card-text fw-bold text-gray-800">{{ \App\Models\ReportCategory::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm mb-4 border-left-success">
                <div class="card-body">
                    <h6 class="card-title text-muted fw-bold">Total Laporan</h6>
                    <h3 class="card-text fw-bold text-gray-800">{{ \App\Models\Report::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm mb-4 border-left-warning">
                <div class="card-body">
                    <h6 class="card-title text-muted fw-bold">Total Masyarakat</h6>
                    <h3 class="card-text fw-bold text-gray-800">{{ \App\Models\Resident::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fa-solid fa-map-location-dot"></i> Peta Sebaran Laporan Warga</h6>
                </div>
                <div class="card-body p-0">
                    <div id="laporMap" style="height: 500px; width: 100%; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Inisialisasi Peta (Titik tengah default)
        var map = L.map('laporMap').setView([-7.7203, 110.3835], 12);

        // Pasang Kulit Peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors | LENTERA'
        }).addTo(map);

        // Ambil data laporan dari Controller
        var reports = @json($reports);

        // Looping data laporan untuk membuat Pin/Marker
        reports.forEach(function(report) {
            
            var imageUrl = report.image ? '/storage/' + report.image : 'https://via.placeholder.com/150';
            
            var badgeColor = 'bg-secondary';
            var severity = report.ai_severity ? report.ai_severity.toLowerCase() : 'sedang';
            if(severity.includes('ringan')) badgeColor = 'bg-success';
            else if(severity.includes('sedang')) badgeColor = 'bg-warning text-dark';
            else if(severity.includes('berat')) badgeColor = 'bg-orange'; 
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

            L.marker([report.latitude, report.longitude])
                .addTo(map)
                .bindPopup(popupContent);
        });
        
    });
</script>
@endsection