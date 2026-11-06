@extends('layouts.app')
@section('title', 'Edit Layanan')

@section('content')
    <div class="card">
        {{-- <div class="card-header">
            <h3 class="card-title">{{ $title ?? '' }}</h3>
        </div> --}}
        <div class="card-body">
            <form action="{{ route('service.update', $edit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="">Service Name *</label>
                    <input type="text" class="form-control" placeholder="Enter service name" name="service_name" required
                        value="{{ old('service_name', $edit->service_name) }}">
                </div>
                <div class="mb-3">
                    <label for="">Price (Rp) *</label>
                    <input type="number" class="form-control" placeholder="Enter price" name="price" required
                        value="{{ old('price', $edit->price) }}">
                </div>
                <div class="mb-3">
                    <label for="">Description</label>
                    <textarea class="form-control" placeholder="Enter service description (optional)" name="description" rows="3">{{ old('description', $edit->description) }}</textarea>
                </div>

                <button class="btn btn-primary" type="submit">Save</button>
                <a href="{{ url()->previous() }}" class="text-secondary">Back</a>
            </form>
        </div>
    </div>

@endsection
