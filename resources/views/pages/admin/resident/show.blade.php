@extends('layouts.admin')

@section('title', 'Detail Warga')

@section('content')
<!-- Page Heading -->
<a href="{{ route('admin.resident.index') }}" class="btn btn-danger mb-3">Kembali</a>


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Warga</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td>Nama</td>
                <td>{{ $resident->user->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $resident->user->email }}</td>
            </tr>
            <tr>
                <td>Avatar</td>
                <td>
                    <img src="{{ asset('storage/'. $resident->avatar) }}" alt="avatar" width="100">
                </td>
        </table>
    </div>
</div>
@endsection