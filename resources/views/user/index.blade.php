@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">
                {{ $title ?? '' }}
            </h3>
        </div> --}}
        <div class="card-body">
            @if (auth()->id() === 1)
                <div class="mb-3" align="right">
                    <a href="{{ route('user.create') }}" class="btn btn-primary icon">
                        Tambah User</a>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Level</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ $index += 1 }}</td>
                            <td class="text-center">{{ $user->name ?? '' }}</td>
                            <td class="text-center">{{ $user->email ?? '' }}</td>
                            <td class="text-center">{{ $user->level->level_name ?? '' }}</td>
                            <td class="text-center">
                                @if ($user->id !== 1 || auth()->id() === 1)
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success icon">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                                @if ($user->id !== 1)
                                    <a href="{{ route('user.destroy', $user->id) }}" class="btn btn-danger icon"
                                        data-confirm-delete="true" title="Delete">
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
    {{--  <script>
        document.addEventListener('click', function(e) {

            const button = e.target.closest('.btn-delete');

            if (button) {
                e.preventDefault();
                const form = button.closest('.form-delete');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data user yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    </script>  --}}
@endsection
