@extends('layouts.app')
@section('title', 'Data Pengeluaran')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-tools mb-3" align="right">
                <a href="{{ route('expense.create') }}" class="btn btn-primary">Tambah Pengeluaran</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="table1">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">Nominal (Rp)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $index => $expense)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                <td class="text-center">{{ $expense->category }}</td>
                                <td>{{ $expense->description }}</td>
                                <td class="text-end">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('expense.edit', $expense->id) }}" class="btn btn-warning icon" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('expense.destroy', $expense->id) }}" class="btn btn-danger icon" data-confirm-delete="true" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
