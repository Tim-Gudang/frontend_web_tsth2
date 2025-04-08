@extends('layouts.main')

@section('content')
<!-- Modal Create -->
<div id="modal_create" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tambah Gudang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('gudangs.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Gudang:</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Gudang" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi:</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Deskripsi gudang"></textarea>
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
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Gudang</h5>
        <button type="button" class="btn btn-primary btn-labeled btn-labeled-start" data-bs-toggle="modal"
            data-bs-target="#modal_create">
            <span class="btn-labeled-icon bg-black bg-opacity-20">
                <i class="ph-plus"></i>
            </span>
            Tambah Gudang
        </button>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table datatable-basic">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Gudang</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Dibuat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gudangs as $key => $gudang)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $gudang['name'] }}</td>
                    <td>{{ $gudang['description'] ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($gudang['created_at'])->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        <div class="d-inline-flex">
                            <!-- Show -->
                            <a href="#" class="text-info me-2" data-bs-toggle="modal"
                                data-bs-target="#modal_show_{{ $gudang['id'] }}">
                                <i class="ph-eye"></i>
                            </a>

                            <!-- Edit -->
                            <a href="#" class="text-primary me-2" data-bs-toggle="modal"
                                data-bs-target="#modal_edit_{{ $gudang['id'] }}">
                                <i class="ph-pen"></i>
                            </a>

                            <!-- Delete -->
                            <form action="{{ route('gudangs.destroy', $gudang['id']) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus gudang ini?')" class="d-inline">
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
</div>

<!-- Modal Edit per Gudang -->
@foreach ($gudangs as $gudang)
<div id="modal_edit_{{ $gudang['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Edit Gudang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('gudangs.update', $gudang['id']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Gudang:</label>
                            <input type="text" name="name" value="{{ $gudang['name'] }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi:</label>
                            <textarea name="description" rows="3" class="form-control">{{ $gudang['description'] ?? '' }}</textarea>
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

<!-- Modal Show per Gudang -->
@foreach ($gudangs as $gudang)
<div id="modal_show_{{ $gudang['id'] }}" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Detail Gudang</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Nama Gudang:</dt>
                        <dd class="col-sm-8">{{ $gudang['name'] }}</dd>

                        <dt class="col-sm-4">Deskripsi:</dt>
                        <dd class="col-sm-8">{{ $gudang['description'] ?? '-' }}</dd>

                        <dt class="col-sm-4">Dibuat pada:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($gudang['created_at'])->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Diupdate pada:</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($gudang['updated_at'])->format('d/m/Y H:i') }}</dd>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Datatable
        $('.datatable-basic').DataTable({
            autoWidth: false,
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Ketik untuk mencari...',
                lengthMenu: '<span class="me-3">Tampilkan:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });
    });
</script>
@endpush