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
                    <h6 class="mb-0">Tambah Tipe Transaksi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaction-types.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Tipe Transaksi:</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Tipe Transaksi" required>
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
        <h5 class="mb-0">Table Tipe Transaksi</h5>
    </div>
    <table class="table datatable-button-html5-basic">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tipe Transaksi</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactionTypes as $key => $transactionType)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $transactionType->name }}</td>
                <td>{{ $transactionType->slug ?? '-' }}</td>
                <td>
                    <span class="badge {{ $transactionType->deleted_at ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">
                        {{ $transactionType->deleted_at ? 'Deleted' : 'Active' }}
                    </span>
                </td>
                <td>{{ $transactionType->created_at ?? '-' }}</td>
                <td>
                    <div class="d-inline-flex">
                        <!-- Show -->
                        <a href="#" class="text-info me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_show_{{ $transactionType->id }}">
                            <i class="ph-eye"></i>
                        </a>

                        <!-- Edit -->
                        <a href="#" class="text-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#modal_edit_{{ $transactionType->id }}">
                            <i class="ph-pen"></i>
                        </a>

                        <!-- Delete -->
                        @if (!$transactionType->deleted_at)
                        <form action="{{ route('transaction-types.destroy', $transactionType->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus?')" class="d-inline me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger">
                                <i class="ph-trash"></i>
                            </button>
                        </form>
                        @endif

                        <!-- Restore -->
                        @if ($transactionType->deleted_at)
                        <form action="{{ route('transaction-types.restore', $transactionType->id) }}" method="POST"
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
@foreach ($transactionTypes as $transactionType)
<div id="modal_edit_{{ $transactionType->id }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Edit Tipe Transaksi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('transaction-types.update', $transactionType->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Tipe Transaksi:</label>
                            <input type="text" name="name" value="{{ $transactionType->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug:</label>
                            <input type="text" name="slug" value="{{ $transactionType->slug }}" class="form-control">
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
@foreach ($transactionTypes as $transactionType)
<div id="modal_show_{{ $transactionType->id }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Detail Tipe Transaksi</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Tipe Transaksi:</dt>
                        <dd class="col-sm-8">{{ $transactionType->name }}</dd>

                        <dt class="col-sm-4">Slug:</dt>
                        <dd class="col-sm-8">{{ $transactionType->slug ?? '-' }}</dd>

                        

                        <dt class="col-sm-4">Dibuat:</dt>
                        <dd class="col-sm-8">{{ $transactionType->created_at ?? '-' }}</dd>

                        <dt class="col-sm-4">Diperbarui:</dt>
                        <dd class="col-sm-8">{{ $transactionType->updated_at ?? '-' }}</dd>

                        <dt class="col-sm-4">Dihapus:</dt>
                        <dd class="col-sm-8">{{ $transactionType->deleted_at ?? '-' }}</dd>
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