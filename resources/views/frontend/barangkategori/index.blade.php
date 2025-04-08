@extends('layouts.main')

@section('content')
<!-- Error and success messages -->
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

<!-- Create Modal -->
<div id="modal_centered" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tambah Kategori Barang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang-categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori:</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug:</label>
                            <input type="text" name="slug" class="form-control" placeholder="Slug (opsional)">
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

<!-- Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Kategori Barang</h5>
    </div>
    <table class="table datatable-button-html5-basic">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($barangCategories as $key => $category)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $category['name'] }}</td>
                <td>{{ $category['slug'] ?? '-' }}</td>
                <td>
                    <span class="badge {{ $category['deleted_at'] ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">
                        {{ $category['deleted_at'] ? 'Deleted' : 'Active' }}
                    </span>
                </td>
                <td>{{ $category['created_at'] ?? '-' }}</td>
                <td>
                    <div class="d-inline-flex">
                        <!-- Show -->
                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_show_{{ $category['id'] }}">
                            <i class="ph-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a href="#" class="text-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_edit_{{ $category['id'] }}">
                            <i class="ph-pen"></i>
                        </a>

                        <!-- Delete -->
                        @if (!$category['deleted_at'])
                        <form action="{{ route('barang-categories.destroy', $category['id']) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus?')" class="d-inline me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger">
                                <i class="ph-trash"></i>
                            </button>
                        </form>
                        @endif

                        <!-- Restore -->
                        @if ($category['deleted_at'])
                        <form action="{{ route('barang-categories.restore', $category['id']) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin memulihkan?')" class="d-inline me-2">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 text-success">
                                <i class="ph-arrow-counter-clockwise"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Edit Modals -->
@foreach ($barangCategories as $category)
<div id="modal_edit_{{ $category['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Edit Kategori Barang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang-categories.update', $category['id']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori:</label>
                            <input type="text" name="name" value="{{ $category['name'] }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug:</label>
                            <input type="text" name="slug" value="{{ $category['slug'] }}" class="form-control">
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

<!-- Show Modals -->
@foreach ($barangCategories as $category)
<div id="modal_show_{{ $category['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Detail Kategori Barang</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Kategori:</dt>
                        <dd class="col-sm-8">{{ $category['name'] }}</dd>

                        <dt class="col-sm-4">Slug:</dt>
                        <dd class="col-sm-8">{{ $category['slug'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ $category['deleted_at'] ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">
                                {{ $category['deleted_at'] ? 'Deleted' : 'Active' }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Dibuat:</dt>
                        <dd class="col-sm-8">{{ $category['created_at'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Diperbarui:</dt>
                        <dd class="col-sm-8">{{ $category['updated_at'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Dihapus:</dt>
                        <dd class="col-sm-8">{{ $category['deleted_at'] ?? '-' }}</dd>
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