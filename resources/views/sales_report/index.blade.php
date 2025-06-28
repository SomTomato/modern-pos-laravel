@extends('layouts.app')

@section('content')
<h1><i class="fa-solid fa-file-invoice-dollar"></i> Sales Report</h1>

<div class="card">
    <div class="card-header">
        <h2>Filter Sales</h2>
    </div>
    <div class="card-body">
        <form method="get" action="{{ route('sales.report') }}" class="filter-form">
            <div class="row align-items-end">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Sales from {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</h3>
        <p class="mb-0"><strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="card-body">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Sale ID</th>
                    <th>Cashier</th>
                    <th>Customer</th>
                    <th style="text-align: right;">Total</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale->created_at->format('M d, Y, h:i A') }}</td>
                        <td>#{{ $sale->id }}</td>
                        <td>{{ $sale->user->username ?? 'N/A' }}</td>
                        <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>
                        <td style="text-align: right;">${{ number_format($sale->total_amount, 2) }}</td>
                        <td style="text-align: center;">
                            {{-- THE FIX: Removed target="_blank" and added a unique ID --}}
                            <a href="{{ route('invoice.show', $sale) }}" class="btn btn-primary btn-sm" id="invoice-link-{{ $sale->id }}">View Invoice</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No sales found for the selected date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
         <div class="mt-3">
            {{ $sales->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- THE FIX: Script to pass dark mode status to all invoice links --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.body.classList.contains('dark-mode')) {
            const invoiceLinks = document.querySelectorAll('a[id^="invoice-link-"]');
            invoiceLinks.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('dark_mode', 'true');
                link.href = url.toString();
            });
        }
    });
</script>
@endpush
