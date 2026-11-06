@php
    // Ambil info user yang sedang login
    $levelName = Auth::user()->level->level_name ?? '';
    $userName = Auth::user()->name ?? 'User';
    $userInitial = strtoupper(substr($userName, 0, 1));
    $badgeStyle = match ($levelName) {
        'Administrator' => 'background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;',
        'Operator' => 'background-color: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0;',
        'Pimpinan' => 'background-color: #f3e8ff; color: #9333ea; border: 1px solid #e9d5ff;',
        default => 'background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;',
    };
@endphp

<div id="sidebar">
    <div class="sidebar-wrapper active d-flex flex-column justify-content-between">
        
        <!-- Header: Logo & Mobile Toggler (Fixed at top) -->
        <div class="sidebar-header flex-shrink-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo" style="margin-bottom: -20px; margin-top: -15px;">
                    <a href="{{ url('dashboard') }}">
                        <img src="{{ asset('aquaLaundry_logo.png') }}"
                             style="width: 230px; height: auto; max-width: 100%; margin-bottom: -15px;" 
                             alt="Aqua Laundry Logo">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-flex align-items-center justify-content-center rounded-circle" 
                       style="width: 36px; height: 36px; background-color: #f1f5f9; color: #475569; text-decoration: none;" 
                       aria-label="Tutup Menu">
                        <i class="bi bi-x fs-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Body: Scrollable Menu -->
        <div class="sidebar-menu flex-grow-1">
            <ul class="menu" style="margin-top: 1rem;">
                <li class="sidebar-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <a href="{{ url('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Menu Master Data untuk Administrator -->
                @if ($levelName === 'Administrator')
                    <li class="sidebar-item {{ request()->routeIs('level.*', 'user.*', 'service.*', 'tax.*') ? 'active' : '' }}">
                        <div class='sidebar-link' style="cursor: default;">
                            <i class="bi bi-collection-fill"></i>
                            <span>Master Data</span>
                        </div>
                        <ul class="submenu" style="display: block; max-height: none; overflow: visible;">
                            <li class="submenu-item {{ request()->routeIs('level.*') ? 'active' : '' }}">
                                <a href="{{ route('level.index') }}"><i class="bi bi-layers-half me-2"></i>Level</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                <a href="{{ route('user.index') }}"><i class="bi bi-person-fill me-2"></i>User</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('service.*') ? 'active' : '' }}">
                                <a href="{{ route('service.index') }}"><i class="bi bi-ui-checks me-2"></i>Jenis Layanan</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('tax.*') ? 'active' : '' }}">
                                <a href="{{ route('tax.index') }}"><i class="bi bi-percent me-2"></i>Pajak</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Menu Transaksi untuk Administrator dan Operator -->
                @if (in_array($levelName, ['Administrator', 'Operator']))
                    <li class="sidebar-item {{ request()->routeIs('customer.*', 'transaction.*', 'pickup.*', 'expense.*') ? 'active' : '' }}">
                        <div class='sidebar-link' style="cursor: default;">
                            <i class="bi bi-basket-fill"></i>
                            <span>Transaksi</span>
                        </div>
                        <ul class="submenu" style="display: block; max-height: none; overflow: visible;">
                            <li class="submenu-item {{ request()->routeIs('customer.*') ? 'active' : '' }}">
                                <a href="{{ route('customer.index') }}"><i class="bi bi-people-fill me-2"></i>Customer</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('transaction.*') ? 'active' : '' }}">
                                <a href="{{ route('transaction.index') }}"><i class="bi bi-cart-plus-fill me-2"></i>Order</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('pickup.*') ? 'active' : '' }}">
                                <a href="{{ route('pickup.index') }}"><i class="bi bi-bag-check-fill me-2"></i>Pengambilan</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('expense.*') ? 'active' : '' }}">
                                <a href="{{ route('expense.index') }}"><i class="bi bi-cash-stack me-2"></i>Pengeluaran</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Menu Laporan untuk Administrator dan Pimpinan -->
                @if (in_array($levelName, ['Administrator', 'Pimpinan']))
                    <li class="sidebar-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
                        <div class='sidebar-link' style="cursor: default;">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                            <span>Laporan</span>
                        </div>
                        <ul class="submenu" style="display: block; max-height: none; overflow: visible;">
                            <li class="submenu-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
                                <a href="{{ route('report.index') }}"><i class="bi bi-file-earmark-text-fill me-2"></i>Detail Laporan</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        
        <!-- Footer: User Profile & Tombol Keluar (Anti-Slop / Modern SaaS Design) -->
        <div class="sidebar-footer flex-shrink-0 p-3 bg-white border-top">
            @auth
                <div class="user-profile-card p-2 rounded-3 border" style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                        <div class="d-flex align-items-center text-truncate" style="gap: 10px; max-width: 100%;">
                            <!-- Avatar dengan initial & online indicator -->
                            <div class="position-relative flex-shrink-0">
                                <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                     style="width: 38px; height: 38px; background: linear-gradient(135deg, #095CDE 0%, #53C3F3 100%); font-size: 0.95rem;">
                                    {{ $userInitial }}
                                </div>
                                <span class="position-absolute bottom-0 end-0 rounded-circle border border-2 border-white" 
                                      style="width: 11px; height: 11px; background-color: #10b981;" 
                                      title="Online"></span>
                            </div>

                            <!-- Detail User -->
                            <div class="user-info lh-sm text-truncate" style="min-width: 0;">
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.885rem;" title="{{ $userName }}">
                                    {{ $userName }}
                                </div>
                                <span class="badge mt-1" style="{{ $badgeStyle }} font-size: 0.68rem; font-weight: 600; padding: 2px 8px; border-radius: 9999px; letter-spacing: 0.3px;">
                                    {{ $levelName }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Keluar dengan Quiet Micro-Interaction -->
                    <form id="sidebar-logout-form" action="{{ route('action-logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="button" id="btn-sidebar-logout" class="btn-logout-custom w-100 d-flex align-items-center justify-content-center gap-2" 
                                onclick="openLogoutModal()" title="Keluar dari akun Anda">
                            <i class="bi bi-box-arrow-right icon-logout"></i>
                            <span>Keluar Akun</span>
                        </button>
                    </form>
                </div>
            @endauth
        </div>

    </div>
</div>

<!-- Modal Konfirmasi Keluar Bespoke: Anti-Slop, Custom Micro-Motion & Blur Backdrop -->
<div id="logout-modal-backdrop" class="logout-modal-backdrop">
    <div class="logout-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
        <!-- Tombol Tutup Silang di Sudut Kanan Atas -->
        <button type="button" class="logout-modal-close" onclick="closeLogoutModal()" aria-label="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>

        <!-- Icon Badge Keluar dengan Aksen Merah Halus -->
        <div class="logout-icon-wrapper">
            <div class="logout-icon-badge">
                <i class="bi bi-box-arrow-right"></i>
            </div>
        </div>

        <!-- Judul & Deskripsi Konfirmasi -->
        <div class="text-center mt-3 mb-4">
            <h5 id="logout-modal-title" class="fw-bold text-dark mb-2" style="font-size: 1.15rem; letter-spacing: -0.01em;">
                Konfirmasi Keluar
            </h5>
            <p class="text-muted small mb-0 px-1" style="line-height: 1.5; font-size: 0.86rem;">
                Apakah Anda yakin ingin mengakhiri sesi kerja ini? Pastikan seluruh pencatatan transaksi hari ini telah selesai.
            </p>
        </div>

        <!-- Tombol Aksi (Batal & Konfirmasi) -->
        <div class="d-flex gap-2">
            <button type="button" id="btn-cancel-logout" class="btn btn-modal-cancel w-50" onclick="closeLogoutModal()">
                Batal
            </button>
            <button type="button" id="btn-confirm-logout" class="btn btn-modal-confirm w-50 d-flex align-items-center justify-content-center gap-2" onclick="submitLogoutForm()">
                <i class="bi bi-box-arrow-right"></i>
                <span>Ya, Keluar</span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Mengunci wrapper agar pas 100dvh (dynamic viewport height) di mobile & desktop */
    #sidebar .sidebar-wrapper {
        height: 100vh;
        height: 100dvh !important;
        max-height: 100dvh !important;
        overflow: hidden !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), left 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    /* Penanganan Mode Mobile & Tablet (< 1200px) */
    @media screen and (max-width: 1199.98px) {
        /* Saat tertutup: Wajib 100% tersembunyi tanpa menyisakan 1 piksel pun */
        #sidebar:not(.active) .sidebar-wrapper {
            left: -350px !important;
            transform: translateX(-110%) !important;
            visibility: hidden !important;
            pointer-events: none !important;
            box-shadow: none !important;
        }

        /* Saat dibuka: Tampil anggun di sisi kiri */
        #sidebar.active .sidebar-wrapper {
            left: 0 !important;
            transform: translateX(0) !important;
            visibility: visible !important;
            pointer-events: auto !important;
            width: 285px !important;
            max-width: 85vw !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
            z-index: 99999 !important;
        }
    }

    /* Penanganan Mode Desktop (>= 1200px) */
    @media screen and (min-width: 1200px) {
        #sidebar .sidebar-wrapper,
        #sidebar.active .sidebar-wrapper {
            left: 0 !important;
            transform: none !important;
            visibility: visible !important;
            pointer-events: auto !important;
            width: 300px !important;
            z-index: 10 !important;
        }
    }

    /* Hanya area menu yang dapat di-scroll */
    #sidebar .sidebar-menu {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        padding-bottom: 1.5rem !important;
        scrollbar-width: thin;
        scrollbar-color: #d1d5db transparent;
    }

    /* Custom scrollbar ramping untuk WebKit (Chrome, Edge, Safari) */
    #sidebar .sidebar-menu::-webkit-scrollbar {
        width: 5px;
    }

    #sidebar .sidebar-menu::-webkit-scrollbar-track {
        background: transparent;
    }

    #sidebar .sidebar-menu::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    #sidebar .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    /* Tombol Keluar Modern: Tenang, Tidak Agresif, dan Memiliki Micro-Interaction Halus */
    .btn-logout-custom {
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.825rem;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .btn-logout-custom .icon-logout {
        font-size: 1.05rem;
        color: #ef4444;
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), color 0.2s ease;
    }

    .btn-logout-custom:hover {
        background-color: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(220, 38, 38, 0.08);
    }

    .btn-logout-custom:hover .icon-logout {
        color: #dc2626;
        transform: translateX(3px);
    }

    .btn-logout-custom:active {
        transform: translateY(0);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-logout-custom:focus-visible {
        outline: 2px solid #ef4444;
        outline-offset: 2px;
    }

    /* ========================================================
       MODAL KONFIRMASI KELUAR (CUSTOM / DESIGN TASTE FRONTEND)
       ======================================================== */
    .logout-modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.22s ease;
    }

    .logout-modal-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    .logout-modal-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 390px;
        border-radius: 20px;
        padding: 26px 24px 22px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(226, 232, 240, 0.9);
        position: relative;
        transform: scale(0.92) translateY(8px);
        transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .logout-modal-backdrop.active .logout-modal-dialog {
        transform: scale(1) translateY(0);
    }

    .logout-modal-close {
        position: absolute;
        top: 14px;
        right: 14px;
        background: transparent;
        border: none;
        color: #94a3b8;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.15s ease;
    }

    .logout-modal-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .logout-icon-wrapper {
        display: flex;
        justify-content: center;
    }

    .logout-icon-badge {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #fee2e2;
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        box-shadow: 0 0 0 8px #fef2f2;
    }

    .btn-modal-cancel {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.15s ease;
    }

    .btn-modal-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .btn-modal-cancel:focus-visible {
        outline: 2px solid #64748b;
        outline-offset: 2px;
    }

    .btn-modal-confirm {
        background: #dc2626;
        color: #ffffff;
        border: 1px solid #dc2626;
        border-radius: 10px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.15s ease;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
    }

    .btn-modal-confirm:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.28);
    }

    .btn-modal-confirm:active {
        transform: translateY(0);
    }

    .btn-modal-confirm:focus-visible {
        outline: 2px solid #dc2626;
        outline-offset: 2px;
    }
