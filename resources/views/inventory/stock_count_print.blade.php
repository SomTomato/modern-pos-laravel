@extends('layouts.print')

@section('title', 'Stock-take Sheet')

@section('content')
<div>
    <div class="print-header">
        <h1>Stock-take Sheet</h1>
        <p>Date Printed: {{ now()->format('F j, Y') }}</p>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Product</th>
                <th style="text-align: center;">System Qty</th>
                <th class="physical-count-col" style="text-align:center;">Physical Count</th>
                <th style="width: 80px; text-align:center;">Verified</th>
                <th style="width: 80px; text-align:center;">Over</th>
                <th style="width: 80px; text-align:center;">Missing</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stockLevels as $product)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img class="table-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
                            <div>
                                <strong>{{ $product->name }}</strong><br>
                                <small>SKU: {{ $product->sku }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center; font-weight: bold; font-size: 1.2em;">
                        {{ $product->quantity }}
                    </td>
                    <td class="writable"></td>
                    <td style="text-align: center;"></td>
                    <td class="writable"></td>
                    <td class="writable"></td>
                </tr>
            @empty
                <tr><td colspan="6">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
