@extends('layouts.main')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Daftar Gudang</h2>
            <a href="{{ route('gudangs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Gudang
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Gudang</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gudangs as $gudang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gudang['name'] }}</td>
                        <td>{{ $gudang['description'] ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($gudang['created_at'])->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('gudangs.edit', $gudang['id']) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('gudangs.destroy', $gudang['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Hapus gudang ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
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