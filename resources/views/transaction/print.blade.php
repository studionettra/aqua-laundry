<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cetak Nota' }} - {{ $order->order_code }}</title>
    <!-- Kita pakai bootstrap dari CDN untuk pencetakan murni -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            background-color: #fff;
        }

        .nota-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        @media print {
            @page {
                size: A4 portrait;
                /* auto is the initial value */
                margin: 0;
                /* this affects the margin in the printer settings */
            }

            body {
                margin: 1.6cm;
                /* margin you want for the content */
            }

            .no-print {
                display: none !important;
            }
        }

        .status-stamp {
            display: inline-block;
            padding: 5px 10px;
            border: 2px dashed #000;
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }
    </style>
</head>

<body onload="window.print()">

    <div class="nota-container">
        <!-- Tombol Kembali (Tidak akan tercetak) -->
        <div class="mb-4 no-print text-center">
            <button onclick="window.close()" class="btn btn-secondary">Tutup Halaman</button>
            <button onclick="window.print()" class="btn btn-primary">Cetak Ulang</button>
        </div>

        <div class="text-center mb-4">
            <h2>NOTA AQUA LAUNDRY</h2>
            <p class="mb-0">Jl. Karet Pasar Baru Barat No. 31, RT.13/RW.4, RW.4, Karet Tengsin, Kecamatan Tanah Abang,
                Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10220</p>
            <p>Telp: 0812-1817-8150</p>
            <hr style="border: 1px dashed #000;">
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <strong>Informasi Pelanggan:</strong><br>
                Nama: {{ $order->customer->customer_name ?? '-' }}<br>
                Telp: {{ $order->customer->phone ?? '-' }}<br>
                Alamat: {{ $order->customer->address ?? '-' }}
            </div>
            <div class="col-6 text-end">
                <strong>Informasi Nota:</strong><br>
                No. Nota: <strong>{{ $order->order_code }}</strong><br>
                Kasir: {{ auth()->check() ? auth()->user()->name : 'Sistem' }}<br>
                Tanggal Masuk: {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}<br>
                Estimasi Selesai:
                {{ $order->order_end_date ? \Carbon\Carbon::parse($order->order_end_date)->format('d-m-Y') : '-' }}<br>
                {{-- Pembayaran:
                {{ !is_null($order->order_pay) && $order->order_pay >= $order->total ? 'LUNAS' : 'BELUM LUNAS' }}<br> --}}
                Pengambilan: {{ $order->order_status == 1 ? 'Diambil' : 'Diproses' }}
                @if ($order->order_status == 1 && $order->pickup)
                    <br>Tanggal Ambil: {{ \Carbon\Carbon::parse($order->pickup->pickup_date)->format('d-m-Y') }}
                    @php
                        $cleanNotes = trim(str_replace('(Lunas dibayar saat Pickup)', '', $order->pickup->notes ?? ''));
                    @endphp
                    @if($cleanNotes)
                        <br>Catatan Ambil: {{ $cleanNotes }}
                    @endif
                @endif
            </div>
        </div>

        <table class="table table-sm table-borderless"
            style="border-top: 1px dashed #000; border-bottom: 1px dashed #000;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Layanan</th>
                    <th>Harga</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $regularDetails = $order->details;
                    $loopIndex = 1;
                @endphp
                @foreach ($regularDetails as $detail)
                    <tr>
                        <td>{{ $loopIndex++ }}</td>
                        <td>{{ $detail->service->service_name ?? 'Layanan dihapus' }}
                            @if ($detail->notes)
                                <br><small><i>({{ $detail->notes }})</i></small>
                            @endif
                        </td>
                        <td>Rp {{ number_format($detail->qty > 0 ? $detail->subtotal / $detail->qty : 0, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->qty }}</td>
                        <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-6">
                <p class="mt-4"><strong>Catatan:</strong><br>
                    1. Pengambilan cucian harus membawa nota ini.<br>
                    2. Cucian yang tidak diambil lebih dari 30 hari bukan tanggung jawab kami.</p>
            </div>
            <div class="col-6 text-end">
                <table class="table table-sm table-borderless">
                    @if($order->tax_amount > 0)
                    <tr>
                        <td class="text-end">Subtotal :</td>
                        <td class="text-end">Rp {{ number_format($order->total - $order->tax_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end">Pajak PPN ({{ $order->tax_percentage }}%) :</td>
                        <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-end">Grand Total :</th>
                        <th class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                    </tr>
                    @if ($order->order_pay)
                        <tr>
                            <td class="text-end">Dibayar :</td>
                            <td class="text-end">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="text-end">Kembali :</td>
                            <td class="text-end">Rp {{ number_format($order->order_change, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
            @if (!is_null($order->order_pay) && $order->order_pay >= $order->total)
                <div class="status-stamp" style="border-color: green; color: green;">LUNAS {{ $order->payment_method ? '('.$order->payment_method.')' : '' }}</div>
            @else
                <div class="status-stamp" style="border-color: red; color: red;">BELUM LUNAS</div>
            @endif
            <p class="mt-4">*** TERIMA KASIH ATAS KEPERCAYAAN ANDA ***</p>
        </div>
    </div>

</body>

</html>
