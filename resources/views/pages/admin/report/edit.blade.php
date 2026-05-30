@extends('layouts.admin')

@section('title', 'Edit Data Laporan')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Edit Data Laporan</h4>
    <a href="{{ route('admin.report.index') }}" class="btn btn-danger">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report.update', $report->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">Kode</label>
                        <input type="text" class="form-control bg-light" id="code" name="code" value="{{ $report->code }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="resident">Pelapor</label>
                        <select name="resident_id" class="form-control">
                            @foreach ($residents as $resident)
                            <option value="{{ $resident->id }}" @if (old('resident_id', $report->resident_id)==$resident->id) selected @endif>
                                {{ $resident->user->name }} ({{ $resident->user->email }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category">Kategori Laporan</label>
                        <select name="report_category_id" class="form-control">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (old('report_category_id', $report->report_category_id)==$category->id) selected @endif>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Judul Laporan</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $report->title) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Laporan</label>
                <textarea class="form-control" id="description" name="description" rows="5">{{ old('description', $report->description) }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{ old('latitude', $report->latitude) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{ old('longitude', $report->longitude) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $report->address) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Bukti Laporan</label>
                <br>
                @if($report->image)
                    <img src="{{ asset('storage/'. $report->image) }}" alt="image" width="100" class="mb-2 rounded border">
                @endif
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

@endsection