<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->number }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 1rem;
            margin: 0;
            padding: 20px;
            background: white;
        }

        .invoice-container {
            max-width: 900px;
            margin: auto;
            padding: 40px;
            border: 1px solid #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .store-info {
            font-size: 1rem;
            text-align: center;
        }

        .invoice-title {
            font-size: 2rem;
            font-weight: bold;
            background: #000;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 3px;
        }

        .invoice-details {
            font-size: 1.2rem;
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #000;
        }

        .invoice-details div {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            font-size: 1.2rem;
        }

        th {
            background: #000;
            color: white;
            text-align: center;
        }

        .totals {
            margin-top: 30px;
            text-align: right;
            font-size: 1.4rem;
            padding: 15px;
            border: 1px solid #000;
        }

        .totals div {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 8px 0;
        }

        .totals .total-final {
            background: #000;
            color: white;
            font-size: 1.6rem;
            padding: 10px;
        }

        .barcode {
            text-align: center;
            margin-top: 20px;
        }

        .thanks-message {
            text-align: center;
            font-size: 1.4rem;
            margin-top: 30px;
            font-style: italic;
        }

        .print-button {
            text-align: center;
            margin-top: 30px;
        }

        .print-button button {
            background: #000;
            color: white;
            padding: 12px 20px;
            font-size: 1.4rem;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        @media print {
            .print-button {
                display: none;
            }
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            .invoice-container {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            @if ($settings->logo)
                <div>{!! $settings->logo !!}</div>
            @endif
        </div>

        <div class="store-info">
            @if ($settings->storeName)
                <div style="font-size: 1.5rem; font-weight: bold;">{{ $settings->storeName }}</div>
            @endif
            @if ($settings->storeAddress)
                <div>{{ $settings->storeAddress }}</div>
            @endif
            @if ($settings->storePhone)
                <div>{{ $settings->storePhone }}</div>
            @endif
            @if ($settings->storeWebsite)
                <div>{{ $settings->storeWebsite }}</div>
            @endif
            @if ($settings->storeEmail)
                <div>{{ $settings->storeEmail }}</div>
            @endif
        </div>

        <div class="invoice-title">@lang('SALE INVOICE')</div>

        <div class="invoice-details">
            <div><span>@lang('Invoice No')</span> <strong>{{ $order->number }}</strong></div>
            <div><span>@lang('Date')</span> <strong>{{ $order->date_view }}</strong></div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>@lang('Product Name')</th>
                        <th>@lang('Qty')</th>
                        <th>@lang('Unit Price')</th>
                        <th>@lang('Total')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->order_details as $detail)
                        <tr>
                            <td>{{ $detail->product->name }}</td>
                            <td style="text-align: center;">{{ $detail->quantity }}</td>
                            <td style="text-align: right;">{{ $detail->view_price }}</td>
                            <td style="text-align: right;">{{ $detail->view_total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="totals">
            <div><span>@lang('Subtotal')</span> <span>{{ $order->subtotal_view }}</span></div>
            @if ($order->discount > 0)
                <div><span>@lang('Discount')</span> <span>{{ $order->discount_view }}</span></div>
            @endif
            @if ($order->is_delivery && $order->delivery_charge > 0)
                <div><span>@lang('Delivery Charge')</span> <span>{{ $order->delivery_charge_view }}</span></div>
            @endif
            <div class="total-final"><span>@lang('Total')</span> <span>{{ $order->total_view }}</span></div>
        </div>

        <div class="barcode">
            <div>{{ $order->number }}</div>
            <div>{!! DNS1D::getBarcodeSVG($order->number, 'C128', 2, 30, 'black', false) !!}</div>
        </div>

        @if ($settings->storeAdditionalInfo)
            <div class="thanks-message">
                {!! nl2br($settings->storeAdditionalInfo) !!}
            </div>
        @endif

        <div class="print-button">
            <button onclick="window.print()">@lang('Print Again')</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>
