@extends('layouts.app')
@section('title', 'Manajemen Pajak')
@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">
                {{ $title ?? '' }}
            </h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('tax.update', $tax->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="tax_percentage">Jumlah Pajak PPN (%) *</label>
                    <input type="number" name="tax_percentage" id="tax_percentage" class="form-control"
                        value="{{ $tax->tax_percentage }}" required min="0" max="100" {{ $tax->is_active ? '' : 'readonly' }}>
                    <small class="text-muted" id="tax_percentage_help">Masukkan angka saja, contoh: 11</small>
                </div>
                <div class="mb-4 form-check form-switch fs-6">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ $tax->is_active ? 'checked' : '' }} style="cursor: pointer;">
                    <label class="form-check-label fw-bold {{ $tax->is_active ? 'text-success' : 'text-secondary' }}" for="is_active" id="tax_status_label" style="cursor: pointer;">
                        {{ $tax->is_active ? 'Status: Pajak Aktif' : 'Status: Pajak Nonaktif' }}
                    </label>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary mt-2" style="font-size: 13px;"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isActiveToggle = document.getElementById('is_active');
            const taxStatusLabel = document.getElementById('tax_status_label');
            const taxPercentageInput = document.getElementById('tax_percentage');
            const taxPercentageHelp = document.getElementById('tax_percentage_help');

            function updateUI() {
                if (isActiveToggle.checked) {
                    taxStatusLabel.innerText = 'Status: Pajak Aktif';
                    taxStatusLabel.classList.remove('text-secondary', 'text-danger');
                    taxStatusLabel.classList.add('text-success');
                    
                    taxPercentageInput.readOnly = false;
                    taxPercentageInput.classList.remove('bg-light');
                    taxPercentageHelp.innerText = 'Masukkan angka saja, contoh: 11';
                } else {
                    taxStatusLabel.innerText = 'Status: Pajak Nonaktif';
                    taxStatusLabel.classList.remove('text-success');
                    taxStatusLabel.classList.add('text-secondary');
                    
                    taxPercentageInput.readOnly = true;
                    taxPercentageInput.classList.add('bg-light');
                    taxPercentageHelp.innerText = 'Pajak sedang nonaktif, persentase diabaikan.';
                }
            }

            // Inisialisasi awal UI saat load
            updateUI();

            // Dengarkan perubahan pada toggle
            isActiveToggle.addEventListener('change', updateUI);
        });
    </script>
@endsection
