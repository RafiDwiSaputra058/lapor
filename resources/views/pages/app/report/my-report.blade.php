@extends('layouts.app')

@section('title', 'Laporanmu')

@section('content')

<ul class="nav nav-tabs" id="filter-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}"
            href="{{ url()->current() }}?status=pending" id="terkirim-tab" role="tab">
            Terkirim
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ request('status') === 'in_progress' ? 'active' : '' }}"
            href="{{ url()->current() }}?status=in_progress" id="diproses-tab" role="tab">
            Diproses
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ request('status') === 'completed' ? 'active' : '' }}"
            href="{{ url()->current() }}?status=completed" id="selesai-tab" role="tab">
            Selesai
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ request('status') === 'rejected' ? 'active' : '' }}"
            href="{{ url()->current() }}?status=rejected" id="ditolaks-tab" role="tab">
            Ditolak
        </a>
    </li>
</ul>



@forelse ($reports as $report)
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
            <div class="d-flex justify-content-between align-items-end mb-2">
                <p class="text-primary city">{{ $report->address }}</p>
                <p class="text-secondary date">
                    {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y') }}
                </p>
            </div>
            <h1 class="card-title">{{ $report->title }}</h1>
        </div>
    </a>
</div>
@empty
<div class="d-flex flex-column justify-content-center align-items-center" style="height: 75vh" id="no-reports">
    <div id="lottie"></div>
    <h5 class="mt-3">Belum ada laporan</h5>
    <a href="" class="btn btn-primary py-2 px-4 mt-3">
        Buat Laporan
    </a>
</div>
@endforelse
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.12.2/lottie.min.js"></script>
<script>
    var animation = bodymovin.loadAnimation({
        container: document.getElementById('lottie'),
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '{{ asset("assets/app/lottie/not-found.json") }}'
    })
</script>
@endsection