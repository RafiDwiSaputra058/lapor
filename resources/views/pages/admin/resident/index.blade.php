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
                        <h2 class="mb-0">{{ $residents->count() }}</h2>
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
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h6 class="mb-0">Daftar Warga</h6>
            </div>
            <div class="col-md-6">
                <input type="text" id="search" class="form-control form-control-sm" placeholder="Cari nama atau email...">
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">No</th>
                        <th>Email</th>
                        <th>Nama</th>
                        <th class="text-center">Avatar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($residents as $resident)
                    <tr id="row-{{ $resident->id }}">
                        <td class="px-3">{{ $loop->iteration }}</td>
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
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('admin.resident.show', $resident->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.resident.edit', $resident->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal({{ $resident->id }}, '{{ $resident->user->name }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-user-slash mb-2 d-block"></i>
                            Belum ada data warga
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <small class="text-muted">Total: {{ $residents->count() }} warga</small>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Sederhana -->
<div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold mb-3">Hapus Data Warga?</h5>
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
        document.getElementById('deleteMessage').innerHTML = 'Yakin ingin menghapus data warga <strong>' + name + '</strong>?';
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
            form.action = '/admin/resident/' + deleteId;
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
            let email = row.cells[1]?.innerText.toLowerCase() || '';
            let name = row.cells[2]?.innerText.toLowerCase() || '';
            row.style.display = (email.includes(value) || name.includes(value)) ? '' : 'none';
        });
    });
</script>

@endsection