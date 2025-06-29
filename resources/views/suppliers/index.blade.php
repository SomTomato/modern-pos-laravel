@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-truck"></i> Supplier Management</h1>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>All Suppliers</h2>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Supplier</a>
    </div>
    <div class="card-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Location</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone_number ?? 'N/A' }}</td>
                        <td>{{ $supplier->address ?? 'N/A' }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; justify-content: center; gap: 5px;">
                                {{-- THE FIX: Corrected the typo from 'rounte' to 'route' and enabled the button --}}
                                <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{ route('suppliers.edit', $supplier) }}" 
                                   class="btn btn-sm" 
                                   style="color: #212529; background-color: #ffc107; border-color: #ffc107; text-decoration: none;">Edit</a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No suppliers have been added yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
