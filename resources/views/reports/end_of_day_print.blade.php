<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End of Day Report</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', sans-serif;
            color: #212529;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
        }
        .report-header h1 { margin: 0; font-size: 24px; }
        .report-header p { margin: 5px 0 0; color: #6c757d; }
        
        .summary-section {
            margin-bottom: 25px;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .summary-section h2 {
            font-size: 18px;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .summary-item {
            font-size: 16px;
        }
        .summary-item strong {
            display: block;
            color: #495057;
        }
        .summary-item span {
            font-size: 1.2em;
            font-weight: bold;
            color: #E67E22;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .details-table th, .details-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        .details-table th {
            background-color: #E67E22;
            color: white;
            font-weight: bold;
        }
        .details-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .print-controls {
            padding: 20px;
            text-align: center;
            background: #e9ecef;
            border-bottom: 1px solid #dee2e6;
        }
        .print-controls .btn {
            margin: 0 10px;
        }

        @media print {
            body { background-color: #fff; }
            .no-print { display: none !important; }
            .container {
                padding: 0;
                margin: 0;
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
                font-size: 10pt;
            }
            .details-table th {
                background-color: #E67E22 !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <div class="print-controls no-print">
        {{-- Link back to the main report page, passing the date --}}
        <a href="{{ route('reports.end_of_day', ['report_date' => $reportDate]) }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Report</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Print Now</button>
    </div>

    <div class="container">
        <div class="report-header">
            <h1>End of Day Report</h1>
            <p>For Date: {{ $date->format('F j, Y') }}</p>
        </div>

        <div class="summary-section">
            <h2>Sales Summary</h2>
            <div class="summary-grid">
                <div class="summary-item">
                    <strong>Total Sales Revenue</strong>
                    <span>${{ number_format($totalRevenue, 2) }}</span>
                </div>
                <div class="summary-item">
                    <strong>Total Transactions</strong>
                    <span>{{ $totalTransactions }}</span>
                </div>
            </div>
        </div>
        
        <div class="summary-section">
            <h2>Revenue by Payment Method</h2>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th style="text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentBreakdown as $method => $amount)
                        <tr>
                            <td>{{ ucfirst($method) }}</td>
                            <td style="text-align: right;">${{ number_format($amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center">No payment data for this day.</td></tr>
                    @endforelse
                    <tr style="font-weight: bold; border-top: 2px solid #dee2e6;">
                        <td><strong>Grand Total</strong></td>
                        <td style="text-align: right;"><strong>${{ number_format($totalRevenue, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <h2>Top 5 Selling Products</h2>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align: center;">Items Sold</th>
                        <th style="text-align: right;">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topSellingProducts as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $item->total_quantity }}</td>
                            <td style="text-align: right;">${{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No sales recorded for this date.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
