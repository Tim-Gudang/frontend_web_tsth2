@extends('layouts.main')

@section('content')
    <!-- Centered Modal -->
    <div id="modal_centered" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Tambah Data</h6>
                    </div>
                    <div class="card-body">
                        <form action="#">
                            <div class="mb-3">
                                <label class="form-label">Nama Satuan:</label>
                                <input type="text" class="form-control" placeholder="Nama Satuan">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi:</label>
                                <textarea rows="3" class="form-control" placeholder="null"></textarea>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="reset" class="btn btn-light">Batal</button>
                                <button type="submit" class="btn btn-primary ms-3">Simpan <i
                                        class="ph-paper-plane-tilt ms-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content">
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
                    @foreach ($data['data'] as $key => $satuan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $satuan['name'] }}</td>
                            <td>{{ $satuan['description'] ?? '-' }}</td>
                            <td><span class="badge bg-success bg-opacity-10 text-success">Active</span></td>
                            <td>
                                <div class="d-inline-flex">
                                    <a href="#" class="text-primary"><i class="ph-pen"></i></a>
                                    <a href="#" class="text-danger mx-2"><i class="ph-trash"></i></a>
                                    <a href="#" class="text-teal"><i class="ph-gear"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
