<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $sale->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background-color: #eee; padding: 20px; }
        @media print {
            body { background-color: #fff; padding: 0; }
            .no-print { display: none; }
            .invoice-box { box-shadow: none; border: none; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title"><h2>Modern POS</h2></td>
                            <td>
                                <strong>Invoice #:</strong> {{ $sale->id }}<br>
                                {{-- THE FIX: Check if created_at exists before formatting it --}}
                                <strong>Date:</strong> {{ $sale->created_at ? $sale->created_at->format('Y-m-d h:i A') : 'N/A' }}<br>
                                <strong>Cashier:</strong> {{ $sale->user->username ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>BILLED TO:</strong><br>
                                {{ $sale->customer->name ?? 'N/A' }}<br>
                                @if($sale->customer && $sale->customer->phone_number)
                                    {{ $sale->customer->phone_number }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Item</td>
                <td style="text-align: center;">Quantity</td>
                <td style="text-align: right;">Price/Unit</td>
                <td style="text-align: right;">Total</td>
            </tr>
            @foreach ($sale->items as $item)
            <tr class="item">
                <td>{{ $item->product->name ?? 'N/A' }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="text-align: right;">${{ number_format($item->price_per_unit, 2) }}</td>
                <td style="text-align: right;">${{ number_format($item->quantity * $item->price_per_unit, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="3" style="text-align: right; font-weight: bold;">Grand Total:</td>
                <td style="text-align: right; font-weight: bold;">${{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            <tr class="heading">
                <td>Payment Method</td>
                <td colspan="3" style="text-align: right;">Details</td>
            </tr>
            <tr class="details">
                <td>{{ ucfirst($sale->payment_method) }}</td>
                <td colspan="3" style="text-align: right;">{{ $sale->payment_provider ?? '' }}</td>
            </tr>
        </table>
    </div>
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary"><i class="fa fa-print"></i> Print Invoice</button>
        <a href="{{ route('pos.terminal') }}" class="btn" style="background-color: #555;">New Sale</a>
    </div>
</body>
</html>
