@extends('layouts.app')

@section('styles')
<style>
    .dark-mode .form-control { background-color: #2d3748; color: #e2e8f0; border-color: #4a5568; }
    .dark-mode .form-control:focus { background-color: #2d3748; color: #e2e8f0; border-color: #805ad5; box-shadow: none; }
    .dark-mode .form-group label { color: #a0aec0; }
</style>
@endsection

@section('content')
<h1><i class="fa-solid fa-pencil-alt"></i> Edit Supplier</h1>

<div class="card">
    <div class="card-header">
        <h2>Update Details for: {{ $supplier->name }}</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Supplier Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label for="email">Email</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $supplier->email) }}" required>@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label for="phone_number">Phone</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $supplier->phone_number) }}"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea name="address" class="form-control" rows="3">{{ old('address', $supplier->address) }}</textarea>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Supplier</button>
                <a href="{{ route('suppliers.index') }}" 
                   style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;">
                   Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
