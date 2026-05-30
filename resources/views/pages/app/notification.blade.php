@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('home') }}" class="text-dark"><i class="fa-solid fa-chevron-left"></i></a>
    <h4 class="fw-bold mb-0">Notifikasi</h4>
</div>

<div class="notification-list">
    @forelse($reports as $report)
        @php
            $latestStatus = $report->reportStatuses->last();
            $statusColor = match($latestStatus->status) {
                'pending' => 'primary',
                'in_progress' => 'warning',
                'completed' => 'success',
                'rejected' => 'danger',
                default => 'secondary'
            };
            
            $statusText = match($latestStatus->status) {
                'pending' => 'berhasil terkirim dan menunggu verifikasi.',
                'in_progress' => 'sedang diproses oleh tim kami.',
                'completed' => 'telah dinyatakan SELESAI. Terima kasih!',
                'rejected' => 'ditolak. Silakan cek detail alasan di halaman laporan.',
                default => 'mengalami perubahan status.'
            };
        @endphp

        <div class="card mb-3 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body d-flex gap-3">
                <div class="flex-shrink-0">
                    <div class="rounded-circle bg-{{ $statusColor }} d-flex align-items-center justify-content-center text-white" style="width: 45px; height: 45px;">
                        <i class="fa-solid {{ $latestStatus->status == 'completed' ? 'fa-check' : 'fa-bell' }}"></i>
                    </div>
                </div>
                <div>
                    <p class="mb-1 small text-muted">{{ $latestStatus->created_at->diffForHumans() }}</p>
                    <p class="mb-0 fw-normal" style="font-size: 0.95rem;">
                        Laporanmu tentang <strong>{{ $report->ai_infrastructure_type ?? $report->category->name }}</strong> ({{ $report->code }}) {{ $statusText }}
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fa-solid fa-bell-slash text-muted mb-3" style="font-size: 3rem;"></i>
            <p class="text-muted">Belum ada notifikasi untukmu.</p>
        </div>
    @endforelse
</div>
@endsection