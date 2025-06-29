@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-truck-field"></i> Supplier Details</h1>

<div class="card">
    {{-- THE FIX: Moved the 'Edit' button inside the card header for the correct layout. --}}
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>{{ $supplier->name }}</h2>
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary"><i class="fa-solid fa-pencil-alt"></i> Edit Supplier</a>
    </div>
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width: 30%;">Contact Person:</th>
                <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Email Address:</th>
                <td>{{ $supplier->email }}</td>
            </tr>
            <tr>
                <th>Phone Number:</th>
                <td>{{ $supplier->phone_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Location / Address:</th>
                <td>{{ $supplier->address ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- THE FIX: Moved the 'Back' button to the bottom and ensured it is styled correctly. --}}
<div class="mt-3">
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary"style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;">Back to List</a>
</div>
@endsection
