@extends('layouts.admin')

@section('title', 'Edit Data Status Laporan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Status Laporan</h4>
    <a href="{{ route('admin.report.show', $status->report->id) }}" class="btn btn-danger">Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold">Form Edit Status</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report-status.update', $status->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="report_id" value="{{ $status->report->id }}">

            <div class="mb-3">
                <label class="form-label">Bukti</label>
                @if($status->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'. $status->image) }}" width="100" class="rounded border">
                    </div>
                @endif
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ old('status', $status->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $status->status) == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ old('status', $status->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ old('status', $status->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="description" rows="4">{{ old('description', $status->description) }}</textarea>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>

@endsection