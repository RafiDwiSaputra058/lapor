@extends('layouts.admin')

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <h1>Admin Dashboard</h1>
        
        <a href="{{ route('admin.summary') }}" class="btn btn-primary shadow-sm">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Buat Ringkasan AI
        </a>
    </div>

    @if(session('summary_result'))
        <div class="alert alert-success shadow-sm mb-4">
            <h5 class="alert-heading fw-bold"><i class="fa-solid fa-file-signature"></i> Ringkasan Eksekutif (AI Generated)</h5>
            <hr>
            <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ session('summary_result') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4 shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Kategori</h6>
                    <h3 class="card-text fw-bold">{{ \App\Models\ReportCategory::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Laporan</h6>
                    <h3 class="card-text fw-bold">{{ \App\Models\Report::count() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Masyarakat</h6>
                    <h3 class="card-text fw-bold">{{ \App\Models\Resident::count() }}</h3>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection