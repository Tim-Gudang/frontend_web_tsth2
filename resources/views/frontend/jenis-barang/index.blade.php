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
                    <h6 class="mb-0">Tambah Jenis Barang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis-barangs.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Jenis Barang:</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Jenis Barang"
                                required>
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

<!-- Table Jenis Barang -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Table Jenis Barang</h5>
    </div>
    <table class="table datatable-button-html5-basic">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Jenis</th>
                <th>Slug</th>
                <th>Deskripsi</th>
                <th>Pembuat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jenisBarangs['data'] ?? [] as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item['name'] ?? 'N/A' }}</td>
                <td>{{ $item['slug'] ?? 'N/A' }}</td>
                <td>{{ $item['description'] ?? '-' }}</td>
                <td>{{ $item['user']['name'] ?? '-' }}</td>
                <td>
                    <div class="d-inline-flex">
                        <!-- Show -->
                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_show_{{ $item['id'] }}">
                            <i class="ph-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a href="#" class="text-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_edit_{{ $item['id'] }}">
                            <i class="ph-pen"></i>
                        </a>

                        <!-- Delete -->
                        <form action="{{ route('jenis-barangs.destroy', $item['id']) }}" method="POST"
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
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data jenis barang</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Edit per Item -->
@foreach ($jenisBarangs['data'] ?? [] as $item)
<div id="modal_edit_{{ $item['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Edit Jenis Barang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis-barangs.update', $item['id']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Jenis Barang:</label>
                            <input type="text" name="name" value="{{ $item['name'] ?? '' }}" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi:</label>
                            <textarea name="description" rows="3"
                                class="form-control">{{ $item['description'] ?? '' }}</textarea>
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
@foreach ($jenisBarangs['data'] ?? [] as $item)
<div id="modal_show_{{ $item['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Detail Jenis Barang</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Jenis:</dt>
                        <dd class="col-sm-8">{{ $item['name'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Slug:</dt>
                        <dd class="col-sm-8">{{ $item['slug'] ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Deskripsi:</dt>
                        <dd class="col-sm-8">{{ $item['description'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Pembuat:</dt>
                        <dd class="col-sm-8">{{ $item['user']['name'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Dibuat Pada:</dt>
                        <dd class="col-sm-8">{{ $item['created_at'] ?
                            \Carbon\Carbon::parse($item['created_at'])->translatedFormat('d F Y H:i') : '-' }}</dd>

                        <dt class="col-sm-4">Diperbarui Pada:</dt>
                        <dd class="col-sm-8">{{ $item['updated_at'] ?
                            \Carbon\Carbon::parse($item['updated_at'])->translatedFormat('d F Y H:i') : '-' }}</dd>
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