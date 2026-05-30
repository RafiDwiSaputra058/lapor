@extends('layouts.admin')

@section('title', 'Tambah Data Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Tambah Data Kategori</h4>
    <a href="{{ route('admin.report-category.index') }}" class="btn btn-danger">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold">Form Tambah Kategori</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report-category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       name="image" accept="image/*">
                <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <button type="submit" class="btn btn-primary px-4">Simpan</button>
        </form>
    </div>
</div>

@endsection