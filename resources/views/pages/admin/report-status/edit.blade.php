@extends ('layouts.admin')

@section('title', 'Edit Data Status Laporan')

@section('content')
<!-- Page Heading -->
<a href="{{ route('admin.report.show', $status->report->id) }}" class="btn btn-danger mb-3">Kembali</a>


<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Data</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.report-status.update', $status->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="report_id" value="{{ $status->report->id }}">
            <div class="form-group">
                <label for="image">Bukti</label>
                @if ($status->image)
                <img src="{{ asset('storage/' . $status->image) }}" alt="Bukti" width="200">
                <br>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                @error('image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>


            <div class="form-group">
                <label for="status">Status</label>

                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="pending" @if(old('status', $status->status)=='pending' ) selected @endif>Pending</option>
                    <option value="in_progress" @if(old('status', $status->status)=='in_progress' ) selected @endif>In Progress</option>
                    <option value="completed" @if(old('status', $status->status)=='completed' ) selected @endif>Completed</option>
                    <option value="rejected" @if(old('status', $status->status)=='rejected' ) selected @endif>Rejected</option>
                </select>


                @error('status')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>


            <div class="form-group">

                <label for="description">Deskripsi</label>
                <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $status->description) }}" rows="5">{{ old('description', $status->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection