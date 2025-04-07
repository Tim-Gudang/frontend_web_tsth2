<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Aplikasi Inventory</title>

    <!-- Global stylesheets -->
    <link href="{{ asset('template/assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('template/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->
</head>

<body>
    <!-- Main navbar -->
    <div class="navbar navbar-dark navbar-static py-2">
        <div class="container-fluid">
            <div class="navbar-brand">
                <a href="{{ route('login') }}" class="d-inline-flex align-items-center">
                    <img src="{{ asset('template/assets/images/logo_icon.svg') }}" alt="">
                    <span class="d-none d-sm-inline-block h-16px ms-3">Inventory System</span>
                </a>
            </div>
        </div>
    </div>
    <!-- /main navbar -->

    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Inner content -->
            <div class="content-inner">
                <!-- Content area -->
                <div class="content d-flex justify-content-center align-items-center">
                    <!-- Login Form -->
                    <form class="login-form" action="{{ route('login') }}" method="POST" style="width: 350px;">
                        @csrf
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <img src="{{ asset('template/assets/images/logo_icon.svg') }}" class="h-48px" alt="">
                                    <h5 class="mb-0 mt-2">Login ke Sistem</h5>
                                    <span class="d-block text-muted">Masukkan email dan password Anda</span>
                                </div>

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

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="email" name="email" class="form-control" 
                                            value="{{ old('email') }}" 
                                            placeholder="Masukkan email.." required autofocus>
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-envelope text-muted"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="password" name="password" class="form-control" 
                                            placeholder="Masukkan password.." required>
                                        <div class="form-control-feedback-icon">
                                            <i class="ph-lock text-muted"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Ingat saya</label>
                                    </div>
                                    <a href="#">Lupa password?</a>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="ph-sign-in me-2"></i> Login
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- /login form -->
                </div>
                <!-- /content area -->
            </div>
            <!-- /inner content -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->

    <!-- Theme JS files -->
    <script src="{{ asset('template/assets/js/vendor/notifications/noty.min.js') }}"></script>
    <script src="{{ asset('template/assets/demo/pages/extra_noty.js') }}"></script>
    
    <script>
        // Menampilkan notifikasi dari session
        @if(session('success'))
            new Noty({
                text: '{{ session('success') }}',
                type: 'success',
                timeout: 3000
            }).show();
        @endif
        
        @if(session('error'))
            new Noty({
                text: '{{ session('error') }}',
                type: 'error',
                timeout: 3000
            }).show();
        @endif
    </script>
</body>
</html>