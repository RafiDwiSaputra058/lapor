@extends('layouts.admin')

@section('title', 'Tambah Data Warga')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Tambah Data Warga</h4>
    <a href="{{ route('admin.resident.index') }}" class="btn btn-danger">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-primary">
            Form Tambah Warga
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.resident.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required>
                <small class="text-muted">Minimal 6 karakter</small>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Profil</label>
                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                       name="avatar" accept="image/*">
                <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                @error('avatar')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Simpan
            </button>
        </form>
    </div>
</div>

@endsection