@extends('layouts.app')

@section('styles')
<style>
    .dark-mode .form-control { background-color: #2d3748; color: #e2e8f0; border-color: #4a5568; }
    .dark-mode .form-control:focus { background-color: #2d3748; color: #e2e8f0; border-color: #805ad5; box-shadow: none; }
    .dark-mode .form-group label { color: #a0aec0; }
</style>
@endsection

@section('content')
<h1><i class="fa-solid fa-truck-loading"></i> Update Purchase Order #{{ $purchaseOrder->id }}</h1>

<div class="card">
    <div class="card-header">
        <h2>Update Status & Notes</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('purchase_orders.update', $purchaseOrder) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="status">Order Status</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ $purchaseOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="received" {{ $purchaseOrder->status == 'received' ? 'selected' : '' }}>Received (All Items OK)</option>
                    <option value="received_with_discrepancy" {{ $purchaseOrder->status == 'received_with_discrepancy' ? 'selected' : '' }}>Received (With Discrepancy)</option>
                    <option value="cancelled" {{ $purchaseOrder->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <small class="form-text text-muted">Setting the status to 'Received' will automatically add the item quantities to your product stock.</small>
            </div>

            <div class="form-group">
                <label for="discrepancy_notes">Discrepancy Notes (Over/Missing/Damaged)</label>
                <textarea name="discrepancy_notes" class="form-control" rows="4">{{ old('discrepancy_notes', $purchaseOrder->discrepancy_notes) }}</textarea>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update Purchase Order</button>
                <a href="{{ route('purchase_orders.show', $purchaseOrder) }}" style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; cursor: pointer; user-select: none; background-color: #6c757d; border: 1px solid #6c757d; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none;"class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
