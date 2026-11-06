@extends('layouts.app')
@section('title', 'Manajemen Customer')
@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">
                {{ $title ?? '' }}
            </h3>
        </div> --}}
        <div class="card-body">
            <div class="card-tools mb-3" align="right">
                <a href="{{ route('customer.create') }}" class="btn btn-primary">Tambah Customer</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Customer</th>
                            <th class="text-center">Nomor Telepon</th>
                            <th class="text-center">Alamat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index => $customer)
                            <tr>
                                <td class="text-center">{{ $index += 1 }}</td>
                                <td class="text-center">{{ $customer->customer_name }}</td>
                                <td class="text-center">{{ $customer->phone }}</td>
                                <td class="text-center">{{ $customer->address }}</td>
                                <td class="text-center">
                                    <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-success icon">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('customer.destroy', $customer->id) }}" class="btn btn-danger"
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
