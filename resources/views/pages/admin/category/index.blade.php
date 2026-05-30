@extends('layouts.admin')

@section('title', 'Data Kategori Laporan')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">📁 Data Kategori Laporan</h4>
        <p class="text-muted small mb-0">Kelola jenis-jenis kategori laporan masyarakat</p>
    </div>
    <a href="{{ route('admin.report-category.create') }}" class="btn btn-primary">
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
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Total Kategori</h6>
                        <h2 class="mb-0">{{ $categories->count() }}</h2>
                    </div>
                    <i class="fas fa-tags fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
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
</div>

<!-- Tabel -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0">Daftar Kategori</h6>
            </div>
            <div class="col-md-6">
                <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari kategori...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="px-3 text-center" width="5%">No</th>
                        <th width="40%">Nama Kategori</th>
                        <th class="text-center" width="25%">Icon</th>
                        <th class="text-center" width="30%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr id="row-{{ $category->id }}">
                        <td class="px-3 text-center">{{ $loop->iteration }}</td>
                        <td>
                            <i class="fas fa-folder-open text-primary me-2"></i>
                            {{ $category->name }}
                        </td>
                        <td class="text-center">
                            @if($category->image && file_exists(storage_path('app/public/' . $category->image)))
                                <img src="{{ asset('storage/'. $category->image) }}" 
                                     width="40" height="40" 
                                     class="rounded-3 border"
                                     style="object-fit: cover;">
                            @else
                                <div class="rounded-3 d-inline-flex align-items-center justify-content-center text-white"
                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('admin.report-category.show', $category->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.report-category.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $category->id }}, '{{ $category->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open mb-2 d-block"></i>
                            Belum ada data kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <small class="text-muted">Total: {{ $categories->count() }} kategori</small>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3">Hapus Kategori?</h5>
                <p class="text-muted mb-0" id="deleteMessage">Yakin ingin menghapus data ini?</p>
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

    function showDeleteModal(id, name) {
        deleteId = id;
        document.getElementById('deleteMessage').innerHTML = 'Yakin ingin menghapus kategori <strong>' + name + '</strong>?';
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
            form.action = '/admin/report-category/' + deleteId;
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
            let name = row.cells[1]?.innerText.toLowerCase() || '';
            row.style.display = name.includes(value) ? '' : 'none';
        });
    });
</script>

@endsection