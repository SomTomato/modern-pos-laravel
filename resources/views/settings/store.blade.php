@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-store"></i> Store Settings</h1>

{{-- The alert partial is already in the main layout and will show messages automatically --}}

<div class="card">
    <div class="card-header">
        <h2>General Information</h2>
    </div>
    {{-- THE FIX: Points to the new 'updateGeneral' route --}}
    <form method="post" action="{{ route('settings.general.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="store_name">Store Name</label>
            <input type="text" name="store_name" id="store_name" class="form-control" value="{{ $settings['store_name'] ?? 'Modern POS' }}" required>
        </div>
        <div class="form-group">
            <label for="store_address">Store Address</label>
            <textarea name="store_address" id="store_address" class="form-control" rows="3">{{ $settings['store_address'] ?? '123 Main Street, Anytown, USA' }}</textarea>
        </div>
        <div class="form-group">
            <label for="store_phone">Store Phone Number</label>
            <input type="text" name="store_phone" id="store_phone" class="form-control" value="{{ $settings['store_phone'] ?? '+1 (555) 123-4567' }}">
        </div>
        <div class="form-group">
            <label for="store_logo">Store Logo (for receipts, leave empty to keep current)</label>
            <input type="file" name="store_logo" id="store_logo" class="form-control" accept="image/*">
            @if(isset($settings['store_logo']))
            <div style="margin-top: 15px;">
                <p>Current Logo:</p>
                <img src="{{ asset('storage/' . $settings['store_logo']) }}" alt="Current Logo" style="max-width: 150px; background: #f0f0f0; padding: 10px; border-radius: 5px;">
            </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Save General Settings</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Financial Settings</h2>
    </div>
    {{-- THE FIX: Points to the new 'updateFinancial' route --}}
    <form method="post" action="{{ route('settings.financial.update') }}">
        @csrf
        <div class="form-group">
            <label for="currency_symbol">Currency Symbol</label>
            <input type="text" name="currency_symbol" id="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '$' }}" style="width: 100px;">
        </div>
        <div class="form-group">
            <label for="tax_rate">Default Tax Rate (%)</label>
            <input type="number" step="0.01" name="tax_rate" id="tax_rate" class="form-control" value="{{ $settings['tax_rate'] ?? '0.00' }}" style="width: 150px;">
        </div>
        <button type="submit" class="btn btn-primary">Save Financial Settings</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Receipt Settings</h2>
    </div>
    {{-- THE FIX: Points to the new 'updateReceipt' route --}}
    <form method="post" action="{{ route('settings.receipt.update') }}">
        @csrf
        <div class="form-group">
            <label for="receipt_footer">Receipt Footer Text</label>
            <textarea name="receipt_footer" id="receipt_footer" class="form-control" rows="4" placeholder="e.g., Thank you for your business! All sales are final.">{{ $settings['receipt_footer'] ?? 'Thank you for shopping at Modern POS!' }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save Receipt Settings</button>
    </form>
</div>
@endsection
