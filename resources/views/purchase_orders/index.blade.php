@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-receipt"></i> Purchase Orders</h1>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>All Purchase Orders</h2>
        <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Purchase Order</a>
    </div>
    <div class="card-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>PO #</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Total Cost</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchaseOrders as $po)
                    <tr>
                        <td>#{{ $po->id }}</td>
                        <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($po->order_date)->format('M d, Y') }}</td>
                        <td style="text-align: center;">
                            @php
                                $statusClass = 'secondary';
                                if ($po->status === 'received') $statusClass = 'success';
                                if ($po->status === 'pending') $statusClass = 'warning';
                                if ($po->status === 'cancelled') $statusClass = 'danger';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $po->status)) }}</span>
                        </td>
                        <td style="text-align: right;">${{ number_format($po->total_cost, 2) }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('purchase_orders.show', $po) }}" class="btn btn-primary btn-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No purchase orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>
@endsection
