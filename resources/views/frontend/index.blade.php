@extends('layouts.main')

@section('content')
    <!-- Centered Modal -->
    <div id="transactionTypeModal" class="modal fade" tabindex="-1">
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
                                <input type="text" class="form-control" name="name" placeholder="Contoh: Pembelian, Penjualan" required>
                            </div>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary ms-3">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content">
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
            data-bs-target="#transactionTypeModal">
            <i class="icon-database-add me-2"></i>Tambah Tipe Transaksi
        </button>

        <!-- Table Tipe Transaksi -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Tipe Transaksi</h5>
            </div>
            <div class="card-body">
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tipe</th>
                            <th>Dibuat Pada</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactionTypes as $key => $type)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $type['name'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($type['created_at'])->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex">
                                        <!-- Edit Button -->
                                        <a href="{{ route('transaction-types.edit', $type['id']) }}" 
                                           class="btn btn-sm btn-icon btn-primary me-2">
                                            <i class="ph-pen"></i>
                                        </a>
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('transaction-types.destroy', $type['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-danger" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
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
    </div>
@endsection