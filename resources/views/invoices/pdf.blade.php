<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .header table td {
            vertical-align: top;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            vertical-align: top;
            width: 50%;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .items-table th {
            background-color: #f8f8f8;
            text-align: left;
        }
        .items-table td.right, .items-table th.right {
            text-align: right;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 5px 10px;
            text-align: right;
        }
        .totals-table td.label {
            font-weight: bold;
            width: 75%;
        }
        .totals-table tr.total td {
            border-top: 2px solid #eee;
            font-size: 18px;
            font-weight: bold;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            background-color: #f44336; /* Default Unpaid */
        }
        .status.Paid { background-color: #4CAF50; }
        .status.Partially { background-color: #FF9800; }
        .notes {
            margin-top: 50px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="title">INVOICE</div>
                        <div>#{{ $invoice->invoice_number }}</div>
                        <div style="margin-top: 10px;">
                            <span class="status {{ explode(' ', $invoice->status)[0] }}">{{ $invoice->status }}</span>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <strong>PaperBoy Distributions</strong><br>
                        City Center<br>
                        contact@paperboy.com<br>
                    </td>
                </tr>
            </table>
        </div>
        
        <table class="info-table">
            <tr>
                <td>
                    <strong>Billed To:</strong><br>
                    {{ $invoice->customer->name }} ({{ $invoice->customer->customer_id }})<br>
                    {{ $invoice->customer->mobile }}<br>
                    {{ $invoice->customer->area ?? '' }}<br>
                    {{ $invoice->customer->address ?? '' }}
                </td>
                <td style="text-align: right;">
                    <strong>Billing Month:</strong> {{ $invoice->billing_month->format('F Y') }}<br>
                    <strong>Issue Date:</strong> {{ $invoice->created_at->format('d M, Y') }}<br>
                    <strong>Due Date:</strong> {{ $invoice->due_date->format('d M, Y') }}
                </td>
            </tr>
        </table>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="right">Qty/Days</th>
                    <th class="right">Unit Price</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="right">{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td>{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Amount Paid:</td>
                <td>{{ number_format($invoice->paid_amount, 2) }}</td>
            </tr>
            <tr class="total">
                <td class="label">Balance Due:</td>
                <td>{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
            </tr>
        </table>
        
        <div class="notes">
            <strong>Notes / Payment Terms:</strong><br>
            Please make the payment by the due date to avoid service interruption. Payments can be made via Cash or UPI.
        </div>
    </div>
</body>
</html>
