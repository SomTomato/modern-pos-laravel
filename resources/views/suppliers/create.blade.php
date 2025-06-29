@extends('layouts.app')

@section('styles')
<style>
    .dark-mode .form-control { background-color: #2d3748; color: #e2e8f0; border-color: #4a5568; }
    .dark-mode .form-control:focus { background-color: #2d3748; color: #e2e8f0; border-color: #805ad5; box-shadow: none; }
    .dark-mode .form-group label { color: #a0aec0; }
</style>
@endsection

@section('content')
<h1><i class="fa-solid fa-plus"></i> Add New Supplier</h1>

<div class="card">
    <div class="card-header">
        <h2>Supplier Details</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Supplier Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person') }}">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label for="email">Email</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>@error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror</div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label for="phone_number">Phone</label><input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="address">Location (Province)</label>
                @php
                    $provinces = [
                        'Banteay Meanchey', 'Battambang', 'Kampong Cham', 'Kampong Chhnang', 'Kampong Speu', 
                        'Kampong Thom', 'Kampot', 'Kandal', 'Koh Kong', 'Kratie', 'Mondulkiri', 
                        'Oddar Meanchey', 'Pailin', 'Phnom Penh', 'Preah Sihanouk', 'Preah Vihear', 
                        'Prey Veng', 'Pursat', 'Ratanakiri', 'Siem Reap', 'Stung Treng', 'Svay Rieng', 
                        'Takeo', 'Tboung Khmum', 'Kep'
                    ];
                @endphp
                <select name="address" class="form-control">
                    <option value="">Select a Province</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" {{ old('address') == $province ? 'selected' : '' }}>{{ $province }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Supplier</button>
                {{-- THE FIX: Added robust inline styles to force the 'Cancel' link to appear as a button --}}
                <a href="{{ route('suppliers.index') }}" 
                   style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;">
                   Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
