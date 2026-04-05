<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Kantin Online</title>

    {{-- Pakai asset yang sama dengan layouts/app.blade.php kamu --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>

    {{-- Navbar sederhana tanpa info user --}}
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row default-layout-navbar">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="{{ route('pesan.index') }}">
                <i class="mdi mdi-food" style="font-size:24px; color:#4B49AC;"></i>
                <span style="font-size:18px; font-weight:bold; color:#4B49AC; margin-left:6px;">
                    Kantin Online
                </span>
            </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            {{-- Tombol login untuk vendor --}}
            {{-- <a href="{{ route('login') }}"
               class="btn btn-outline-primary btn-sm btn-rounded mr-3">
                <i class="mdi mdi-login"></i> Login Vendor
            </a> --}}
        </div>
    </nav>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper" style="padding-top:70px;">
            <div class="main-panel" style="width:100%;">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <footer class="footer">
                    <div class="d-flex justify-content-center">
                        <span class="text-muted">Kantin Online &copy; {{ date('Y') }}</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>

    @yield('scripts')
</body>
</html>