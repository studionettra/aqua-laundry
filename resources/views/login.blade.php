<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aqua Laundry Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('template/dist/assets/static/images/logo/favicon.svg') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url("{{ asset('template/dist/assets/static/images/bg/4853433.png') }}") no-repeat center center fixed;
            background-size: cover;
            padding: 20px 10px;
        }

        .login-card {
            max-width: 440px;
            width: 100%;
            margin: auto;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        @media (max-width: 420px) {
            .login-card {
                padding: 1.25rem !important;
                border-radius: 14px !important;
            }
        }

        .btn-demo {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            text-align: left;
            position: relative;
            overflow: hidden;
        }

        .btn-demo:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.08);
        }

        .btn-demo.demo-admin:hover {
            border-color: #3b82f6;
            background: #f0f7ff;
        }

        .btn-demo.demo-kasir:hover {
            border-color: #10b981;
            background: #ecfdf5;
        }

        .btn-demo.demo-pimpinan:hover {
            border-color: #8b5cf6;
            background: #f5f3ff;
        }

        .divider-demo {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 22px 0 16px;
        }

        .divider-demo::before,
        .divider-demo::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider-demo span {
            padding: 0 12px;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="card login-card shadow-lg p-4">
        <!-- Logo Brand -->
        <div class="text-center mb-2">
            <a href="/">
                <img src="{{ asset('aquaLaundry_logo.png') }}" 
                     style="width: 240px; height: auto; max-width: 100%; margin-bottom: -25px; margin-top: -10px;" 
                     alt="Aqua Laundry Logo">
            </a>
            <p class="text-muted small mt-2 mb-0">Sistem Manajemen & Operasional Kasir Laundry</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Form Login Manual -->
        <form id="form-login" action="{{ route('action-login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Email Pengguna</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="input-email" name="email" class="form-control" value="{{ old('email') }}" required
                        autofocus placeholder="contoh: admin@gmail.com">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                    <input type="password" id="input-password" name="password" class="form-control" required placeholder="••••••••">
                </div>
            </div>
            <button type="submit" id="btn-submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Dashboard
            </button>
        </form>

        <!-- Divider Showcase Mode -->
        <div class="divider-demo">
            <span>Akses Cepat Demo (1-Klik)</span>
        </div>

        <!-- Tombol Akses Cepat untuk Calon Klien -->
        <div class="d-flex flex-column gap-2">
            <!-- 1. Role: Administrator -->
            <button type="button" class="btn-demo demo-admin p-2 rounded-3 w-100 d-flex align-items-center justify-content-between"
                    onclick="quickLogin('admin@gmail.com', '12345678', 'Administrator')">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary text-white p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-shield-check fs-6"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-dark lh-1" style="font-size: 0.85rem;">Login sbg Administrator</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Full Akses Master Data, Layanan & User</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-primary fs-6 pe-1"></i>
            </button>

            <!-- 2. Role: Operator Kasir -->
            <button type="button" class="btn-demo demo-kasir p-2 rounded-3 w-100 d-flex align-items-center justify-content-between"
                    onclick="quickLogin('operator@gmail.com', '12345678', 'Operator Kasir')">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success text-white p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-cart3 fs-6"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-dark lh-1" style="font-size: 0.85rem;">Login sbg Operator Kasir</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Input Order Cucian, Pelanggan & Pengambilan</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right text-success fs-6 pe-1"></i>
            </button>

            <!-- 3. Role: Pimpinan / Owner -->
            <button type="button" class="btn-demo demo-pimpinan p-2 rounded-3 w-100 d-flex align-items-center justify-content-between"
                    onclick="quickLogin('pimpinan@gmail.com', '12345678', 'Pimpinan')">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge text-white p-2 rounded-circle d-flex align-items-center justify-content-center" 
                          style="background-color: #8b5cf6; width: 32px; height: 32px;">
                        <i class="bi bi-graph-up fs-6"></i>
                    </span>
                    <div>
                        <div class="fw-bold text-dark lh-1" style="font-size: 0.85rem;">Login sbg Pimpinan (Owner)</div>
                        <small class="text-muted" style="font-size: 0.72rem;">Grafik Omset, Laporan Keuangan & Pengeluaran</small>
                    </div>
                </div>
                <i class="bi bi-chevron-right fs-6 pe-1" style="color: #8b5cf6;"></i>
            </button>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted" style="font-size: 0.7rem;">
                <i class="bi bi-info-circle me-1"></i> Klik role di atas untuk mencoba fitur tanpa perlu mengetik sandi.
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('sweetalert::alert', ['cdn' => 'https://cdn.jsdelivr.net/npm/sweetalert2@9'])

    <script>
        /**
         * Mengisi form otomatis dan langsung mengirimkan login request
         */
        function quickLogin(email, password, roleName) {
            const emailInput = document.getElementById('input-email');
            const passwordInput = document.getElementById('input-password');
            const submitBtn = document.getElementById('btn-submit');
            const loginForm = document.getElementById('form-login');

            if (emailInput && passwordInput && loginForm && submitBtn) {
                emailInput.value = email;
                passwordInput.value = password;

                // Tampilkan feedback visual langsung kepada calon klien
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Menghubungkan sbg ${roleName}...
                `;

                // Kirim form langsung
                setTimeout(() => {
                    loginForm.submit();
                }, 150);
            }
        }
    </script>
</body>

</html>
