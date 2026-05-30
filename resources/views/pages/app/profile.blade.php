@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center gap-2">
    <div class="position-relative">
        <img src="{{ asset('storage/' . Auth::user()->resident->avatar) }}" alt="avatar" class="avatar">
        <label for="avatar-input" class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center"
            style="width:30px;height:30px;background:var(--primary);cursor:pointer;">
            <i class="fas fa-camera text-white" style="font-size:12px;"></i>
        </label>
    </div>
    <h5>{{ Auth::user()->name }}</h5>

    @if(session('success'))
    <small class="text-success">{{ session('success') }}</small>
    @endif

    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
        @csrf
        <input type="file" id="avatar-input" name="avatar" class="d-none" accept="image/*"
            onchange="document.getElementById('avatar-form').submit()">
    </form>
</div>

<div class="row mt-4">
    <div class="col-6">
        <div class="card profile-stats">
            <div class="card-body">
                <h5 class="card-title">
                    {{ Auth::user()->resident->reports()
        ->whereHas('reportStatuses', fn($q) => $q->whereIn('status', ['pending','in_progress'])
        ->whereIn('id', fn($s) => $s->selectRaw('MAX(id)')->from('report_statuses')->groupBy('report_id')))
        ->count() }}
                </h5>
                <p class="card-text">Laporan Aktif</p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card profile-stats">
            <div class="card-body">
                <h5 class="card-title">
                    {{ Auth::user()->resident->reports()
        ->whereHas('reportStatuses', fn($q) => $q->where('status', 'completed')
        ->whereIn('id', fn($s) => $s->selectRaw('MAX(id)')->from('report_statuses')->groupBy('report_id')))
        ->count() }}
                </h5>
                <p class="card-text">Laporan Selesai</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="list-group list-group-flush">
        <a href="#"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-user"></i>
                <p class="fw-light">Pengaturan Akun</p>
            </div>
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        <a href="#"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-lock"></i>
                <p class="fw-light"> Kata sandi</p>
            </div>
            <i class="fa-solid fa-chevron-right"></i>
        </a>
        <a href="#"
            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-question-circle"></i>
                <p class="fw-light">Bantuan dan dukungan</p>
            </div>
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    </div>

    <div class="mt-4">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <button class="btn btn-outline-danger w-100 rounded-pill"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">>
            Keluar
        </button>
    </div>
</div>

@endsection