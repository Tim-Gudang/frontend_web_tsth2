@extends('layouts.main')

@section('content')
<!-- Tampilkan pesan error -->
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<!-- Modal Tambah -->

<div id="modal_centered" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tambah Data</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('satuans.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Satuan:</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Satuan" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi:</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="null"></textarea>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="reset" class="btn btn-light">Batal</button>
                            <button type="submit" class="btn btn-primary ms-3">Simpan
                                <i class="ph-paper-plane-tilt ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Area -->
<button type="button" class="btn btn-primary btn-labeled btn-labeled-start mb-2" data-bs-toggle="modal"
    data-bs-target="#modal_centered">
    <span class="btn-labeled-icon bg-black bg-opacity-20">
        <i class="icon-database-add"></i>
    </span> Tambah
</button>

<!-- Table Satuan -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Table Satuan</h5>
    </div>
    <table class="table datatable-button-html5-basic">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Satuan</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($satuans as $key => $satuan)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $satuan['name'] }}</td>
                <td>{{ $satuan['description'] ?? '-' }}</td>
                <td><span class="badge bg-success bg-opacity-10 text-success">Active</span></td>
                <td>
                    <div class="d-inline-flex">
                        <!-- Show -->
                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_show_{{ $satuan['id'] }}">
                            <i class="ph-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a href="#" class="text-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_edit_{{ $satuan['id'] }}">
                            <i class="ph-pen"></i>
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('satuans.destroy', $satuan['id']) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus?')" class="d-inline me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger">
                                <i class="ph-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Edit per Item -->
@foreach ($satuans as $satuan)
<div id="modal_edit_{{ $satuan['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Edit Data</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('satuans.update', $satuan['id']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Satuan:</label>
                            <input type="text" name="name" value="{{ $satuan['name'] }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi:</label>
                            <textarea name="description" rows="3"
                                class="form-control">{{ $satuan['description'] }}</textarea>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary ms-3">Simpan Perubahan
                                <i class="ph-paper-plane-tilt ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Show per Item -->
@foreach ($satuans as $satuan)
<div id="modal_show_{{ $satuan['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Detail Satuan</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Satuan:</dt>
                        <dd class="col-sm-8">{{ $satuan['name'] }}</dd>

                        <dt class="col-sm-4">Deskripsi:</dt>
                        <dd class="col-sm-8">{{ $satuan['description'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                        </dd>
                    </dl>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection