@extends('layouts.app')
@section('title', 'Tambah User Baru')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="">Level *</label>
                    <select name="id_level" id="" class="form-control" required>
                        <option value="">Pilih Salah Satu</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level->id }}">{{ $level->level_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Nama *</label>
                    <input type="text" class="form-control" placeholder="Masukkan Nama Anda" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="">Email *</label>
                    <input type="email" class="form-control" placeholder="Masukkan Email Anda" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="">Password *</label>
                    <input type="password" class="form-control" placeholder="Masukkan Password Anda" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Tambah</button>
                <a href="{{ url()->previous() }}" class="text-secondary">Kembali</a>
            </form> 
        </div>
    </div>

@endsection
