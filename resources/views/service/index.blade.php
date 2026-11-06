@extends('layouts.app')
@section('title', 'Daftar Layanan')
@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">
                {{ $title ?? '' }}
            </h3>
        </div> --}}
        <div class="card-body">
            <div class="card-tools mb-3" align="right">
                <a href="{{ route('service.create') }}" class="btn btn-primary">Tambah Layanan</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Layanan</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Deskripsi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $index => $service)
                            <tr>
                                <td class="text-center">{{ $index += 1 }}</td>
                                <td class="text-center">{{ $service->service_name }}</td>
                                <td class="text-center">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $service->description ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('service.edit', $service->id) }}" class="btn btn-success icon">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('service.destroy', $service->id) }}" class="btn btn-danger"
                                        data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
