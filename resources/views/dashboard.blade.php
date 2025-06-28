@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1><i class="fa-solid fa-chart-pie"></i> Dashboard</h1>

    <div class="stats-grid">
        <div class="stat-card products">
            <div class="icon"><i class="fa-solid fa-box-archive"></i></div>
            <div class="info">
                <h3>Total Products</h3>
                <p>{{ $total_products }}</p>
            </div>
        </div>
        <div class="stat-card sales">
            <div class="icon"><i class="fa-solid fa-cash-register"></i></div>
            <div class="info">
                <h3>Sales Today</h3>
                <p>{{ $sales_today_count }}</p>
            </div>
        </div>
        <div class="stat-card revenue">
            <div class="icon"><i class="fa-solid fa-dollar-sign"></i></div>
            <div class="info">
                <h3>Revenue Today</h3>
                <p>${{ number_format($revenue_today, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 30px; height: 400px;">
        <div class="card-header">
            <h2>Sales Trend (Last 7 Days)</h2>
        </div>
        <canvas id="salesChart"></canvas>
    </div>

    <div id="chartData"
         data-labels='@json($chart_labels)'
         data-values='@json($chart_values)'>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush