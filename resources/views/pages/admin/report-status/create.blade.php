@extends('layouts.admin')

@section('title', 'Tambah Data Status Laporan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Tambah Status Laporan</h4>
    <a href="{{ route('admin.report.show', $report->id) }}" class="btn btn-danger">Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold">Form Tambah Status</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report-status.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="report_id" value="{{ $report->id }}">

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="in_progress">Diproses</option>
                    <option value="completed">Selesai</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Bukti</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

@endsection