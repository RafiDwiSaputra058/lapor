@extends('layouts.admin')

@section('title', 'Detail Laporan')

@section('content')
<!-- Page Heading -->
<a href="{{ route('admin.report.index') }}" class="btn btn-danger mb-3">Kembali</a>


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Laporan</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <td>Kode Laporan</td>
                <td>{{ $report->code }}</td>
            </tr>
            <tr>
                <td>Pelapor</td>
                <td>{{ $report->resident->user->email }} - {{ $report->resident->user->name }}</td>
            </tr>
            <tr>
                <td>Kategori Laporan</td>
                <td>{{ $report->reportCategory->name }}</td>
            </tr>
            <tr>
                <td>Judul Laporan</td>
                <td>{{ $report->title }}</td>
            </tr>
            <tr>
                <td>Deskripsi Laporan</td>
                <td>{{ $report->description }}</td>
            </tr>
            <tr>
                <td>Bukti Laporan</td>
                <td>
                    <img src="{{ asset('storage/'. $report->image) }}" alt="image" width="200">
                </td>
            </tr>
            <tr>
                <td>Latitude</td>
                <td>{{ $report->latitude }}</td>
            </tr>
            <tr>
                <td>Longitude</td>
                <td>{{ $report->longitude }}</td>
            </tr>
            <tr>
                <td>Map View</td>
                <td>
                    <div id="map"
                        style="height: 400px;"
                        data-lat="{{ $report->latitude }}"
                        data-lng="{{ $report->longitude }}"
                        data-address="{{ $report->address }}">
                    </div>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>{{ $report->address }}</td>
            </tr>

        </table>
    </div>
</div>



<div class="card shadow mb-5">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Status Laporan</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('admin.report-status.create', $report->id) }}" class="btn btn-primary mb-3">Tambah Status</a>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Deskripsi</th>

                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report->reportStatuses as $status)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($status->image)
                            <img src="{{ asset('storage/'. $status->image) }}" alt="image" width="100">
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ $status->status }}</td>
                        <td>{{ $status->description }}</td>




                        <td>
                            <a href="{{ route('admin.report-status.edit', $status->id) }}" class="btn btn-warning">Edit</a>



                            <form action="{{ route('admin.report-status.destroy', $status->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var mapEl = document.getElementById('map');
    var lat = parseFloat(mapEl.dataset.lat);
    var lng = parseFloat(mapEl.dataset.lng);
    var address = mapEl.dataset.address;

    var myapp = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
        maxZoom: 19,
    }).addTo(myapp);

    var marker = L.marker([lat, lng]).addTo(myapp);
    marker.bindPopup("<b>Lokasi Laporan</b><br>berada di " + address).openPopup();
</script>
@endsection