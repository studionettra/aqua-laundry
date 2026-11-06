@extends('layouts.app')
@section('title', 'Tambah Customer Baru')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="">Nama Customer *</label>
                    <input type="text" class="form-control" placeholder="Masukkan Nama Customer" name="customer_name"
                        required value="{{ old('customer_name') }}">
                </div>
                <div class="mb-3">
                    <label for="">Nomor Telepon *</label>
                    <input type="text" class="form-control" placeholder="Masukkan Nomor Telepon" name="phone" required
                        value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label for="">Alamat *</label>
                    <textarea class="form-control" placeholder="Masukkan Alamat" name="address" rows="3" required>{{ old('address') }}</textarea>
                </div>

                <button class="btn btn-primary" type="submit">Tambah</button>
                <a href="{{ url()->previous() }}" class="text-secondary">Kembali</a>
            </form>
        </div>
    </div>

@endsection
