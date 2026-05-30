@extends('layouts.admin')

@section('title', 'Tambah Data Laporan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Tambah Laporan</h4>
    <a href="{{ route('admin.report.index') }}" class="btn btn-danger">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5>Form Tambah Laporan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Kode</label>
                <input type="text" class="form-control" value="AUTO" disabled>
            </div>

            <div class="mb-3">
                <label>Pelapor</label>
                <select name="resident_id" class="form-control">
                    <option>Pilih Pelapor</option>
                    @foreach ($residents as $resident)
                    <option value="{{ $resident->id }}">{{ $resident->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <select name="report_category_id" class="form-control">
                    <option>Pilih Kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Judul</label>
                <input type="text" class="form-control" name="title">
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea class="form-control" name="description" rows="4"></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Latitude</label>
                    <input type="text" class="form-control" name="latitude">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Longitude</label>
                    <input type="text" class="form-control" name="longitude">
                </div>
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea class="form-control" name="address" rows="2"></textarea>
            </div>

            <div class="mb-3">
                <label>Bukti</label>
                <input type="file" class="form-control" name="image">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

@endsection