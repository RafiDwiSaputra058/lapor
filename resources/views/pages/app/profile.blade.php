@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center gap-2">
    <div class="position-relative">
        <img src="{{ asset('storage/' . Auth::user()->resident->avatar) }}" alt="avatar" class="avatar" style="width:100px; height:100px; border-radius:50%; object-fit:cover;">
        <label for="avatar-input" class="position-absolute bottom-0 end-0 rounded-circle d-flex align-items-center justify-content-center"
            style="width:30px;height:30px;background:var(--primary);cursor:pointer;">
            <i class="fas fa-camera text-white" style="font-size:12px;"></i>
        </label>
    </div>
    <h5 class="mt-2">{{ Auth::user()->name }}</h5>

    @if(session('success'))
    <small class="text-success">{{ session('success') }}</small>
    @endif

    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show w-100 mt-2" role="alert">
        <small>{{ session('status') }}</small>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
        @csrf
        <input type="file" id="avatar-input" name="avatar" class="d-none" accept="image/*"
            onchange="document.getElementById('avatar-form').submit()">
    </form>
</div>

<div class="row mt-4">
    <div class="col-6">
        <div class="card profile-stats shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-primary">
                    {{ Auth::user()->resident->reports()->whereHas('reportStatuses', fn($q) => $q->whereIn('status', ['pending','in_progress'])->whereIn('id', fn($s) => $s->selectRaw('MAX(id)')->from('report_statuses')->groupBy('report_id')))->count() }}
                </h5>
                <p class="card-text small text-muted">Laporan Aktif</p>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card profile-stats shadow-sm border-0">
            <div class="card-body text-center">
                <h5 class="card-title fw-bold text-success">
                    {{ Auth::user()->resident->reports()->whereHas('reportStatuses', fn($q) => $q->where('status', 'completed')->whereIn('id', fn($s) => $s->selectRaw('MAX(id)')->from('report_statuses')->groupBy('report_id')))->count() }}
                </h5>
                <p class="card-text small text-muted">Laporan Selesai</p>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="list-group list-group-flush profile-menu">
        {{-- Tombol Kata Sandi (Memicu Modal) --}}
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom"
            data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-lock text-muted"></i>
                <p class="fw-light mb-0">Kata sandi</p>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
        </a>
        
        {{-- Tombol Bantuan dan Dukungan (Memicu Modal) --}}
        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 border-0 border-bottom"
            data-bs-toggle="modal" data-bs-target="#supportModal">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-question-circle text-muted"></i>
                <p class="fw-light mb-0">Bantuan dan dukungan</p>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
        </a>
    </div>

    <div class="mt-5">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <button class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Keluar
        </button>
    </div>
</div>

{{-- ========================================= --}}
{{-- KUMPULAN MODAL (POP-UP) --}}
{{-- ========================================= --}}

{{-- 1. Modal Ganti Kata Sandi (Dummy Flow) --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold" id="changePasswordModalLabel">Ganti Kata Sandi</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.password.update.dummy') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->hasBag('updatePassword'))
                        <div class="alert alert-danger p-2 small">
                            {{ $errors->updatePassword->first() }}
                        </div>
                    @endif
                    <div class="mb-3 bg-light p-3 rounded-3">
                        <label class="form-label small text-muted mb-1">Kata Sandi Lama</label>
                        <input type="password" class="form-control border-0 bg-transparent px-0 shadow-none" name="old_password" placeholder="Masukkan sandi lama" required>
                    </div>
                    <div class="mb-3 bg-light p-3 rounded-3">
                        <label class="form-label small text-muted mb-1">Kata Sandi Baru</label>
                        <input type="password" class="form-control border-0 bg-transparent px-0 shadow-none" name="new_password" placeholder="Minimal 8 karakter" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. Modal Bantuan & Dukungan --}}
<div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold text-center w-100" id="supportModalLabel">Bantuan & Dukungan</h1>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-4">
                    <small class="text-muted d-block mb-1">Hubungi Call Center LaporIn</small>
                    <h3 class="fw-bold text-primary mb-0">0812 3333 4444</h3>
                </div>
                
                <hr class="text-muted opacity-25 w-75 mx-auto my-4">
                
                <small class="text-muted d-block mb-3">Kunjungi media sosial kami</small>
                <div class="d-flex justify-content-center gap-3">
                    <a href="https://instagram.com" target="_blank" class="text-decoration-none text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); font-size: 1.5rem;">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" class="text-decoration-none text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #1877F2; font-size: 1.5rem;">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk memunculkan modal kembali jika ada error validasi password --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->hasBag('updatePassword'))
            var myModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            myModal.show();
        @endif
    });
</script>
@endsection