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
<div class="row">
    <!-- Quick stats boxes -->
    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">125</h3>
                </div>
                <div>
                    Barang
                </div>
            </div>
        </div>
        <!-- /members online -->
    </div>

    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">8</h3>
                </div>
                <div>
                    Jenis Barang
                </div>
            </div>
        </div>
        <!-- /members online -->
    </div>

    <div class="col-lg-3">
        <!-- Current server load -->
        <div class="card bg-pink text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h3 class="mb-10">42</h3>
                    <div class="dropdown d-inline-flex ms-auto">
                        <a href="#" class="text-white d-inline-flex align-items-center dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <i class="ph-gear"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <i class="ph-chart-line me-2"></i>
                                Statistics
                            </a>
                        </div>
                    </div>
                </div>
                <div>
                    Transaksi
                </div>
            </div>
            <div class="rounded-bottom overflow-hidden" id="server-load"></div>
        </div>
        <!-- /current server load -->
    </div>

    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">5</h3>
                </div>
                <div>
                    Satuan
                </div>
            </div>
        </div>
        <!-- /members online -->
    </div>

    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">15</h3>
                </div>
                <div>
                    User
                </div>
            </div>
        </div>
        <!-- /members online -->
    </div>

    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">3</h3>
                </div>
                <div>
                    Gudang
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">4</h3>
                </div>
                <div>
                    Status
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <!-- Members online -->
        <div class="card bg-teal text-white">
            <div class="card-body">
                <div class="d-flex">
                    <h3 class="mb-10">6</h3>
                </div>
                <div>
                    Jenis Transaksi
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="ph-calendar-blank ph-2x text-muted"></i>
                        <p class="mt-2">Grafik transaksi akan muncul di sini</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Daily financials -->
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="mb-0">Daily financials</h5>
                    <div class="ms-auto">
                        <label class="form-check form-switch form-check-reverse">
                            <input type="checkbox" class="form-check-input" id="realtime" checked>
                            <span class="form-check-label">Realtime</span>
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-3">
                        <i class="ph-chart-line ph-2x text-muted"></i>
                        <p class="mt-2">Grafik keuangan akan muncul di sini</p>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="bg-pink bg-opacity-10 text-pink lh-1 rounded-pill p-2">
                                <i class="ph-chart-line"></i>
                            </div>
                        </div>
                        <div class="flex-fill">
                            Stats for today: <span class="fw-semibold">24</span> orders, $1250 revenue
                            <div class="text-muted fs-sm">2 hours ago</div>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="bg-success bg-opacity-10 text-success lh-1 rounded-pill p-2">
                                <i class="ph-check"></i>
                            </div>
                        </div>
                        <div class="flex-fill">
                            Invoices <a href="#">#001</a> and <a href="#">#002</a> have been paid
                            <div class="text-muted fs-sm">Today, 10:30</div>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <div class="bg-primary bg-opacity-10 text-primary lh-1 rounded-pill p-2">
                                <i class="ph-users"></i>
                            </div>
                        </div>
                        <div class="flex-fill">
                            5 new users registered today
                            <div class="text-muted fs-sm">Today, 08:15</div>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="me-3">
                            <div class="bg-warning bg-opacity-10 text-warning lh-1 rounded-pill p-2">
                                <i class="ph-arrow-counter-clockwise"></i>
                            </div>
                        </div>
                        <div class="flex-fill">
                            New inventory items added
                            <div class="text-muted fs-sm">Today, 09:45</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /daily financials -->
        </div>
    </div>
    <!-- /dashboard content -->
</div>
@endsection