</style>

<script>
    /**
     * Membuka modal konfirmasi keluar dengan transisi halus
     */
    function openLogoutModal() {
        const modal = document.getElementById('logout-modal-backdrop');
        if (modal) {
            modal.classList.add('active');
            // Otomatis arahkan fokus keyboard ke tombol Batal untuk keselamatan aksi
            setTimeout(() => {
                const cancelBtn = document.getElementById('btn-cancel-logout');
                if (cancelBtn) cancelBtn.focus();
            }, 80);
            document.body.style.overflow = 'hidden';
        }
    }

    /**
     * Menutup modal konfirmasi keluar
     */
    function closeLogoutModal() {
        const modal = document.getElementById('logout-modal-backdrop');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    /**
     * Mengirim form logout setelah konfirmasi
     */
    function submitLogoutForm() {
        const confirmBtn = document.getElementById('btn-confirm-logout');
        const logoutForm = document.getElementById('sidebar-logout-form');

        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                <span>Keluar...</span>
            `;
        }

        if (logoutForm) {
            logoutForm.submit();
        }
    }

    // Tutup modal jika pengguna mengklik area backdrop di luar kotak dialog
    document.addEventListener('click', function (e) {
        const modal = document.getElementById('logout-modal-backdrop');
        if (e.target === modal) {
            closeLogoutModal();
        }
    });

    // Dukungan navigasi keyboard: Tombol Escape untuk membatalkan
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('logout-modal-backdrop');
            if (modal && modal.classList.contains('active')) {
                closeLogoutModal();
            }
        }
    });

    // Inisialisasi awal responsif: Buka di desktop (>1200px), sembunyikan di tablet/ponsel
    if (window.innerWidth > 1200) {
        document.getElementById('sidebar')?.classList.add('active', 'sidebar-desktop');
    }
</script>
