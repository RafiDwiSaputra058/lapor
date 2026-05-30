@extends('layouts.admin')

@section('title', 'Data Warga')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">👥 Data Warga</h4>
        <p class="text-muted small mb-0">Kelola data seluruh warga yang terdaftar</p>
    </div>
    <a href="{{ route('admin.resident.create') }}" class="btn btn-primary">
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
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Warga</h6>
                        <h2 class="mb-0">{{ $residents->total() }}</h2>
                    </div>
                    <i class="fas fa-users fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Laporan</h6>
                        <h2 class="mb-0">{{ \App\Models\Report::count() }}</h2>
                    </div>
                    <i class="fas fa-file-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Kategori</h6>
                        <h2 class="mb-0">{{ \App\Models\ReportCategory::count() }}</h2>
                    </div>
                    <i class="fas fa-tags fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="card shadow-sm rounded-4">
    <div class="card-header bg-white rounded-top-4 py-3 border-0">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0 fw-bold">Daftar Warga</h6>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="search" class="form-control border-start-0" placeholder="Cari...">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3 text-center">No</th>
                        <th>Email</th>
                        <th>Nama</th>
                        <th class="text-center">Avatar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($residents as $index => $resident)
                    <tr id="row-{{ $resident->id }}">
                        <td class="px-3 text-center fw-semibold">{{ $residents->firstItem() + $index }}</td>
                        <td>{{ $resident->user->email }}</td>
                        <td>{{ $resident->user->name }}</td>
                        <td class="text-center">
                            @if($resident->avatar)
                                <img src="{{ asset('storage/'. $resident->avatar) }}" width="35" height="35" class="rounded-circle" style="object-fit: cover;">
                            @else
                                <i class="fas fa-user-circle fa-2x text-secondary"></i>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.resident.show', $resident->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.resident.edit', $resident->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" onclick="showDeleteModal({{ $resident->id }}, '{{ $resident->user->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-user-slash fa-4x text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">Belum ada data warga</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination dengan angka -->
    <div class="card-footer bg-white rounded-bottom-4 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Menampilkan {{ $residents->firstItem() }} - {{ $residents->lastItem() }} dari {{ $residents->total() }} warga
            </div>
            <div>
                @if ($residents->hasPages())
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous --}}
                            @if ($residents->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $residents->previousPageUrl() }}">Previous</a></li>
                            @endif
                            
                            {{-- Nomor Halaman --}}
                            @foreach ($residents->getUrlRange(1, $residents->lastPage()) as $page => $url)
                                @if ($page == $residents->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                            
                            {{-- Next --}}
                            @if ($residents->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $residents->nextPageUrl() }}">Next</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Next</span></li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-3">
            <div class="modal-body text-center p-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <h5 class="fw-bold mb-2">Hapus Data Warga?</h5>
                <p class="text-muted small" id="deleteMessage">Yakin ingin menghapus data ini?</p>
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

    function showDeleteModal(id, name) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = 'Yakin ingin menghapus data warga <strong>' + name + '</strong>?';
        document.getElementById('deleteForm').action = '/admin/resident/' + id;
        var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        myModal.show();
    }
</script>

<script>
    document.getElementById('search')?.addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let email = row.cells[1]?.innerText.toLowerCase() || '';
            let name = row.cells[2]?.innerText.toLowerCase() || '';
            row.style.display = (email.includes(value) || name.includes(value)) ? '' : 'none';
        });
    });
</script>

@endsection