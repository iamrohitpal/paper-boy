<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Financial Report - {{ $parsedMonth->format('F Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #777;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            width: 33.33%;
        }
        .summary-table th {
            background-color: #f8f8f8;
        }
        .summary-table .amount {
            font-size: 18px;
            font-weight: bold;
        }
        .text-green { color: #4CAF50; }
        .text-red { color: #F44336; }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .data-table th, .data-table td {
            border: 1px solid #eee;
            padding: 8px;
        }
        .data-table th {
            background-color: #f8f8f8;
            text-align: left;
        }
        .data-table td.right, .data-table th.right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PaperBoy Distributions</h1>
        <p>Financial Report for {{ $parsedMonth->format('F Y') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <th>Total Income</th>
            <th>Total Expenses</th>
            <th>Net Profit/Loss</th>
        </tr>
        <tr>
            <td>
                <div class="amount text-green">₹{{ number_format($totalIncome, 2) }}</div>
                <div style="font-size: 11px; color: #777;">{{ $payments->count() }} Payments</div>
            </td>
            <td>
                <div class="amount text-red">₹{{ number_format($totalExpense, 2) }}</div>
                <div style="font-size: 11px; color: #777;">{{ $expenses->count() }} Expenses</div>
            </td>
            <td>
                <div class="amount {{ $netProfit >= 0 ? 'text-green' : 'text-red' }}">
                    ₹{{ number_format($netProfit, 2) }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Income Details (Payments)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="65%">Customer</th>
                <th class="right" width="20%">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('d M, Y') }}</td>
                    <td>{{ $payment->customer->name }} ({{ $payment->customer->customer_id }}) - {{ $payment->payment_mode }}</td>
                    <td class="right text-green">{{ number_format($payment->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No income recorded for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Expense Details</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Date</th>
                <th width="50%">Title / Description</th>
                <th width="15%">Category</th>
                <th class="right" width="20%">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('d M, Y') }}</td>
                    <td>{{ $expense->title }}</td>
                    <td>{{ $expense->category }}</td>
                    <td class="right text-red">{{ number_format($expense->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No expenses recorded for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
