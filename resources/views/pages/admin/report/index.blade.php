@extends('layouts.admin')

@section('title', 'Data Laporan')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Data Laporan</h4>
        <small class="text-muted">{{ $reports->count() }} laporan tercatat</small>
    </div>
    <a href="{{ route('admin.report.create') }}" class="btn btn-primary px-4">+ Tambah</a>
</div>


{{-- RANKED LIST BY URGENCY --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="fw-bold mb-1">🎯 Prioritas Penanganan</div>
                <div class="text-muted small">Diurutkan berdasarkan AI Urgency Score tertinggi</div>
            </div>

            {{-- PETUNJUK RANGE ANGKA + DOT WARNA --}}
            <div class="d-flex flex-wrap gap-3 align-items-center bg-light px-3 py-2 rounded-3">
                <span class="small text-muted fw-semibold me-1">Indikator Kedaruratan:</span>

                <div class="d-flex align-items-center gap-1.5">
                    <div style="width:10px; height:10px; border-radius:50%; background-color:#dc3545;"></div>
                    <span class="small text-muted" style="font-size:0.8rem;">&ge; 70 (Tinggi)</span>
                </div>

                <div class="d-flex align-items-center gap-1.5">
                    <div style="width:10px; height:10px; border-radius:50%; background-color:#ffc107;"></div>
                    <span class="small text-muted" style="font-size:0.8rem;">40 - 69 (Sedang)</span>
                </div>

                <div class="d-flex align-items-center gap-1.5">
                    <div style="width:10px; height:10px; border-radius:50%; background-color:#198754;"></div>
                    <span class="small text-muted" style="font-size:0.8rem;">&lt; 40 (Rendah)</span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:0.875rem;">
                <thead>
                    <tr style="border-bottom:2px solid #f1f3f5;">
                        {{-- Semua header diberi class text-center --}}
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Prioritas</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Kode</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Pelapor</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Judul</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Infrastruktur</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Severity</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Urgency Score</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Status</th>
                        <th class="pb-3 fw-semibold text-muted text-center" style="font-size:0.75rem;text-transform:uppercase;border:none;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports->sortByDesc('urgency_score') as $report)
                    <tr style="border-bottom:1px solid #f8f9fa;">
                        {{-- Kolom Prioritas --}}
                        <td class="py-3 text-center">
                            @if($loop->index === 0)
                            <span class="fw-bold" style="color:#E65100;">#1</span>
                            @elseif($loop->index === 1)
                            <span class="fw-bold" style="color:#1DA1F2;">#2</span>
                            @elseif($loop->index === 2)
                            <span class="fw-bold" style="color:#9C6B00;">#3</span>
                            @else
                            <span class="text-muted">#{{ $loop->iteration }}</span>
                            @endif
                        </td>

                        {{-- Kolom Kode --}}
                        <td class="py-3 text-muted text-center" style="font-size:0.8rem;">{{ $report->code }}</td>

                        {{-- Kolom Pelapor (Rata Tengah) --}}
                        <td class="py-3 fw-semibold text-center">{{ $report->resident?->user?->name ?? 'Warga Terhapus' }}</td>

                        {{-- Kolom Judul (Rata Tengah) --}}
                        <td class="py-3 text-center">{{ Str::limit($report->title, 25) }}</td>

                        {{-- Kolom Infrastruktur (Rata Tengah) --}}
                        <td class="py-3 text-capitalize text-center">{{ $report->ai_infrastructure_type ?? '-' }}</td>

                        {{-- Kolom Severity (Rata Tengah) --}}
                        <td class="py-3 text-center">
                            @if($report->ai_severity == 'Berat')
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#FFF0F0;color:#C92A2A;font-size:0.75rem;">Berat</span>
                            @elseif($report->ai_severity == 'Sedang')
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#FFF3E0;color:#E65100;font-size:0.75rem;">Sedang</span>
                            @elseif($report->ai_severity == 'Ringan')
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#EBFBEE;color:#2F9E44;font-size:0.75rem;">Ringan</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Kolom Urgency Score (Sudah Rata Tengah & Bebas Error Style) --}}
                        <td class="py-3 text-center">
                            @if($report->urgency_score)
                            @php
                            $score = $report->urgency_score;
                            $dotClass = $score >= 70 ? 'text-danger' : ($score >= 40 ? 'text-warning' : 'text-success');
                            @endphp

                            {{-- Menggunakan d-inline-block dengan lebar pasti agar posisi konten terkunci rapi di tengah --}}
                            <div class="d-inline-block" style="width: 45px; position: relative; text-align: left;">
                                {{-- Angka skor rata kiri di dalam kotak --}}
                                <span class="fw-bold fs-6 text-dark">{{ $score }}</span>

                                {{-- Ikon lingkaran dipaksa geser ke kanan secara absolut --}}
                                <i class="fas fa-circle {{ $dotClass }}" style="font-size: 0.55rem; position: absolute; right: 0; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Kolom Status (Rata Tengah) --}}
                        <td class="py-3 text-center">
                            @php $lastStatus = $report->reportStatuses?->last(); @endphp
                            @if($lastStatus?->status === 'pending')
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#F1F3F5;color:#495057;font-size:0.75rem;">Menunggu</span>
                            @elseif($lastStatus?->status === 'in_progress')
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#EEF2FF;color:#3B5BDB;font-size:0.75rem;">Diproses</span>
                            @elseif($lastStatus?->status === 'completed')
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#EBFBEE;color:#2F9E44;font-size:0.75rem;">Selesai</span>
                            @else
                            <span class="px-3 py-1 rounded-pill fw-semibold" style="background:#FFF0F0;color:#C92A2A;font-size:0.75rem;">Ditolak</span>
                            @endif
                        </td>

                        {{-- Kolom Aksi (Rata Tengah dengan justify-content-center) --}}
                        <td class="py-3 text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.report.show', $report->id) }}" class="btn btn-sm btn-light text-primary border-0 p-2 d-inline-flex align-items-center justify-content-center rounded-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail Laporan" style="width: 32px; height: 32px;"><i class="fas fa-eye fs-6"></i></a>
                                <a href="{{ route('admin.report.edit', $report->id) }}" class="btn btn-sm btn-light text-warning border-0 p-2 d-inline-flex align-items-center justify-content-center rounded-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Ubah Laporan" style="width: 32px; height: 32px;"><i class="fas fa-pen fs-6"></i></a>
                                <form action="{{ route('admin.report.destroy', $report->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger border-0 p-2 d-inline-flex align-items-center justify-content-center rounded-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Laporan" style="width: 32px; height: 32px;" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')"><i class="fas fa-trash fs-6"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection