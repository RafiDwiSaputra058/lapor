@extends('layouts.admin')

@section('title', 'Edit Data Warga')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Data Warga</h4>
    <a href="{{ route('admin.resident.index') }}" class="btn btn-danger">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-primary">
            </i> Form Edit Warga
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.resident.update', $resident->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name', $resident->user->name) }}" required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control bg-light" 
                       name="email" value="{{ old('email', $resident->user->email) }}" readonly>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" placeholder="Kosongkan jika tidak diubah">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Profil</label>
                @if($resident->avatar)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'. $resident->avatar) }}" 
                             width="60" height="60" class="rounded-circle border">
                        <br>
                        <small class="text-muted">Foto saat ini</small>
                    </div>
                @endif
                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                       name="avatar" accept="image/*">
                @error('avatar')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Update
            </button>
        </form>
    </div>
</div>

@endsection