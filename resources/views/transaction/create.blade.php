@extends('layouts.app')
@section('title', 'Buat Order Baru')

@section('content')
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('transaction.store') }}" method="POST" id="orderForm">
                @csrf
                <!-- <h5 class="mb-3">Informasi Pesanan</h5> -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label for="">Customer *</label>
                        <select name="id_customer" class="form-control" required id="customerSelect">
                            <option value="">Pilih Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }} - {{ $customer->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="">Tanggal Order *</label>
                        <input type="date" class="form-control" name="order_date" required value="{{ date('Y-m-d') }}"
                            min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="">Estimasi Selesai *</label>
                        <input type="date" class="form-control" name="order_end_date" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <h5 class="mb-3">Rincian Layanan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="serviceTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Layanan *</th>
                                <th width="15%" class="text-center">Harga (Rp)</th>
                                <th width="10%" class="text-center">Qty / Kg *</th>
                                <th width="12%" class="text-center">Pajak</th>
                                <th width="15%" class="text-center">Subtotal (Rp)</th>
                                <th class="text-center">Catatan</th>
                                <th width="5%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="serviceTableBody">
                            <!-- Baris dinamis akan disisipkan di sini oleh JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <button type="button" class="btn btn-sm btn-success" id="btnTambahLayanan">
                                        <i class="bi bi-plus-circle"></i> Tambah Layanan
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end h6">Subtotal</th>
                                <th colspan="3" class="h6">
                                    Rp <span id="subTotalDisplay">0</span>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end h6">Pajak ({{ $tax_percentage }}%)</th>
                                <th colspan="3" class="h6">
                                    Rp <span id="taxDisplay">0</span>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end h6">Total Tagihan</th>
                                <th colspan="3" class="h6">
                                    Rp <span id="grandTotalDisplay">0</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h5 class="mb-3">Pilihan Pembayaran</h5>
                <div class="form-group mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_timing" id="payment_akhir"
                            value="di_akhir" {{ old('payment_timing', 'di_akhir') == 'di_akhir' ? 'checked' : '' }}>
                        <label class="form-check-label" for="payment_akhir">Di Akhir (Saat Pick Up)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_timing" id="payment_awal"
                            value="di_awal" {{ old('payment_timing') == 'di_awal' ? 'checked' : '' }}>
                        <label class="form-check-label" for="payment_awal">Di Awal (Sekarang)</label>
                    </div>
                </div>

                <div id="paymentFields" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="payment_method">Metode Pembayaran *</label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Transfer" {{ old('payment_method') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="QRIS" {{ old('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="order_pay">Jumlah Bayar (Rp) *</label>
                            <input type="number" class="form-control @error('order_pay') is-invalid @enderror"
                                name="order_pay" id="order_pay" min="0" step="any"
                                value="{{ old('order_pay') }}">
                            @error('order_pay')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="order_change">Kembalian (Rp)</label>
                            <input type="text" class="form-control bg-light" id="order_change" readonly
                                value="0">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Buat Pesanan</button>
                    <a href="{{ route('transaction.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery & Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Data Services dalam format JSON untuk digunakan oleh Vanilla JS -->
    <script>
        // Penjelasan: Mengkonversi koleksi services dari PHP ke JSON object agar bisa dibaca oleh Javascript Murni
        const servicesData = @json($services);
        const taxPercentage = {{ $tax_percentage }};
        const taxIsActive = {{ $tax_is_active ? 'true' : 'false' }};

        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('serviceTableBody');
            const btnTambahLayanan = document.getElementById('btnTambahLayanan');
            const subTotalDisplay = document.getElementById('subTotalDisplay');
            const taxDisplay = document.getElementById('taxDisplay');
            const grandTotalDisplay = document.getElementById('grandTotalDisplay');

            // Inisialisasi Select2
            $(document).ready(function() {
                $('#customerSelect').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih Customer',
                    width: '100%'
                });
            });

            // Penjelasan: Fungsi untuk menambah baris layanan baru secara dinamis (tanpa jQuery)
            function addServiceRow() {
                const tr = document.createElement('tr');

                // Membuat struktur opsi dropdown dari data JSON
                let optionsHtml = '<option value="">Pilih Layanan</option>';
                servicesData.forEach(service => {
                    optionsHtml +=
                        `<option value="${service.id}" data-price="${service.price}">${service.service_name}</option>`;
                });

                // Memasukkan template HTML ke dalam baris tabel
                tr.innerHTML = `
                    <td>
                        <select name="service_id[]" class="form-control service-select" required>
                            ${optionsHtml}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control price-input bg-light" readonly value="0">
                    </td>
                    <td>
                        <input type="number" name="qty[]" class="form-control qty-input" min="0.1" step="any" value="1" required>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center tax-value-display fw-bold text-dark bg-transparent" readonly value="{{ $tax_percentage }}%">
                    </td>
                    <td>
                        <input type="text" class="form-control subtotal-input bg-light" readonly value="0">
                    </td>
                    <td>
                        <input type="text" name="notes[]" class="form-control" placeholder="Catatan (Opsional)">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-row"><i class="bi bi-trash"></i></button>
                    </td>
                `;

                tableBody.appendChild(tr);
                bindRowEvents(tr);
            }

            // Mengikat event listener (seperti onchange / oninput) pada elemen-elemen di baris baru
            function bindRowEvents(row) {
                const serviceSelect = row.querySelector('.service-select');
                const qtyInput = row.querySelector('.qty-input');
                const btnRemove = row.querySelector('.btn-remove-row');

                // Update tampilan awal berdasarkan globalTaxToggle
                updateRowTaxDisplay(row);

                // Saat layanan dipilih, ambil harga dari atribut 'data-price' dan hitung subtotal
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;

                    row.querySelector('.price-input').value = formatRupiah(price);
                    calculateSubtotal(row);
                });

                // Saat qty diubah/diketik, langsung hitung ulang subtotal
                qtyInput.addEventListener('input', function() {
                    calculateSubtotal(row);
                });

                // Hapus baris dari tabel
                btnRemove.addEventListener('click', function() {
                    row.remove();
                    calculateGrandTotal();
                });
            }

            // Penjelasan: Rumus menghitung subtotal per baris
            function calculateSubtotal(row) {
                const serviceSelect = row.querySelector('.service-select');
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const price = selectedOption ? parseFloat(selectedOption.getAttribute('data-price') || 0) : 0;

                const qty = parseFloat(row.querySelector('.qty-input').value || 0);
                // Membulatkan subtotal ke ratusan Rupiah terdekat
                const subtotal = Math.round((price * qty) / 100) * 100;

                row.querySelector('.subtotal-input').value = formatRupiah(subtotal);
                calculateGrandTotal();
            }

            function updateRowTaxDisplay(row) {
                const taxValueDisplay = row.querySelector('.tax-value-display');

                if (!taxIsActive) {
                    taxValueDisplay.classList.remove('bg-transparent', 'text-dark', 'fw-bold');
                    taxValueDisplay.classList.add('bg-light', 'text-muted');
                } else {
                    taxValueDisplay.classList.remove('bg-light', 'text-muted');
                    taxValueDisplay.classList.add('bg-transparent', 'text-dark', 'fw-bold');
                }
            }

            // Penjelasan: Menjumlahkan seluruh subtotal di tabel menjadi Grand Total dan Pajak
            function calculateGrandTotal() {
                let subTotal = 0;
                let taxableSubTotal = 0;
                const rows = document.querySelectorAll('#serviceTableBody tr');
                const isGlobalTaxEnabled = taxIsActive;

                rows.forEach(row => {
                    const subtotalInput = row.querySelector('.subtotal-input');

                    if (subtotalInput) {
                        const val = subtotalInput.value.replace(/\./g, '');
                        const amount = parseFloat(val || 0);
                        subTotal += amount;

                        if (isGlobalTaxEnabled) {
                            taxableSubTotal += amount;
                        }
                    }
                });

                let taxAmount = Math.round((taxableSubTotal * taxPercentage) / 100);
                let grandTotalRaw = subTotal + taxAmount;
                let grandTotal = Math.round(grandTotalRaw / 100) * 100;

                subTotalDisplay.innerText = formatRupiah(subTotal);
                taxDisplay.innerText = formatRupiah(taxAmount);
                grandTotalDisplay.innerText = formatRupiah(grandTotal);

                // Update min value for payment
                const orderPayInput = document.getElementById('order_pay');
                if (orderPayInput) {
                    orderPayInput.min = grandTotal;
                    // Trigger input event to recalculate change if value exists
                    if (orderPayInput.value) {
                        orderPayInput.dispatchEvent(new Event('input'));
                    }
                }
            }

            // Format angka ke format Rupiah standar (contoh: 15.000)
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 0
                }).format(angka);
            }

            // --- Logika Pilihan Pembayaran ---
            const paymentAkhir = document.getElementById('payment_akhir');
            const paymentAwal = document.getElementById('payment_awal');
            const paymentFields = document.getElementById('paymentFields');
            const orderPayInput = document.getElementById('order_pay');
            const orderChangeInput = document.getElementById('order_change');

            function togglePaymentFields(isInit = false) {
                if (paymentAwal.checked) {
                    paymentFields.style.display = 'block';
                    orderPayInput.required = true;
                } else {
                    paymentFields.style.display = 'none';
                    orderPayInput.required = false;
                    if (!isInit) {
                        orderPayInput.value = '';
                        orderChangeInput.value = '0';
                        orderChangeInput.className = 'form-control bg-light text-dark';
                    }
                }
            }

            paymentAkhir.addEventListener('change', () => togglePaymentFields(false));
            paymentAwal.addEventListener('change', () => togglePaymentFields(false));

            // Inisialisasi saat load halaman
            togglePaymentFields(true);

            orderPayInput.addEventListener('input', function() {
                const payAmount = parseFloat(this.value || 0);

                // Hitung grand total yang ada di UI
                let subTotal = 0;
                document.querySelectorAll('.subtotal-input').forEach(input => {
                    subTotal += parseFloat(input.value.replace(/\./g, '') || 0);
                });
                let taxAmount = taxIsActive ? Math.round((subTotal * taxPercentage) / 100) : 0;
                let grandTotalRaw = subTotal + taxAmount;
                let grandTotal = Math.round(grandTotalRaw / 100) * 100;

                const changeAmount = payAmount - grandTotal;
                const btnSubmit = document.querySelector('#orderForm button[type="submit"]');

                if (!this.value) {
                    orderChangeInput.value = '0';
                    orderChangeInput.className = 'form-control bg-light text-dark';
                    this.setCustomValidity('');
                    if (btnSubmit) btnSubmit.disabled = false;
                    return;
                }

                if (changeAmount < 0) {
                    orderChangeInput.value = 'Uang Kurang';
                    orderChangeInput.className = 'form-control bg-light text-danger fw-bold';
                    this.setCustomValidity('Jumlah bayar tidak boleh kurang dari total tagihan.');
                    if (btnSubmit) btnSubmit.disabled = true;
                } else if (changeAmount === 0) {
                    orderChangeInput.value = '0';
                    orderChangeInput.className = 'form-control bg-light text-dark fw-bold';
                    this.setCustomValidity('');
                    if (btnSubmit) btnSubmit.disabled = false;
                } else {
                    orderChangeInput.value = formatRupiah(changeAmount);
                    orderChangeInput.className = 'form-control bg-light text-success fw-bold';
                    this.setCustomValidity('');
                    if (btnSubmit) btnSubmit.disabled = false;
                }
            });

            // Jalankan fungsi tambah baris saat tombol Tambah Layanan diklik
            btnTambahLayanan.addEventListener('click', addServiceRow);

            // Munculkan 1 baris layanan kosong secara default saat halaman dimuat
            addServiceRow();
        });
    </script>
@endsection
