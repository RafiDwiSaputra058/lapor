@extends('layouts.admin')

@section('title', 'Data Laporan')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">📋 Data Laporan</h4>
        <p class="text-muted small mb-0">Kelola semua laporan yang masuk dari masyarakat</p>
    </div>
    <a href="{{ route('admin.report.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah Data
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white" style="background-color: #36b9cc;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Laporan</h6>
                        <h2 class="mb-0">{{ $reports->count() }}</h2>
                    </div>
                    <i class="fas fa-file-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Selesai</h6>
                        <h2 class="mb-0">{{ \App\Models\ReportStatus::where('status', 'completed')->count() }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background-color: #4e73df;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Diproses</h6>
                        <h2 class="mb-0">{{ \App\Models\ReportStatus::where('status', 'in_progress')->count() }}</h2>
                    </div>
                    <i class="fas fa-spinner fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background-color: #f6c23e;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Pending</h6>
                        <h2 class="mb-0">{{ \App\Models\ReportStatus::where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0">Daftar Laporan</h6>
            </div>
            <div class="col-md-6">
                <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari pelapor atau judul...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">No</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Judul Laporan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                    @php
                        $lastStatus = $report->reportStatuses->last();
                        $status = $lastStatus->status ?? 'pending';
                    @endphp
                    <tr id="row-{{ $report->id }}">
                        <td class="px-3">{{ $loop->iteration }}</td>
                        <td>{{ $report->resident->user->name ?? '-' }}</td>
                        <td>{{ $report->reportCategory->name ?? '-' }}</td>
                        <td>{{ Str::limit($report->title, 40) }}</td>
                        <td class="text-center">
                            @if($report->image)
                                <img src="{{ asset('storage/'. $report->image) }}" width="35" height="35" class="rounded" style="object-fit: cover;">
                            @else
                                <i class="fas fa-image text-secondary"></i>
                            @endif
                        </td>
                        <td>
                            @if($status == 'pending')
                                <span class="badge bg-warning text-white px-3 py-2">Pending</span>
                            @elseif($status == 'in_progress')
                                <span class="badge bg-primary text-white px-3 py-2">Diproses</span>
                            @elseif($status == 'completed')
                                <span class="badge bg-success text-white px-3 py-2">Selesai</span>
                            @else
                                <span class="badge bg-secondary text-white px-3 py-2">{{ $status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('admin.report.show', $report->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.report.edit', $report->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $report->id }}, '{{ $report->title }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-file-alt mb-2 d-block"></i>
                            Belum ada data laporan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <small class="text-muted">Total: {{ $reports->count() }} laporan</small>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3">Hapus Laporan?</h5>
                <p class="text-muted mb-0" id="deleteMessage">Yakin ingin menghapus laporan ini?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4 pt-0">
                <button type="button" class="btn btn-secondary btn-sm px-3" onclick="closeModal()">Batal</button>
                <button type="button" class="btn btn-danger btn-sm px-3" id="confirmDeleteBtn">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteId = null;
    let myModal = null;

    function showDeleteModal(id, title) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = 'Yakin ingin menghapus laporan <strong>' + title + '</strong>?';
        myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }

    function closeModal() {
        if (myModal) {
            myModal.hide();
        }
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteId) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/report/' + deleteId;
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

<script>
    document.getElementById('search')?.addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let pelapor = row.cells[1]?.innerText.toLowerCase() || '';
            let judul = row.cells[3]?.innerText.toLowerCase() || '';
            row.style.display = (pelapor.includes(value) || judul.includes(value)) ? '' : 'none';
        });
    });
</script>

@endsection