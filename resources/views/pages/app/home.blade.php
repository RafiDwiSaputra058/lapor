@extends('layouts.app')

@section('title', 'Home')

@section('content')
@auth
<h6 class="greeting">Hi, {{ Auth::user()?->name ?? 'User' }} 👋</h6>
@endauth
<h4 class="home-headline">Laporkan masalahmu dan kami segera atasi itu</h4>

<div class="d-flex align-items-center gap-5 py-3 overflow-auto" id="category"
    style="white-space: nowrap;">
    @foreach ($categories as $category)
    <a href="{{ route('report.index', ['category' => $category->name]) }}" class="category d-inline-block">
        <div class="icon">
            <img src="{{ asset('storage/' . $category->image) }}" alt="icon">
        </div>
        <p>{{ $category->name }}</p>
    </a>
    @endforeach


</div>

<div class="py-3" id="reports">
    <div class="d-flex justify-content-between align-items-center">
        <h6>Pengaduan terbaru</h6>
        <a href="{{ route('report.index') }}" class="text-primary text-decoration-none show-more">
            Lihat semua
        </a>
    </div>

    <div class="d-flex flex-column gap-3 mt-3">
        @foreach ($reports->take(3) as $report)
        <div class="card card-report border-0 shadow-none">
            <a href="{{ route('report.show', $report->code) }}" class="text-decoration-none text-dark">
                <div class="card-body p-0">
                    <div class="card-report-image position-relative mb-2">
                        <img src="{{ asset('storage/' . $report->image) }}" alt="">
                        @if ($report->reportStatuses->last()?->status === 'pending')
                        <div class="badge-status on-process">Terkirim</div>
                        @elseif ($report->reportStatuses->last()?->status === 'in_progress')
                        <div class="badge-status on-process">Sedang diproses</div>
                        @elseif ($report->reportStatuses->last()?->status === 'completed')
                        <div class="badge-status done">Selesai</div>
                        @elseif ($report->reportStatuses->last()?->status === 'rejected')
                        <div class="badge-status">Ditolak</div>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-start mb-1 mt-2 px-1">
                        <p class="text-primary city"><i class="fas fa-location-dot me-1"></i>{{ Str::limit($report->address, 40) }}</p>
                        <p class="text-secondary date" style="font-size:0.75rem; flex-shrink:0;">
                            {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                        </p>
                    </div>
                    <h1 class="card-title px-1 pb-2" style="font-size:1rem; font-weight:700;">{{ $report->title }}</h1>
                </div>
            </a>
        </div>
        @endforeach


    </div>

</div>
@endsection