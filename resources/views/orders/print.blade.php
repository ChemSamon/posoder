<!DOCTYPE html>
<html>
<head>
    <title>Print Receipt</title>
    <style>
    @media print {
        @page {
            size: 58mm auto;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: 'Khmer OS Battambang', 'Courier New', monospace;
            font-size: 13px;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            width: 58mm;
        }

        .receipt-container {
            width: 100%;
            max-width: 58mm;
            margin: 0 auto;
            padding: 5px;
            box-sizing: border-box;
        }

        /* Header */
        .receipt-header {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        /* Receipt number & date */
        #receipt-number {
            text-align: left;
            font-size: 13px;
            margin: 5px 0;
        }

        .receipt-line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        /* Table Style for Items */
        table.receipt-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 2px 0;
            vertical-align: top;
            word-wrap: break-word;
        }

        .receipt-table th:last-child,
        .receipt-table td:last-child {
            text-align: right;
        }

        .receipt-table th {
            border-bottom: 1px solid #000;
            font-weight: bold;
        }

        /* Item Note */
        .item-note {
            font-size: 12px;
            font-style: italic;
            margin-left: 2px;
            margin-bottom: 4px;
            word-wrap: break-word;
            font-weight: normal;
        }

        /* Totals */
        .receipt-total {
            border-top: 2px solid #000;
            margin-top: 6px;
            padding-top: 3px;
            font-size: 13px;
            line-height: 1.3;
            font-weight: bold;
        }

        /* Final Total Highlight */
        .final-total-row {
            font-size: 14px;
            border-top: 2px solid #000;
            padding-top: 4px;
        }

        .thank-you {
            text-align: center;
            margin-top: 8px;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 5px;
            font-weight: bold;
        }

        .footer-info {
            text-align: center;
            font-size: 9px;
            margin-top: 4px;
            line-height: 1.2;
        }

        .receipt-container * {
            page-break-inside: avoid !important;
        }

        /* Text alignment helpers */
        .text-left { text-align: left; }
        .text-right { text-align: right; }
    }

    /* Screen preview styles */
    body {
        font-family: 'Khmer OS Battambang', 'Courier New', monospace;
        font-size: 13px;
        background: #f4f4f4;
        color: #000;
        text-align: center;
    }

    .receipt-container {
        max-width: 58mm;
        margin: 20px auto;
        background: #fff;
        box-shadow: 0 0 5px rgba(0,0,0,0.2);
        padding: 5px;
    }

    table.receipt-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .receipt-table th, .receipt-table td {
        font-size: 13px;
        padding: 2px 0;
    }

    .summary td {
        font-weight: bold;
    }
    </style>
</head>
<body onload="window.print()">

@php
    // Set exchange rate if not passed from controller
    $exchangeRate = $exchangeRate ?? 4100;
@endphp

<div class="receipt-container">
    <div class="receipt-header">ចែរញាវ​ បុកល្ហុងកូនកាត់</div>

    <p id="receipt-number">
        លេខវិក័យប័ត្រ #: <span style="font-weight: normal;">{{ $order->receipt_number }}</span><br>
        ថ្ងៃ: <span style="font-weight: normal;">{{ $order->created_at->format('Y-m-d H:i') }}</span>
    </p>

    <div class="receipt-line"></div>

    <table class="receipt-table">
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="padding-left: 0;">
                    {{ $item->product_name }}
                    @if(!empty($item->note))
                        <span class="item-note">ម្ទេស:({{ $item->note }})</span>
                    @endif
                </td>
                <td class="text-left">x{{ $item->quantity }}</td>
                <td class="text-right">${{ number_format($item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="receipt-line"></div>

    <table class="summary" style="width: 100%; text-align: left;">
    <tr>
        <td>Subtotal: ${{ number_format($order->total, 2) }}</td>
    </tr>
    <tr>
        <td>Discount ({{ $order->discount }}%): -${{ number_format($order->total - $order->total_after_discount, 2) }}</td>
    </tr>

    @if(!empty($order->note))
    <tr>
        <td>Order Note: <span style="font-weight: normal; font-size: 10px;">{{ $order->note }}</span></td>
    </tr>
    @endif

    <tr class="final-total-row">
        <td>**ចំនួនសរុប**: ${{ number_format($order->total_after_discount, 2) }} = {{ number_format($order->total_after_discount * $exchangeRate, 0) }} ៛</td>
    </tr>
</table>


    <div class="receipt-line"></div>

    <p class="thank-you">🙏 សូមអរគុណ ម៉ូយៗ</p>

    <div class="footer-info">
        Tel: 016 789 312<br>
        Powered by Samon
    </div>
</div>

</body>
</html>
