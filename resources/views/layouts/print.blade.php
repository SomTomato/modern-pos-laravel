<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Print Document')</title>
    <style>
        /* This is the only CSS that will apply to this page */
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
        }
        .print-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .print-header h1 {
            margin: 0;
            font-size: 24pt;
            font-weight: bold;
        }
        .print-header p {
            margin: 5px 0;
            font-size: 12pt;
        }
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .styled-table th, .styled-table td {
            border: 1px solid #333 !important;
            padding: 8px;
            text-align: left;
        }
        .styled-table thead tr {
            background-color: #e9ecef !important;
            -webkit-print-color-adjust: exact; /* Ensures background colors print */
            print-color-adjust: exact;
        }
        .styled-table .table-image {
            height: 40px;
            width: 40px;
            vertical-align: middle;
        }
        .physical-count-col {
            width: 120px;
        }
        .writable {
             height: 40px;
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function () { window.close(); }">
    @yield('content')
</body>
</html>
