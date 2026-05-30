@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h4 class="fw-bold mb-1 text-gray-800">📊 Admin Dashboard</h4>
            <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('admin.summary') }}" class="btn btn-primary rounded-3 shadow-sm">
            <i class="fas fa-magic me-1"></i> Buat Ringkasan AI
        </a>
    </div>

    @if(session('summary_result'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 rounded-3" role="alert">
            <div class="d-flex">
                <i class="fas fa-file-alt fa-2x me-3"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1">Ringkasan Eksekutif (AI Generated)</h6>
                    <hr class="my-2">
                    <p class="mb-0" style="white-space: pre-wrap;">{{ session('summary_result') }}</p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 rounded-3" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Kategori</h6>
                            <h2 class="fw-bold mb-0">{{ \App\Models\ReportCategory::count() }}</h2>
                        </div>
                        <i class="fas fa-tags fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Laporan</h6>
                            <h2 class="fw-bold mb-0">{{ \App\Models\Report::count() }}</h2>
                        </div>
                        <i class="fas fa-file-alt fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Total Masyarakat</h6>
                            <h2 class="fw-bold mb-0">{{ \App\Models\Resident::count() }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-map-marker-alt text-primary me-2"></i> Peta Sebaran Laporan Warga
            </h6>
        </div>
        <div class="card-body p-0">
            <div id="laporMap" style="height: 450px; width: 100%; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;"></div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('laporMap').setView([-7.7203, 110.3835], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // DAFTARKAN GAMBAR PIN WARNA-WARNI DARI INTERNET (CDN)
        var colorMarkers = {
            green: new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            }),
            gold: new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-gold.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            }),
            red: new L.Icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            })
        };

        var reports = @json($reports);

        reports.forEach(function(report) {
            var imageUrl = report.image ? '/storage/' + report.image : 'https://placehold.co/150x100?text=No+Image';
            
            var badgeColor = 'bg-secondary';
            var selectedPin = colorMarkers.gold; // Pin default kuning emas
            var severity = report.ai_severity ? report.ai_severity.toLowerCase() : 'sedang';
            
            if(severity.includes('ringan')) {
                badgeColor = 'bg-success';
                selectedPin = colorMarkers.green;
            } else if(severity.includes('sedang')) {
                badgeColor = 'bg-warning text-dark';
                selectedPin = colorMarkers.gold;
            } else if(severity.includes('berat')) {
                badgeColor = 'bg-danger';
                selectedPin = colorMarkers.red;
            }

            var popupContent = `
                <div style="width: 240px;">
                    <img src="${imageUrl}" alt="Foto" style="width: 100%; height: 130px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                    <h6 class="fw-bold mb-1">${report.title}</h6>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1 text-danger"></i> ${report.address || 'Tidak ada alamat'}</p>
                    <span class="badge ${badgeColor} px-3 py-2">${report.ai_severity || 'Sedang'}</span>
                </div>
            `;

            if(report.latitude && report.longitude) {
                // Tanamkan Pin yang sudah dipilih warnanya ke Peta
                L.marker([report.latitude, report.longitude], {icon: selectedPin})
                    .addTo(map)
                    .bindPopup(popupContent);
            }
        });
    });
</script>

<script>
    // Auto close alert after 5 seconds
    setTimeout(function() {
        let alert = document.querySelector('.alert');
        if(alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 5000);
</script>

@endsection