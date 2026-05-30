@extends('layouts.admin')

@section('title', 'Detail Warga')

@section('content')

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Detail Warga</h4>
    <a href="{{ route('admin.resident.index') }}" class="btn btn-danger btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width="30%">Nama</th>
                <td>{{ $resident->user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $resident->user->email }}</td>
            </tr>
            <tr>
                <th>Bergabung</th>
                <td>{{ $resident->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Avatar</th>
                <td>
                    @if($resident->avatar && file_exists(storage_path('app/public/' . $resident->avatar)))
                        <img src="{{ asset('storage/'. $resident->avatar) }}" width="60" height="60" class="rounded-circle" style="object-fit: cover;">
                    @else
                        <i class="fas fa-user-circle fa-3x text-secondary"></i>
                    @endif
                </td>
            </tr>
        </table>
        
        <!-- Tombol Edit di bawah -->
        <hr>
        <div class="text-center">
            <a href="{{ route('admin.resident.edit', $resident->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Data Warga
            </a>
        </div>
    </div>
</div>

@endsection