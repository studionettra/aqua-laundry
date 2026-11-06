@extends('layouts.app')
@section('title', 'Laporan Pendapatan Laundry')

@section('content')
    <!-- Section Operasional & Pembayaran -->
    <div class="row mb-4 g-4">
        <!-- Operasional -->
        <div class="col-12 col-xl-5">
            <h6 class="mb-3 font-semibold text-muted"><i class="bi bi-activity"></i> OPERASIONAL</h6>
            <div class="row row-cols-1 row-cols-sm-2 g-3">
                <!-- Kartu 1: Total Orderan -->
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 align-items-center">
                        <div class="card-body p-3 d-flex align-items-center w-100">
                            <div class="stats-icon purple d-flex align-items-center justify-content-center me-3 mb-0">
                                <i class="iconly-boldBuy"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold text-sm mb-1">Total Order</h6>
                                <h5 class="font-extrabold mb-0">{{ $totalOrders }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Kartu 2: Rata-rata Nilai Transaksi -->
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 align-items-center">
                        <div class="card-body p-3 d-flex align-items-center w-100">
                            <div class="stats-icon green d-flex align-items-center justify-content-center me-3 mb-0">
                                <i class="iconly-boldWallet"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold text-sm mb-1">Avg Transaksi</h6>
                                <h6 class="font-extrabold mb-0 text-truncate text-sm"
                                    title="Rp {{ number_format($averageTransactionValue, 0, ',', '.') }}">Rp
                                    {{ number_format($averageTransactionValue, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="col-12 col-xl-7">
            <h6 class="mb-3 font-semibold text-muted"><i class="bi bi-credit-card"></i> METODE PEMBAYARAN</h6>
            <div class="row row-cols-1 row-cols-sm-3 g-3">
                <!-- Cash -->
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-info align-items-center">
                        <div class="card-body p-3 d-flex align-items-center w-100">
                            <div class="stats-icon info d-flex align-items-center justify-content-center me-3 mb-0" style="background-color: #0dcaf0;">
                                <i class="iconly-boldWallet text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-info font-semibold text-sm mb-1">Total Cash</h6>
                                <h5 class="font-extrabold mb-0 text-info">Rp {{ number_format($totalCash ?? 0, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Transfer -->
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-secondary align-items-center">
                        <div class="card-body p-3 d-flex align-items-center w-100">
                            <div class="stats-icon secondary d-flex align-items-center justify-content-center me-3 mb-0" style="background-color: #6c757d;">
                                <i class="iconly-boldSend text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-secondary font-semibold text-sm mb-1">Total Transfer</h6>
                                <h5 class="font-extrabold mb-0 text-secondary">Rp {{ number_format($totalTransfer ?? 0, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- QRIS -->
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-warning align-items-center">
                        <div class="card-body p-3 d-flex align-items-center w-100">
                            <div class="stats-icon warning d-flex align-items-center justify-content-center me-3 mb-0" style="background-color: #ffc107;">
                                <i class="iconly-boldScan text-white"></i>
                            </div>
                            <div>
                                <h6 class="text-warning font-semibold text-sm mb-1">Total QRIS</h6>
                                <h5 class="font-extrabold mb-0 text-warning">Rp {{ number_format($totalQRIS ?? 0, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Keuangan -->
    <h6 class="mb-3 font-semibold text-muted mt-2"><i class="bi bi-cash-stack"></i> REKAPITULASI KEUANGAN (LUNAS)</h6>
    <!-- Kartu Finansial (Rekapitulasi Pendapatan Lunas) -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-4">
        <!-- Finansial 1: Total Pendapatan Kotor -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 border-start border-4 border-primary align-items-center">
                <div class="card-body p-3 d-flex align-items-center w-100">
                    <div class="stats-icon blue d-flex align-items-center justify-content-center me-4 mb-0">
                        <i class="iconly-boldWallet"></i>
                    </div>
                    <div>
                        <h6 class="text-muted font-semibold text-sm mb-1">Total Kotor (Lunas)</h6>
                        <h4 class="font-extrabold mb-0 text-dark">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finansial 2: Total Pajak -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 border-start border-4 border-secondary align-items-center">
                <div class="card-body p-3 d-flex align-items-center w-100">
                    <div class="stats-icon red d-flex align-items-center justify-content-center me-4 mb-0">
                        <i class="iconly-boldDocument"></i>
                    </div>
                    <div>
                        <h6 class="text-muted font-semibold text-sm mb-1">Total Pajak (Lunas)</h6>
                        <h4 class="font-extrabold mb-0 text-dark">Rp {{ number_format($totalTax, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finansial 3: Pendapatan Bersih -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 border-start border-4 border-success align-items-center">
                <div class="card-body p-3 d-flex align-items-center w-100">
                    <div class="stats-icon green d-flex align-items-center justify-content-center me-4 mb-0">
                        <i class="iconly-boldChart"></i>
                    </div>
                    <div>
                        <h6 class="text-success font-semibold text-sm mb-1">Pendapatan Bersih</h6>
                        <h4 class="font-extrabold mb-0 text-success">Rp {{ number_format($netIncome, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finansial 4: Total Pengeluaran -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 border-start border-4 border-danger align-items-center">
                <div class="card-body p-3 d-flex align-items-center w-100">
                    <div class="stats-icon red d-flex align-items-center justify-content-center me-4 mb-0" style="background-color: #dc3545;">
                        <i class="iconly-boldGraph"></i>
                    </div>
                    <div>
                        <h6 class="text-danger font-semibold text-sm mb-1">Total Pengeluaran</h6>
                        <h4 class="font-extrabold mb-0 text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finansial 5: Laba Bersih -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 border-start border-4 border-primary bg-primary bg-opacity-10 align-items-center">
                <div class="card-body p-3 d-flex align-items-center w-100">
                    <div class="stats-icon blue d-flex align-items-center justify-content-center me-4 mb-0">
                        <i class="iconly-boldActivity"></i>
                    </div>
                    <div>
                        <h6 class="text-primary font-semibold text-sm mb-1">Laba Bersih</h6>
                        <h4 class="font-extrabold mb-0 text-primary">Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finansial 6: Tagihan Belum Lunas -->
        <div class="col">
            <div class="card h-100 shadow-sm border-0 border-start border-4 border-warning align-items-center">
                <div class="card-body p-3 d-flex align-items-center w-100">
                    <div class="stats-icon d-flex align-items-center justify-content-center me-4 mb-0" style="background-color: #ff9800;">
                        <i class="iconly-boldTime-Circle text-white"></i>
                    </div>
                    <div>
                        <h6 class="text-muted font-semibold text-sm mb-1">Tagihan Belum Lunas</h6>
                        <h4 class="font-extrabold mb-0 text-dark">Rp {{ number_format($unpaidRevenue, 0, ',', '.') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card Laporan -->
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">

            <!-- Form Filter Berdasarkan Tanggal -->
            <div class="p-3 rounded shadow-sm mb-4 border border-light-subtle">
                <form action="{{ route('report.index') }}" method="GET" class="mb-0">
                    <div class="row align-items-end g-2">
                        <div class="col-md-2">
                            <label for="" class="form-label fw-bold text-muted mb-1">Tanggal Awal</label>
                            <input type="date" name="start_date" class="form-control border-secondary-subtle"
                                value="{{ $startDate }}" required>
                        </div>
                        <div class="col-md-2">
                            <label for="" class="form-label fw-bold text-muted mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control border-secondary-subtle"
                                value="{{ $endDate }}" required>
                        </div>
                        <div class="col-md-2">
                            <label for="" class="form-label fw-bold text-muted mb-1">Metode Bayar</label>
                            <select name="payment_method" class="form-select border-secondary-subtle">
                                <option value="">Semua</option>
                                <option value="Cash" {{ ($paymentMethod ?? '') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Transfer" {{ ($paymentMethod ?? '') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="QRIS" {{ ($paymentMethod ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            </select>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm me-2">
                                <i class="bi bi-search"></i> Tampilkan
                            </button>
                            <a href="{{ route('report.print', ['start_date' => $startDate, 'end_date' => $endDate, 'payment_method' => $paymentMethod ?? '']) }}"
                                target="_blank" class="btn btn-success px-4 shadow-sm">
                                <i class="bi bi-printer"></i> Cetak Laporan
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Hasil Laporan -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Nomor Nota</th>
                            <th>Nama Pelanggan</th>
                            <th width="12%">Status Cucian</th>
                            <th width="15%">Status Pembayaran</th>
                            <th width="15%">Pajak</th>
                            <th width="15%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($order->order_date)->locale('id')->translatedFormat('d F Y') }}
                                </td>
                                <td class="text-center">{{ $order->order_code }}</td>
                                <td class="text-center">{{ $order->customer->customer_name ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($order->order_status == 4)
                                        <span class="badge bg-success px-3 py-1">Diambil</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-1">Diproses</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!is_null($order->order_pay))
                                        <span class="badge bg-primary px-3 py-1">Lunas</span>
                                        @if($order->payment_method)
                                            <div class="mt-1"><small class="text-muted">{{ $order->payment_method }}</small></div>
                                        @endif
                                    @else
                                        <span class="badge bg-danger px-3 py-1">Belum Lunas</span>
                                    @endif
                                </td>
                                <td class="text-center">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                                <td class="text-center">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada transaksi pada rentang
                                    tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>



        </div>
    </div>
@endsection
