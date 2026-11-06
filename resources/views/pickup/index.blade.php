@extends('layouts.app')
@section('title', 'Antrian Pengambilan')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="table1">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Order</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Tanggal Order</th>
                        <th class="text-center">Total (Rp)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $order)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $order->order_code }}</td>
                            <td class="text-center">{{ $order->customer->customer_name ?? 'Unknown' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</td>
                            <td class="text-center">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if ($order->customer->phone)
                                    @php
                                        $phone = $order->customer->phone;
                                        if (substr($phone, 0, 1) == '0') {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                        $waMessage = "Halo kak *" . ($order->customer->customer_name ?? '') . "*, pesanan laundry dengan nomor nota *" . $order->order_code . "* sudah selesai dan *Siap Diambil*. Total tagihan: *Rp " . number_format($order->total, 0, ',', '.') . "*. Terima kasih.";
                                    @endphp
                                    <a href="https://wa.me/{{ $phone }}?text={{ urlencode($waMessage) }}" target="_blank" class="btn btn-sm btn-success me-1" title="Kirim WA">
                                        <i class="bi bi-whatsapp"></i> WA
                                    </a>
                                @endif
                                
                                @if ($order->order_pay < $order->total)
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#pickupModal{{ $order->id }}">
                                        <i class="bi bi-cash-coin"></i> Tagihan
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#pickupModal{{ $order->id }}">
                                        <i class="bi bi-bag-check"></i> Ambil
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal Pickup -->
                        <div class="modal fade" id="pickupModal{{ $order->id }}" tabindex="-1"
                            aria-labelledby="pickupModalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('pickup.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_order" value="{{ $order->id }}">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="pickupModalLabel{{ $order->id }}">Konfirmasi
                                                Pengambilan - {{ $order->order_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Customer:
                                                <strong>{{ $order->customer->customer_name ?? 'Unknown' }}</strong>
                                            </p>
                                            <p>Total Tagihan: <strong>Rp
                                                    {{ number_format($order->total, 0, ',', '.') }}</strong></p>

                                            @if ($order->order_pay < $order->total)
                                                <div class="alert alert-warning px-3 py-2 mb-3">
                                                    <small><strong>Belum Lunas:</strong> Silakan selesaikan pembayaran
                                                        terlebih dahulu.</small>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Metode Pembayaran *</label>
                                                        <select name="payment_method" class="form-select" required>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Transfer">Transfer</option>
                                                            <option value="QRIS">QRIS</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Uang Bayar</label>
                                                        <input type="number" name="order_pay"
                                                            class="form-control order-pay-input"
                                                            data-total="{{ $order->total }}" required
                                                            min="{{ $order->total }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kembalian (Rp)</label>
                                                        <input type="text"
                                                            class="form-control bg-light order-change-input" readonly
                                                            value="0">
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label">Catatan Pengambilan (opsional)</label>
                                                <textarea name="notes" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit"
                                                class="btn {{ $order->order_pay < $order->total ? 'btn-warning' : 'btn-success' }}">
                                                {{ $order->order_pay < $order->total ? 'Bayar & Konfirmasi Ambil' : 'Konfirmasi Ambil' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payInputs = document.querySelectorAll('.order-pay-input');

            payInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const total = parseFloat(this.getAttribute('data-total') || 0);
                    const payAmount = parseFloat(this.value || 0);
                    const changeAmount = payAmount - total;
                    const modal = this.closest('.modal-content');
                    const changeInput = modal.querySelector('.order-change-input');
                    const submitBtn = modal.querySelector('button[type="submit"]');

                    if (!this.value) {
                        changeInput.value = '0';
                        changeInput.className =
                            'form-control bg-light order-change-input text-dark';
                        submitBtn.disabled = true;
                        return;
                    }

                    if (changeAmount < 0) {
                        changeInput.value = 'Uang Kurang';
                        changeInput.className =
                            'form-control bg-light order-change-input text-danger fw-bold';
                        submitBtn.disabled = true;
                    } else if (changeAmount === 0) {
                        changeInput.value = '0';
                        changeInput.className =
                            'form-control bg-light order-change-input text-dark fw-bold';
                        submitBtn.disabled = false;
                    } else {
                        changeInput.value = new Intl.NumberFormat('id-ID', {
                            maximumFractionDigits: 0
                        }).format(changeAmount);
                        changeInput.className =
                            'form-control bg-light order-change-input text-success fw-bold';
                        submitBtn.disabled = false;
                    }
                });
            });
        });
    </script>
@endsection
