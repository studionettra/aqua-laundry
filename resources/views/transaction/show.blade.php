@extends('layouts.app')
@section('title', 'Detail Order')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }} - {{ $order->order_code }}</h3>
        </div> --}}
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informasi Customer</h5>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="150">Nama</td>
                            <td>: {{ $order->customer->customer_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>No. Telepon</td>
                            <td>: {{ $order->customer->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>: {{ $order->customer->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Informasi Pesanan</h5>
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td width="150">Tanggal Masuk</td>
                            <td>: {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Selesai</td>
                            <td>:
                                {{ $order->order_end_date ? \Carbon\Carbon::parse($order->order_end_date)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:
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
                                
                                @if($order->order_status != 4)
                                <form action="{{ route('transaction.update-status', $order->id) }}" method="POST" class="d-inline-block ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="order_status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="0" {{ $order->order_status == 0 ? 'selected' : '' }}>Baru</option>
                                        <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>Dicuci</option>
                                        <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>Disetrika</option>
                                        <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>Siap Diambil</option>
                                    </select>
                                </form>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status Pembayaran</td>
                            <td>:
                                @if (!is_null($order->order_pay))
                                    <span class="badge bg-info text-dark">Lunas</span>
                                    @if($order->payment_method)
                                        <span class="badge bg-secondary ms-1">{{ $order->payment_method }}</span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">Belum Dibayar</span>
                                @endif
                            </td>
                        </tr>
                        @if ($order->order_status == 1 && $order->pickup)
                        <tr>
                            <td>Tanggal Diambil</td>
                            <td>: {{ \Carbon\Carbon::parse($order->pickup->pickup_date)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td>Catatan Ambil</td>
                            <td>: 
                                @php
                                    $cleanNotes = trim(str_replace('(Lunas dibayar saat Pickup)', '', $order->pickup->notes ?? ''));
                                @endphp
                                {{ $cleanNotes ?: '-' }}
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <h5>Rincian Layanan</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th>Qty / Berat</th>
                            <th>Subtotal</th>
                            <th>Catatan</th>
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
                                <td>{{ $detail->service->service_name ?? 'Layanan dihapus' }}</td>
                                <td>Rp {{ number_format($detail->qty > 0 ? $detail->subtotal / $detail->qty : 0, 0, ',', '.') }}</td>
                                <td>{{ $detail->qty }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td>{{ $detail->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @if ($order->tax_amount > 0)
                            <tr>
                                <th colspan="4" class="text-end">Pajak PPN ({{ $order->tax_percentage }}%)</th>
                                <th colspan="2">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</th>
                            </tr>
                        @endif
                        <tr>
                            <th colspan="4" class="text-end">Grand Total</th>
                            <th colspan="2">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                        </tr>
                        @if ($order->order_pay)
                            <tr>
                                <th colspan="4" class="text-end">Dibayar</th>
                                <th colspan="2">Rp {{ number_format($order->order_pay, 0, ',', '.') }}</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Kembalian</th>
                                <th colspan="2">Rp {{ number_format($order->order_change, 0, ',', '.') }}</th>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            <div class="mt-4">
                <a href="{{ route('transaction.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
