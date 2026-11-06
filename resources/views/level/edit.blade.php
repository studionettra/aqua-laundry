@extends('layouts.app')
@section('title', 'Edit Level')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('level.update', $edit->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="">Level Name *</label>
                    <input type="text" class="form-control" placeholder="Enter level name" name="level_name" required
                        value="{{ $edit->level_name }}">
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ url()->previous() }}" class="text-secondary">Back</a>
            </form>
        </div>
    </div>

@endsection
