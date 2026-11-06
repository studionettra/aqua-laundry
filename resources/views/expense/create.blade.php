@extends('layouts.app')
@section('title', 'Tambah Pengeluaran')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('expense.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expense_date">Tanggal Pengeluaran *</label>
                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category">Kategori *</label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Operasional" {{ old('category') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="Gaji" {{ old('category') == 'Gaji' ? 'selected' : '' }}>Gaji</option>
                            <option value="Bahan Baku" {{ old('category') == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                            <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="amount">Nominal (Rp) *</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min="0" step="any" value="{{ old('amount') }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description">Keterangan</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a href="{{ route('expense.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
