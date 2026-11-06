@extends('layouts.app')
@section('title', 'Tambah Layanan Baru')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('service.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="">Nama Layanan *</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama layanan (contoh: Cuci Kering)"
                        name="service_name" required value="{{ old('service_name') }}">
                </div>
                <div class="mb-3">
                    <label for="">Harga (Rp) *</label>
                    <input type="number" class="form-control" placeholder="Masukkan harga" name="price" required
                        value="{{ old('price') }}">
                </div>
                <div class="mb-3">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" placeholder="Masukkan deskripsi layanan (opsional)" name="description" rows="3">{{ old('description') }}</textarea>
                </div>

                <button class="btn btn-primary" type="submit">Tambah</button>
                <a href="{{ url()->previous() }}" class="text-secondary">Kembali</a>
            </form>
        </div>
    </div>

@endsection
