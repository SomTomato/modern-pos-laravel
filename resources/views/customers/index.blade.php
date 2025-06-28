@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-users"></i> Customer Management</h1>

{{-- REMOVED old alert messages here. The new partial handles them. --}}

<div class="card">
    <div class="card-header">
        <h2>Add New Customer</h2>
    </div>
    <form method="post" action="{{ route('customers.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Customer Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Customer</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Registered Customers</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date Registered</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone_number }}</td>
                <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                <td style="text-align: right;">
                    @if($customer->id != 1)
                        <form method="POST" action="{{ route('customers.destroy', $customer) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this customer? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No customers have been registered yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
