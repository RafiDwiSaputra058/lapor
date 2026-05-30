@extends('layouts.no-nav')

@section('title', 'Ambil Foto')

@section('content')
<div class="d-flex flex-column justify-content-center align-items-center">
    <video autoplay="true" id="video-webcam">
        Browsermu tidak mendukung bro, upgrade donk!
    </video>

    <div style="position:fixed; bottom:200px; left:50%; transform:translateX(-50%); z-index:2;">
        <button class="btn btn-primary btn-snap" onclick="takeSnapshot()">
            <i class="fas fa-camera"></i>
        </button>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('assets/app/js/take.js') }}"></script>
@endsection