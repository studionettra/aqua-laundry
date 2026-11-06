@extends('layouts.app')
@section('title', 'Edit Pengeluaran')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('expense.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expense_date">Tanggal Pengeluaran *</label>
                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category">Kategori *</label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Operasional" {{ old('category', $expense->category) == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                            <option value="Gaji" {{ old('category', $expense->category) == 'Gaji' ? 'selected' : '' }}>Gaji</option>
                            <option value="Bahan Baku" {{ old('category', $expense->category) == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                            <option value="Lainnya" {{ old('category', $expense->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="amount">Nominal (Rp) *</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min="0" step="any" value="{{ old('amount', $expense->amount) }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="description">Keterangan</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $expense->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a href="{{ route('expense.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
