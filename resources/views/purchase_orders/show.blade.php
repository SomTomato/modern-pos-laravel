@extends('layouts.app')

@section('content')
{{-- The main page title is now separate from the action buttons --}}
<h1><i class="fa-solid fa-file-invoice"></i> Purchase Order #{{ $purchaseOrder->id }}</h1>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- THE FIX: Moved the 'Update Status' button inside the card header for better layout and context. --}}
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Order Summary</h2>
                <a href="{{ route('purchase_orders.edit', $purchaseOrder) }}" class="btn btn-primary"><i class="fa-solid fa-pencil-alt"></i> Update Status</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name ?? 'N/A' }}</p>
                        <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('F d, Y') }}</p>
                        <p><strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery_date ? \Carbon\Carbon::parse($purchaseOrder->expected_delivery_date)->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}</span></p>
                        <p><strong>Total Cost:</strong> ${{ number_format($purchaseOrder->total_cost, 2) }}</p>
                    </div>
                    @if($purchaseOrder->notes)
                    <div class="col-12">
                        <p><strong>Notes:</strong> {{ $purchaseOrder->notes }}</p>
                    </div>
                    @endif
                     @if($purchaseOrder->discrepancy_notes)
                    <div class="col-12">
                        <p><strong>Discrepancy Notes:</strong> <span class="text-danger">{{ $purchaseOrder->discrepancy_notes }}</span></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h2>Items on this Order</h2>
            </div>
            <div class="card-body">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align: center;">Quantity</th>
                            <th style="text-align: right;">Cost Price</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Product not found' }}</td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">${{ number_format($item->cost_price, 2) }}</td>
                            <td style="text-align: right;">${{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- The 'Back to List' button remains at the bottom for easy navigation. --}}
<div class="mt-3">
    <a href="{{ route('purchase_orders.index') }}" style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;"class="btn btn-secondary">Back to List</a>
</div>
@endsection
