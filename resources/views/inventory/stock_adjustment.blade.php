@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-right-left"></i> Stock Adjustment</h1>

<div class="card no-print">
    <div class="card-header">
        <h2>Adjust Product Stock</h2>
    </div>
    <form method="post" action="{{ route('inventory.process_adjustment') }}">
        @csrf
        <div class="form-group">
            <label for="product_id">Select Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">-- Choose a product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-image="{{ $product->image }}">
                        {{ $product->name }} (Current Stock: {{ $product->quantity }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="image-preview-container" id="product-image-preview-container" style="display: none; justify-content: flex-start; height: auto; margin-bottom: 20px;">
             <img id="product-image-preview" src="#" alt="Product Image" class="table-image"/>
        </div>
        <div class="form-group">
            <label for="adjustment_type">Adjustment Type</label>
            <select name="adjustment_type" id="adjustment_type" class="form-control" required>
                <option value="add">Add to Stock</option>
                <option value="remove">Remove from Stock</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason for Adjustment</label>
            <input type="text" name="reason" id="reason" class="form-control" placeholder="e.g., New shipment, Damaged goods">
        </div>
        <button type="submit" class="btn btn-primary">Submit Adjustment</button>
    </form>
</div>

<div class="card no-print">
    <div class="card-header">
        <h2>Recent Adjustment Log</h2>
    </div>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Adjusted By</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($adjustments as $adj)
            <tr>
                <td>{{ $adj->created_at->format("Y-m-d H:i") }}</td>
                <td>{{ $adj->product->name ?? 'N/A' }}</td>
                <td>
                    @if ($adj->adjustment_type == 'add')
                        <span class="status-badge active" style="background-color: #3498db;">Added</span>
                    @else
                        <span class="status-badge disabled" style="background-color: var(--warning-color);">Removed</span>
                    @endif
                </td>
                <td>{{ $adj->quantity_changed }}</td>
                <td>{{ $adj->reason }}</td>
                <td>{{ $adj->user->username ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr><td colspan="6">No stock adjustments have been recorded yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const imagePreviewContainer = document.getElementById('product-image-preview-container');
    const imagePreview = document.getElementById('product-image-preview');
    if(productSelect) {
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const imageUrl = selectedOption.dataset.image;
            if (imageUrl && imageUrl !== 'default.png') {
                imagePreview.src = `/storage/products/${imageUrl}`;
                imagePreviewContainer.style.display = 'flex';
            } else {
                imagePreviewContainer.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
@endsection
