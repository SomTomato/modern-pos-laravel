<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Count Worksheet</title>
    {{-- Link to your app's CSS for consistent button styling --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* Basic styles for the print page */
        body {
            background-color: #f4f6f9; /* A light grey for the background on screen */
            color: #212529;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; /* White background for the content area */
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
        }
        .print-header h1 { margin: 0; font-size: 24px; }
        .print-header p { margin: 5px 0 0; color: #6c757d; }

        /* Table styles for printing */
        .print-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .print-table th, .print-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }
        /* THE FIX: Changed the background color to the theme's orange */
        .print-table th {
            background-color: #E67E22;
            color: white;
            font-weight: bold;
        }
        .print-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .print-table .table-image {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        .blank-cell {
             height: 35px;
        }

        /* Controls for the print view page */
        .print-controls {
            padding: 20px;
            text-align: center;
            background: #e9ecef;
            border-bottom: 1px solid #dee2e6;
        }
        .print-controls .btn {
            margin: 0 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* This is the magic: hide controls when printing */
        @media print {
            body {
                background-color: #fff; /* Ensure print background is white */
            }
            .no-print {
                display: none !important;
            }
            .container {
                padding: 0;
                margin: 0;
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
                font-size: 10pt;
            }
            .print-table {
                font-size: 10pt;
            }
             /* THE FIX: Ensured the orange header color prints correctly */
            .print-table th {
                background-color: #E67E22 !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    {{-- Controls are inside a "no-print" div --}}
    <div class="print-controls no-print">
        <a href="{{ route('inventory.stock_count') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Stock Count</a>
        <button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print"></i> Print Now</button>
    </div>

    <div class="container">
        <div class="print-header">
            <h1>Stock Count Worksheet</h1>
            <p>Printed on: {{ now()->format('F j, Y, g:i a') }}</p>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Image</th>
                    <th style="width: 35%;">Product (SKU)</th>
                    <th style="text-align: center; width: 10%;">System Qty</th>
                    <th style="text-align: center; width: 10%;">Physical Count</th>
                    <th style="text-align: center; width: 10%;">Over</th>
                    <th style="text-align: center; width: 10%;">Missing</th>
                    <th style="text-align: center; width: 10%;">Verified</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockLevels as $product)
                    <tr>
                        <td>
                            <img class="table-image" src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}">
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small>SKU: {{ $product->sku }}</small>
                        </td>
                        <td style="text-align: center; font-weight: bold; font-size: 1.1em;">
                            {{ $product->quantity }}
                        </td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td style="text-align: center;">
                            <input type="checkbox" style="width: 20px; height: 20px;">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align: center;">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
