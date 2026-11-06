@extends('layouts.app')
@section('title', 'Manajemen Level')
@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">
                {{ $title ?? '' }}
            </h3>
        </div> --}}
        <div class="card-body">
            <div class="mb-3" align="right">
                <a href="{{ route('level.create') }}" class="btn btn-primary">Tambah Level</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center w-10">No</th>
                        <th class="text-center w-50">Nama Level</th>
                        <th class="text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($levels as $index => $level)
                        <tr>
                            <td class="text-center w-10">{{ $index += 1 }}</td>
                            <td class="text-center w-50">{{ $level->level_name ?? '' }}</td>
                            <td class="text-center w-40 d-flex justify-content-center align-items-center gap-2">
                                <a href="{{ route('level.edit', $level->id) }}" class="btn btn-success icon">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                @if (auth()->user()->id_level == $level->id || auth()->user()->level->level_name === $level->level_name || strtolower($level->level_name) === 'administrator')
                                    <button class="btn btn-secondary disabled" title="Level inti atau sedang Anda gunakan" disabled>
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @else
                                    <a href="{{ route('level.destroy', $level->id) }}" class="btn btn-danger"
                                        data-confirm-delete="true">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
