@extends('layouts.admin')

@section('title', 'Detail Laporan')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Detail Laporan</h4>
    <a href="{{ route('admin.report.index') }}" class="btn btn-danger">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Informasi Laporan -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-primary">Informasi Laporan</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="30%">Kode Laporan</th>
                <td><strong>{{ $report->code }}</strong></td>
            </tr>
            <tr>
                <th>Pelapor</th>
                <td>{{ $report->resident->user->name }} ({{ $report->resident->user->email }})</span>
                </td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $report->reportCategory->name ?? '-' }}</span>
                </td>
            </tr>
            <tr>
                <th>Judul</th>
                <td>{{ $report->title }}</span>
                </td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $report->description }}</span>
                </td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $report->address ?? '-' }}</span>
                </td>
            </tr>
            <tr>
                <th>Koordinat</th>
                <td>Lat: {{ $report->latitude }}, Lng: {{ $report->longitude }}</span>
                </td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ $report->created_at->format('d M Y H:i') }}</span>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Bukti dan Map -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Bukti Laporan</h6>
            </div>
            <div class="card-body text-center">
                @if($report->image)
                    <img src="{{ asset('storage/'. $report->image) }}" width="250" class="rounded shadow-sm">
                @else
                    <p class="text-muted">Tidak ada gambar</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 fw-bold text-primary">Peta Lokasi</h6>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 320px; width: 100%; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;"
                     data-lat="{{ $report->latitude }}"
                     data-lng="{{ $report->longitude }}"
                     data-address="{{ $report->address }}">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analisis AI -->
@if($report->ai_infrastructure_type)
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex align-items-center gap-2">
        <h6 class="m-0 font-weight-bold text-primary">🤖 Analisis AI</h6>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="p-3 rounded text-center" style="background:#f0f7ff;">
                    <div class="text-muted small mb-1">Jenis Infrastruktur</div>
                    <div class="fw-bold text-capitalize">🏗️ {{ $report->ai_infrastructure_type }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded text-center" style="background:
                    @if($report->ai_severity == 'Berat') #fff0f0
                    @elseif($report->ai_severity == 'Sedang') #fffbe6
                    @else #f0fff4 @endif;">
                    <div class="text-muted small mb-1">Tingkat Kerusakan</div>
                    <div class="fw-bold
                        @if($report->ai_severity == 'Berat') text-danger
                        @elseif($report->ai_severity == 'Sedang') text-warning
                        @else text-success @endif">
                        ⚠️ {{ $report->ai_severity }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded text-center" style="background:#f5f0ff;">
                    <div class="text-muted small mb-1">Saran Kategori</div>
                    <div class="fw-bold text-primary">📂 {{ $report->ai_suggested_category }}</div>
                </div>
            </div>
        </div>
        <div class="p-3 rounded" style="background:#f8f9fa; border-left: 4px solid #4e73df;">
            <div class="text-muted small mb-1">Reasoning AI</div>
            <div>{{ $report->ai_reasoning }}</div>
        </div>
    </div>
</div>
@endif

<!-- Status Laporan -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-primary">
            <i class="fas fa-history me-2"></i> Status Laporan
        </h6>
        <a href="{{ route('admin.report-status.create', $report->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Status
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th class="text-center" width="10%">Bukti</th>
                        <th width="15%">Status</th>
                        <th width="50%">Deskripsi</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report->reportStatuses as $status)
                    <tr id="status-row-{{ $status->id }}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            @if($status->image)
                                <img src="{{ asset('storage/'. $status->image) }}" width="35" height="35" class="rounded-circle" style="object-fit: cover;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($status->status == 'pending')
                                <span class="badge bg-warning text-white">Pending</span>
                            @elseif($status->status == 'in_progress')
                                <span class="badge bg-primary text-white">Diproses</span>
                            @elseif($status->status == 'completed')
                                <span class="badge bg-success text-white">Selesai</span>
                            @else
                                <span class="badge bg-secondary text-white">{{ $status->status }}</span>
                            @endif
                        </td>
                        <td>{{ $status->description }}</td>
                        <td class="text-center">
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('admin.report-status.edit', $status->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteStatusModal({{ $status->id }}, '{{ $status->status }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-history fa-2x mb-2 d-block"></i>
                            Belum ada status laporan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white text-muted small">
        Total: {{ $report->reportStatuses->count() }} status
    </div>
</div>

<!-- Modal Konfirmasi Hapus Status -->
<div id="deleteStatusModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3">Hapus Status?</h5>
                <p class="text-muted mb-0" id="deleteStatusMessage">Yakin ingin menghapus status ini?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4 pt-0">
                <button type="button" class="btn btn-secondary btn-sm px-3" onclick="closeStatusModal()">Batal</button>
                <button type="button" class="btn btn-danger btn-sm px-3" id="confirmDeleteStatusBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteStatusId = null;
    let statusModal = null;

    function showDeleteStatusModal(id, statusName) {
        deleteStatusId = id;
        document.getElementById('deleteStatusMessage').innerHTML = 'Yakin ingin menghapus status <strong>' + statusName + '</strong>?';
        statusModal = new bootstrap.Modal(document.getElementById('deleteStatusModal'));
        statusModal.show();
    }

    function closeStatusModal() {
        if (statusModal) {
            statusModal.hide();
        }
    }

    document.getElementById('confirmDeleteStatusBtn').addEventListener('click', function() {
        if (deleteStatusId) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/report-status/' + deleteStatusId;
            form.style.display = 'none';
            
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            var method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
</script>

@endsection

@section('script')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var mapEl = document.getElementById('map');
    var lat = parseFloat(mapEl.dataset.lat);
    var lng = parseFloat(mapEl.dataset.lng);
    var address = mapEl.dataset.address;

    var myapp = L.map('map').setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(myapp);
    L.marker([lat, lng]).addTo(myapp).bindPopup(address).openPopup();
</script>
@endsection