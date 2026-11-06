<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cetak Laporan' }}</title>
    <!-- Kita pakai bootstrap dari CDN untuk pencetakan murni agar rapi -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            color: #000;
        }

        .table {
            font-size: 14px;
            /* Ukuran font tabel diperkecil agar lebih rapi dan hemat ruang saat dicetak */
        }

        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .kop-surat::after {
            content: '';
            position: absolute;
            width: 100%;
            border-bottom: 1px solid #000;
            bottom: -5px;
            left: 0;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 1rem;
            }

            body {
                -webkit-print-color-adjust: exact;
                /* Memaksa warna tercetak */
                print-color-adjust: exact;
            }

            .table-bordered,
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important;
                /* Memaksa garis tabel tercetak tajam */
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="container-fluid mt-4">

        <div class="kop-surat text-center">
            <h3 class="mb-1 fw-bold">AQUA LAUNDRY</h3>
            <p class="mb-0">Jl. Karet Pasar Baru Barat No. 31, RT.13/RW.4, Karet Tengsin, Kecamatan Tanah Abang,</p>
            <p class="mb-0">Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10220</p>
            <p class="mb-0">Telp: 0812-1817-8150</p>
        </div>

        <div class="text-center mb-4 mt-4">
            <h5 class="fw-bold text-decoration-underline">{{ strtoupper($title) }}</h5>
            <p class="mb-0">Periode: {{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }}
                s.d. {{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</p>
        </div>

        <table class="table table-bordered border-dark align-middle">
            <thead class="text-center" style="background-color: #f8f9fa !important; -webkit-print-color-adjust: exact;">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="12%" class="text-center">Tanggal</th>
                    <th width="15%" class="text-center">Nomor Nota</th>
                    <th class="text-center">Nama Pelanggan</th>
                    <th width="12%" class="text-center">Status Cucian</th>
                    <th width="12%" class="text-center">Status Pembayaran</th>
                    <th width="14%" class="text-center">Pajak</th>
                    <th width="14%" class="text-center">Pendapatan</th>
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
                        <td class="text-center">{{ $order->order_status == 4 ? 'Sudah Diambil' : 'Dalam Proses' }}</td>
                        <td class="text-center">
                            {{ !is_null($order->order_pay) ? 'Lunas' : 'Belum Lunas' }}
                            @if($order->payment_method)
                                <br><small>({{ $order->payment_method }})</small>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($order->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Bagian Total Terpisah dari Tabel -->
        <div class="row mt-4">
            <div class="col-7 offset-5 col-md-5 offset-md-7">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-start fw-bold">Pajak Terkumpul (Lunas)</td>
                        <td class="text-end" width="10%">: Rp</td>
                        <td class="text-end fw-bold" width="35%">{{ number_format($totalTax, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-start fw-bold">Pendapatan Lunas</td>
                        <td class="text-end">: Rp</td>
                        <td class="text-end fw-bold">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #000;">
                        <td class="text-start fw-bolder fs-6 pt-2">PENDAPATAN BERSIH KOTOR</td>
                        <td class="text-end fs-6 pt-2">: Rp</td>
                        <td class="text-end fw-bolder fs-6 pt-2">{{ number_format($netIncome, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-start fw-bold text-danger">Total Pengeluaran</td>
                        <td class="text-end text-danger">: Rp</td>
                        <td class="text-end fw-bold text-danger">{{ number_format($totalExpense, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #000;">
                        <td class="text-start fw-bolder fs-6 pt-2 text-primary">LABA BERSIH</td>
                        <td class="text-end fs-6 pt-2 text-primary">: Rp</td>
                        <td class="text-end fw-bolder fs-6 pt-2 text-primary">{{ number_format($labaBersih, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-start text-muted fst-italic pt-2">Tagihan Belum Lunas (Bayar di Akhir)</td>
                        <td class="text-end text-muted fst-italic pt-2">: Rp</td>
                        <td class="text-end text-muted fst-italic pt-2">{{ number_format($unpaidRevenue, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p>Jakarta, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</p>
                <br><br><br>
                <p><strong><u>Admin</u></strong></p>
            </div>
        </div>

    </div>
</body>

</html>
