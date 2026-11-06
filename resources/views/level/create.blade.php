@extends('layouts.app')
@section('title', 'Tambah Level Baru')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('level.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="">Nama Level *</label>
                    <input type="text" class="form-control" placeholder="Masukkan Nama Level" name="level_name" required>
                </div>
                <button class="btn btn-primary" type="submit">Tambah</button>
                <a href="{{ url()->previous() }}" class="text-secondary">Kembali</a>
            </form>
        </div>
    </div>

@endsection
