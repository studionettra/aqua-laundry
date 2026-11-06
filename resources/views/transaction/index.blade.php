@extends('layouts.app')
@section('title', 'Order Laundry')
@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">
                {{ $title ?? '' }}
            </h3>
        </div> --}}
        <div class="card-body">
            <div class="card-tools mb-3" align="right">
                <a href="{{ route('transaction.create') }}" class="btn btn-primary">Buat Order</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Order</th>
                            <th class="text-center">Customer</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tipe Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr>
                                <td class="text-center">{{ $index += 1 }}</td>
                                <td class="text-center">{{ $order->order_code }}</td>
                                <td class="text-center">{{ $order->customer->customer_name ?? 'Unknown' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                                </td>
                                <td class="text-center">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($order->order_status == 0)
                                        <span class="badge bg-secondary">Baru</span>
                                    @elseif ($order->order_status == 1)
                                        <span class="badge bg-warning text-dark">Dicuci</span>
                                    @elseif ($order->order_status == 2)
                                        <span class="badge bg-info text-dark">Disetrika</span>
                                    @elseif ($order->order_status == 3)
                                        <span class="badge bg-primary">Siap Diambil</span>
                                    @elseif ($order->order_status == 4)
                                        <span class="badge bg-success">Sudah Diambil</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($order->payment_method)
                                        <span class="badge bg-info text-dark">{{ $order->payment_method }}</span>
                                    @else
                                        @if ($order->order_status == 4 && $order->pickup && str_contains($order->pickup->notes ?? '', '(Lunas dibayar saat Pickup)'))
                                            <span class="badge bg-secondary" title="Lunas saat Pickup">Bayar Di Akhir</span>
                                        @elseif (!is_null($order->order_pay))
                                            <span class="badge bg-info text-dark">Bayar Di Awal</span>
                                        @else
                                            <span class="badge bg-secondary">Bayar Di Akhir</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('transaction.show', $order->id) }}" class="btn btn-info icon"
                                        title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('transaction.print', $order->id) }}" target="_blank"
                                        class="btn btn-secondary icon" title="Cetak Nota">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    @if($order->customer->phone)
                                        @php
                                            $phone = $order->customer->phone;
                                            if (substr($phone, 0, 1) == '0') {
                                                $phone = '62' . substr($phone, 1);
                                            }
                                            $statusText = $order->order_status == 3 ? 'sudah *Siap Diambil*' : 'sedang *Diproses*';
                                            $waMessage = "Halo kak *" . ($order->customer->customer_name ?? '') . "*, pesanan laundry dengan nomor nota *" . $order->order_code . "* saat ini " . $statusText . ". Total tagihan: *Rp " . number_format($order->total, 0, ',', '.') . "*. Terima kasih.";
                                        @endphp
                                        <a href="https://wa.me/{{ $phone }}?text={{ urlencode($waMessage) }}" target="_blank" class="btn btn-success icon" title="Kirim WA">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('transaction.destroy', $order->id) }}" class="btn btn-danger icon"
                                        data-confirm-delete="true" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
