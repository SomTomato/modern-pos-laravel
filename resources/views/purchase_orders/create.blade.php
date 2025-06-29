@extends('layouts.app')

@section('styles')
<style>
    /* Add some specific styling for the PO form */
    .dark-mode .form-control { background-color: #2d3748; color: #e2e8f0; border-color: #4a5568; }
    .dark-mode .form-control:focus { background-color: #2d3748; color: #e2e8f0; border-color: #805ad5; box-shadow: none; }
    .dark-mode .form-group label { color: #a0aec0; }
    .item-row .form-control { font-size: 0.9rem; }
    .remove-item-btn { cursor: pointer; }
</style>
@endsection

@section('content')
<h1><i class="fa-solid fa-plus"></i> Create New Purchase Order</h1>

<form action="{{ route('purchase_orders.store') }}" method="POST">
    @csrf
    <div class="row">
        {{-- Main PO Details --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2>Order Details</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier_id">Supplier</label>
                                <select name="supplier_id" class="form-control" required>
                                    <option value="">-- Select Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order_date">Order Date</label>
                                <input type="date" name="order_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label for="expected_delivery_date">Expected Delivery (Optional)</label>
                                <input type="date" name="expected_delivery_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h2>Order Items</h2>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Product</th>
                                <th style="width: 15%;">Quantity</th>
                                <th style="width: 20%;">Cost Price (per item)</th>
                                <th style="width: 20%;">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body">
                           {{-- Rows will be added dynamically by JavaScript --}}
                        </tbody>
                    </table>
                    <button type="button" id="add-item-btn" class="btn btn-success"><i class="fa fa-plus"></i> Add Item</button>
                </div>
            </div>
        </div>

        {{-- Totals and Notes --}}
        <div class="col-12 mt-4">
             <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notes">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <div>
                                <h3>Total Cost: <span id="grand-total">$0.00</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Create Purchase Order</button>
        <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

{{-- Hidden template for a new item row --}}
<template id="item-row-template">
    <tr class="item-row">
        <td>
            <select name="items[][product_id]" class="form-control product-select" required>
                <option value="">-- Select a Product --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (SKU: {{ $product->sku }})</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="items[][quantity]" class="form-control quantity-input" value="1" min="1" required></td>
        <td><input type="number" step="0.01" name="items[][cost_price]" class="form-control cost-price-input" value="0.00" min="0" required></td>
        <td class="subtotal-cell">$0.00</td>
        <td><i class="fa-solid fa-trash-alt text-danger remove-item-btn"></i></td>
    </tr>
</template>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsTableBody = document.getElementById('items-table-body');
    const itemRowTemplate = document.getElementById('item-row-template');
    let itemIndex = 0;

    function addRow() {
        const newRow = itemRowTemplate.content.cloneNode(true);
        const rowElement = newRow.querySelector('.item-row');
        
        // Update name attributes to be unique
        rowElement.querySelector('.product-select').name = `items[${itemIndex}][product_id]`;
        rowElement.querySelector('.quantity-input').name = `items[${itemIndex}][quantity]`;
        rowElement.querySelector('.cost-price-input').name = `items[${itemIndex}][cost_price]`;

        itemsTableBody.appendChild(newRow);
        itemIndex++;
        updateEventListeners();
    }

    function updateEventListeners() {
        // Remove item button
        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.onclick = (e) => e.target.closest('.item-row').remove();
        });
        
        // Recalculate on input change
        document.querySelectorAll('.quantity-input, .cost-price-input').forEach(input => {
            input.oninput = calculateTotals;
        });
    }

    function calculateTotals() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const costPrice = parseFloat(row.querySelector('.cost-price-input').value) || 0;
            const subtotal = quantity * costPrice;
            row.querySelector('.subtotal-cell').textContent = '$' + subtotal.toFixed(2);
            grandTotal += subtotal;
        });
        document.getElementById('grand-total').textContent = '$' + grandTotal.toFixed(2);
    }
    
    addItemBtn.addEventListener('click', addRow);
    
    // Add one row to start with
    addRow();
});
</script>
@endpush
