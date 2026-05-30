@extends('layouts.admin')

@section('title', 'Edit Data Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Data Kategori</h4>
    <a href="{{ route('admin.report-category.index') }}" class="btn btn-danger">Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold">Form Edit Kategori</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report-category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $category->name) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label>
                <div class="mb-2">
                    @if($category->image)
                        <img src="{{ asset('storage/'. $category->image) }}" width="80" class="rounded border">
                    @else
                        <span class="text-muted">Tidak ada gambar</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Ganti Gambar</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

@endsection