@extends('layouts.admin')

@section('title', 'Detail Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Detail Kategori</h4>
    <a href="{{ route('admin.report-category.index') }}" class="btn btn-danger">Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold">Informasi Kategori</h6>
    </div>
    <div class="card-body">
        <div class="text-center mb-3">
            @if($category->image)
                <img src="{{ asset('storage/'. $category->image) }}" width="120" class="rounded-3 border">
            @else
                <i class="fas fa-folder-open fa-4x text-muted"></i>
            @endif
        </div>
        <table class="table table-bordered">
            <tr>
                <th width="30%">ID</th>
                <td>{{ $category->id }}</span>
                </td>
            </tr>
            <tr>
                <th>Nama Kategori</th>
                <td>{{ $category->name }}</span>
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $category->created_at->format('d M Y, H:i') }}</span>
                </td>
            </tr>
        </table>
    </div>
</div>

@endsection