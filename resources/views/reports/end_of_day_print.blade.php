@extends('layouts.print')

@section('title', 'End of Day Report')

@section('content')
<div class="printable-area">
    <div class="print-header">
        <h1>End of Day Summary</h1>
        <p>Date: <strong>{{ \Carbon\Carbon::parse($reportDate)->format('F j, Y') }}</strong></p>
    </div>

    {{-- Payment Breakdown Section --}}
    <div class="summary-section" style="margin-bottom: 30px;">
        <h3>Revenue by Payment Method</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th style="text-align: right;">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @forelse($paymentBreakdown as $method => $amount)
                    @php $grandTotal += $amount; @endphp
                    <tr>
                        <td>{{ ucfirst($method) }}</td>
                        <td style="text-align: right;">${{ number_format($amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2">No payment data for this day.</td></tr>
                @endforelse
                <tr style="font-weight: bold; border-top: 2px solid #000;">
                    <td>Grand Total Revenue</td>
                    <td style="text-align: right;">${{ number_format($grandTotal, 2) }}</td>
                </tr>
                 <tr style="font-weight: bold;">
                    <td>Total Sales Count</td>
                    <td style="text-align: right;">{{ $totalSales }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    {{-- Detailed Transaction List --}}
    <div class="details-section">
        <h3>All Transactions</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Sale ID</th>
                    <th>Cashier</th>
                    <th>Customer</th>
                    <th style="text-align: right;">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('h:i:s A') }}</td>
                        <td>#{{ $sale->id }}</td>
                        <td>{{ $sale->user->username ?? 'N/A' }}</td>
                        <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                        <td style="text-align: right;">${{ number_format($sale->total_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">No sales were recorded on this day.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
