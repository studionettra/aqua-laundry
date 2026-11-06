<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Dashboard - Aqua Laundry Dashboard</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/compiled/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dist/assets/extensions/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="shortcut icon" href="{{ asset('template/dist/assets/static/images/logo/favicon.svg') }}" type="image/x-icon">

    <style>
        /* ========================================================
           RESPONSIVE SYSTEM: DESIGN TASTE FRONTEND
           ======================================================== */
        
        /* Penyesuaian Ruang Padding #main di Berbagai Layar */
        @media screen and (max-width: 1199.98px) {
            #main {
                margin-left: 0 !important;
                padding: 1.25rem 1.15rem !important;
            }
            #sidebar:not(.active) .sidebar-wrapper {
                left: -350px !important;
                transform: translateX(-110%) !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }
            .sidebar-backdrop {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                background: rgba(15, 23, 42, 0.55) !important;
                backdrop-filter: blur(4px) !important;
                -webkit-backdrop-filter: blur(4px) !important;
                z-index: 9998 !important;
            }
        }

        @media screen and (max-width: 575.98px) {
            #main {
                padding: 1rem 1rem !important;
            }
            .page-heading {
                margin-bottom: 1.25rem !important;
            }
            .page-heading h3 {
                font-size: 1.35rem !important;
            }
            .card-body {
                padding: 1rem !important;
            }
            /* Stats Icon scaling di mobile */
            .stats-icon {
                width: 2.6rem !important;
                height: 2.6rem !important;
            }
            .stats-icon i {
                font-size: 1.35rem !important;
            }
        }

        /* Mobile Topbar Navigation */
        .mobile-header-bar {
            background: #ffffff;
            border-radius: 12px;
            padding: 8px 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .btn-burger-touch {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: #f1f5f9;
            color: #1e293b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-burger-touch:hover, .btn-burger-touch:active {
            background: #e2e8f0;
            color: #095CDE;
        }

        /* Smooth momentum touch-scrolling untuk semua tabel di mobile */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .table-responsive table th,
        .table-responsive table td {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')
    <div id="app">
        <!-- Sidebar Navigation -->
        @include('layouts.inc.sidebar')

        <!-- Main Content Area -->
        <div id="main">
            <!-- Header Navigasi Mobile & Tablet -->
            <header class="mb-3 mb-md-4">
                <div class="mobile-header-bar d-flex align-items-center justify-content-between d-xl-none">
                    <!-- Tombol Toggle Sidebar & Brand Logo -->
                    <div class="d-flex align-items-center gap-2">
                        <a href="#" class="burger-btn btn-burger-touch" aria-label="Buka Menu Sidebar">
                            <i class="bi bi-justify fs-5"></i>
                        </a>
                        <div class="ms-1 lh-1">
                            <span class="fw-bold text-primary" style="font-size: 1.05rem; letter-spacing: -0.02em;">Aqua Laundry</span>
                            <small class="d-block text-muted" style="font-size: 0.68rem;">Management System</small>
                        </div>
                    </div>

                    <!-- Profil Ringkas di Bar Mobile -->
                    @auth
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light-primary text-primary px-2 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                {{ Auth::user()->level->level_name ?? '' }}
                            </span>
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" 
                                 style="width: 32px; height: 32px; font-size: 0.85rem; background: linear-gradient(135deg, #095CDE 0%, #53C3F3 100%) !important;">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Judul Halaman -->
            <div class="page-heading">
                <h3 class="fw-bold text-dark">@yield('title')</h3>
            </div>

            <!-- Konten Halaman Dinamis -->
            <div class="page-content">
                <div class="row">
                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>
            </div>

            <!-- Footer Aplikasi -->
            <footer class="mt-5 pt-3 border-top text-muted small">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div>
                        <p class="mb-0">&copy; {{ date('Y') }} <strong>Aqua Laundry</strong>. All rights reserved.</p>
                    </div>
                    <div>
                        <p class="mb-0">Sistem Manajemen Kasir & Operasional Laundry</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Script Vendor & Theme -->
    <script src="{{ asset('template/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/dist/assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('template/dist/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('template/dist/assets/static/js/pages/dashboard.js') }}"></script>
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])
</body>

</html>
