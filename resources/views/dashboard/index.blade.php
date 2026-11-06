@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <!-- Penjelasan: Bagian Selamat Datang -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-3 px-3 p-md-4">
                    <div class="d-flex align-items-center">
                        <!-- Menampilkan avatar default pengguna -->
                        <div class="avatar avatar-lg avatar-md-xl flex-shrink-0">
                            <img src="{{ asset('template/dist/assets/static/images/faces/2.jpg') }}" alt="User Avatar">
                        </div>
                        <!-- Menampilkan nama dan role pengguna yang diambil dari session autentikasi -->
                        <div class="ms-3 name lh-sm text-truncate">
                            <h5 class="font-bold mb-1 text-truncate" style="font-size: 1.1rem;">Selamat Datang, {{ auth()->user()->name }}!</h5>
                            <h6 class="text-muted mb-0 small text-truncate">{{ auth()->user()->level->level_name ?? 'Staff' }} - Aqua Laundry</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjelasan: Kartu Ringkasan Statistik Operasional Hari Ini -->
    <div class="row mb-3 mb-md-4 g-2 g-md-3">
        <!-- Kartu 1: Order Masuk Hari Ini -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 mb-0">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        <div class="stats-icon purple flex-shrink-0">
                            <i class="iconly-boldBuy"></i>
                        </div>
                        <div class="text-truncate" style="min-width: 0;">
                            <h6 class="text-muted font-semibold text-sm mb-1 text-truncate">Order Hari Ini</h6>
                            <h5 class="font-extrabold mb-0 text-truncate">{{ $todayOrders }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kartu 2: Pendapatan Hari Ini -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 mb-0">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        <div class="stats-icon green flex-shrink-0">
                            <i class="iconly-boldWallet"></i>
                        </div>
                        <div class="text-truncate" style="min-width: 0;">
                            <h6 class="text-muted font-semibold text-sm mb-1 text-truncate">Pendapatan</h6>
                            <h5 class="font-extrabold mb-0 text-truncate text-sm" title="Rp {{ number_format($todayRevenue, 0, ',', '.') }}">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kartu 3: Cucian Belum Diambil -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 mb-0">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        <div class="stats-icon blue flex-shrink-0">
                            <i class="iconly-boldActivity"></i>
                        </div>
                        <div class="text-truncate" style="min-width: 0;">
                            <h6 class="text-muted font-semibold text-sm mb-1 text-truncate">Belum Diambil</h6>
                            <h5 class="font-extrabold mb-0 text-truncate">{{ $unclaimedOrders }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kartu 4: Dinamis (Admin/Operator: Tagihan Belum Dibayar, Pimpinan: Total Pelanggan) -->
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm border-0 mb-0">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center gap-2 gap-sm-3">
                        @if (in_array(auth()->user()->level->level_name ?? '', ['Administrator', 'Operator']))
                            <div class="stats-icon text-white d-flex justify-content-center align-items-center flex-shrink-0" style="background-color: #ff9800;">
                                <i class="iconly-boldTime-Circle"></i>
                            </div>
                            <div class="text-truncate" style="min-width: 0;">
                                <h6 class="text-muted font-semibold text-sm mb-1 text-truncate">Tagihan Unpaid</h6>
                                <h5 class="font-extrabold mb-0 text-truncate text-sm" title="Rp {{ number_format($unpaidBills ?? 0, 0, ',', '.') }}">Rp {{ number_format($unpaidBills ?? 0, 0, ',', '.') }}</h5>
                            </div>
                        @else
                            <div class="stats-icon red flex-shrink-0">
                                <i class="iconly-boldProfile"></i>
                            </div>
                            <div class="text-truncate" style="min-width: 0;">
                                <h6 class="text-muted font-semibold text-sm mb-1 text-truncate">Total Pelanggan</h6>
                                <h5 class="font-extrabold mb-0 text-truncate">{{ $totalCustomers }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Khusus Pimpinan: Ringkasan Bulanan & Grafik -->
    @if (in_array(auth()->user()->level->level_name ?? '', ['Pimpinan']))
        <div class="row mb-4">
            <!-- Summary Bulanan -->
            <div class="col-12 col-xl-4 mb-4 mb-xl-0">
                <div class="card h-100 shadow-sm">
                    <div class="card-body py-4">
                        <h5 class="card-title mb-4">Ringkasan Bulan Ini</h5>
                        <div class="d-flex align-items-center mb-4">
                            <div class="stats-icon blue d-flex align-items-center justify-content-center me-3" style="width: 3rem; height: 3rem;">
                                <i class="iconly-boldWallet"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Pendapatan Bulan Ini</h6>
                                <h4 class="font-extrabold mb-0">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="stats-icon purple d-flex align-items-center justify-content-center me-3" style="width: 3rem; height: 3rem;">
                                <i class="iconly-boldBuy"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Order Bulan Ini</h6>
                                <h4 class="font-extrabold mb-0">{{ $monthlyOrders ?? 0 }}</h4>
                            </div>
                        </div>
                        <a href="{{ route('report.index') }}" class="btn btn-primary w-100 mt-2">
                            <i class="bi bi-file-earmark-bar-graph-fill me-2"></i>Lihat Detail Laporan
                        </a>
                    </div>
                </div>
            </div>
            <!-- Grafik Tren Pendapatan -->
            <div class="col-12 col-xl-8">
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title">Tren Pendapatan (7 Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenue-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Penjelasan: Aksi Cepat / Shortcut Buttons -->
    @if (in_array(auth()->user()->level->level_name ?? '', ['Administrator', 'Operator']))
        <style>
            .btn-quick-action {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .btn-quick-action i::before {
                vertical-align: 0 !important;
            }
        </style>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm mb-0">
                    <div class="card-body py-4">
                        <h5 class="card-title mb-3">Aksi Cepat</h5>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('transaction.create') }}" class="btn btn-primary btn-quick-action">
                                <i class="bi bi-cart-plus-fill me-2"></i>
                                <span>Tambah Order Baru</span>
                            </a>
                            <a href="{{ route('pickup.create') }}" class="btn btn-success btn-quick-action">
                                <i class="bi bi-bag-check-fill me-2"></i>
                                <span>Catat Pengambilan</span>
                            </a>
                            <a href="{{ route('customer.create') }}" class="btn btn-info text-white btn-quick-action">
                                <i class="bi bi-person-plus-fill me-2"></i>
                                <span class="d-none d-md-inline-block">Tambah Pelanggan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Penjelasan: Daftar Cucian Aktif Terbaru yang Belum Diambil -->
    @if (in_array(auth()->user()->level->level_name ?? '', ['Administrator', 'Operator']))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Antrean Cucian Terbaru (Belum Diambil)</h4>
                    </div> --}}
                    <div class="card-body">
                        <h5 class="mb-3">Antrean Cucian Terbaru (Belum Diambil)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0 text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th class="text-center" style="width: 15%">Kode Nota</th>
                                        <th class="text-center" style="width: 20%">Nama Pelanggan</th>
                                        <th class="text-center" style="width: 15%">Tanggal Masuk</th>
                                        <th class="text-center" style="width: 15%">Batas Pengambilan</th>
                                        <th class="text-center" style="width: 15%">Total Tagihan</th>
                                        <th class="text-center" style="width: 15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentActiveOrders as $index => $order)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center font-bold text-primary">{{ $order->order_code }}</td>
                                            <td>{{ $order->customer->customer_name ?? '-' }}</td>
                                            <td class="text-center">{{ date('d M Y', strtotime($order->order_date)) }}</td>
                                            <td class="text-center">
                                                @if ($order->order_end_date)
                                                    @if (strtotime($order->order_end_date) < time())
                                                        <span class="badge bg-danger" title="Sudah melewati batas waktu!">
                                                            {{ date('d M Y', strtotime($order->order_end_date)) }} (Overdue)
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            {{ date('d M Y', strtotime($order->order_end_date)) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-sm btn-info text-white icon" title="Detail Order">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    <a href="{{ route('transaction.print', $order->id) }}" target="_blank" class="btn btn-sm btn-secondary icon" title="Cetak Nota">
                                                        <i class="bi bi-printer-fill"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="bi bi-info-circle fs-4 d-block mb-2"></i>
                                                Tidak ada cucian aktif yang belum diambil saat ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (in_array(auth()->user()->level->level_name ?? '', ['Pimpinan']))
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var options = {
                    series: [{
                        name: 'Pendapatan',
                        data: {!! json_encode($chartRevenues ?? []) !!}
                    }],
                    chart: {
                        height: 300,
                        type: 'area',
                        toolbar: { show: false },
                        fontFamily: 'Nunito, sans-serif'
                    },
                    colors: ['#435ebe'],
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    xaxis: {
                        categories: {!! json_encode($chartDates ?? []) !!},
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return "Rp " + value.toLocaleString("id-ID");
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return "Rp " + val.toLocaleString("id-ID");
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
                chart.render();
            });
        </script>
    @endif
@endsection
