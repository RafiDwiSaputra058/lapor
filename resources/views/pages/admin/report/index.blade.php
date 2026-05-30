@extends('layouts.admin')

@section('title', 'Data Laporan')

@section('content')

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

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Laporan</h6>
                        <h2 class="mb-0">{{ $totalLaporan }}</h2>
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
                        <h2 class="mb-0">{{ $selesai }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Diproses</h6>
                        <h2 class="mb-0">{{ $diproses }}</h2>
                    </div>
                    <i class="fas fa-spinner fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Pending</h6>
                        <h2 class="mb-0">{{ $pending }}</h2>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-light mb-4 border-0 rounded-3">
    <div class="card-body py-2">
        <div class="d-flex gap-4 align-items-center flex-wrap">
            <span class="fw-semibold small">Indikator Kedaruratan:</span>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-circle text-danger" style="font-size: 0.7rem;"></i>
                <span class="small">≥ 70 (Tinggi)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-circle text-warning" style="font-size: 0.7rem;"></i>
                <span class="small">40 - 69 (Sedang)</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-circle text-success" style="font-size: 0.7rem;"></i>
                <span class="small">&lt; 40 (Rendah)</span>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm rounded-4">
    <div class="card-header bg-white rounded-top-4 py-3 border-0">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0 fw-bold">Daftar Laporan</h6>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="search" class="form-control border-start-0" placeholder="Cari pelapor atau judul...">
                </div>
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
                        <th>Tingkat</th>
                        <th>Urgency</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $index => $report)
                    @php
                    $lastStatus = $report->reportStatuses->last();
                    $status = $lastStatus->status ?? 'pending';
                    $score = $report->urgency_score ?? 0;
                    @endphp
                    <tr id="row-{{ $report->id }}">
                        <td class="px-3 fw-semibold text-primary">{{ $reports->firstItem() + $index }}</td>
                        <td>{{ $report->resident->user->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $report->reportCategory->name ?? '-' }}</span>
                        </td>
                        <td>{{ Str::limit($report->title, 40) }}</td>
                        <td>
                            @if($report->ai_severity == 'Berat')
                            <span class="badge bg-danger text-white">Berat</span>
                            @elseif($report->ai_severity == 'Sedang')
                            <span class="badge bg-warning text-white">Sedang</span>
                            @elseif($report->ai_severity == 'Ringan')
                            <span class="badge bg-success text-white">Ringan</span>
                            @else
                            <span class="badge bg-secondary text-white">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress" style="width: 60px; height: 6px;">
                                    <div class="progress-bar {{ $score >= 70 ? 'bg-danger' : ($score >= 40 ? 'bg-warning' : 'bg-success') }}"
                                        style="width: {{ $score }}%"></div>
                                </div>
                                <span class="fw-bold small">{{ $score }}</span>
                            </div>
                        </td>
                        <td>
                            @if($status == 'pending')
                            <span class="badge bg-warning text-white">Pending</span>
                            @elseif($status == 'in_progress')
                            <span class="badge bg-primary text-white">Diproses</span>
                            @elseif($status == 'completed')
                            <span class="badge bg-success text-white">Selesai</span>
                            @else
                            <span class="badge bg-secondary text-white">{{ $status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
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
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">Belum ada data laporan</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white rounded-bottom-4 py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="text-muted small">
                <i class="fas fa-list me-1"></i>
                Menampilkan <span class="fw-semibold text-primary">{{ $reports->firstItem() }}</span>
                - <span class="fw-semibold text-primary">{{ $reports->lastItem() }}</span>
                dari <span class="fw-semibold text-primary">{{ $reports->total() }}</span> laporan
            </div>
            <div>
                @if ($reports->hasPages())
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        @if ($reports->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link rounded-3"><i class="fas fa-chevron-left"></i></span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link rounded-3" href="{{ $reports->previousPageUrl() }}">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        @endif

                        @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                        @if ($page == $reports->currentPage())
                        <li class="page-item active">
                            <span class="page-link rounded-3">{{ $page }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link rounded-3" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endif
                        @endforeach

                        @if ($reports->hasMorePages())
                        <li class="page-item">
                            <a class="page-link rounded-3" href="{{ $reports->nextPageUrl() }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                        @else
                        <li class="page-item disabled">
                            <span class="page-link rounded-3"><i class="fas fa-chevron-right"></i></span>
                        </li>
                        @endif
                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-3">
            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <h5 class="fw-bold mb-2">Hapus Laporan?</h5>
                <p class="text-muted small" id="deleteMessage">Yakin ingin menghapus laporan ini?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-3 rounded-3">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteId = null;

    function showDeleteModal(id, title) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = 'Yakin ingin menghapus laporan <strong>' + title + '</strong>?';
        document.getElementById('deleteForm').action = '/admin/report/' + id;
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
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