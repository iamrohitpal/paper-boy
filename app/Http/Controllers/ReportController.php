<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $parsedMonth = Carbon::parse($month);

        $startOfMonth = $parsedMonth->copy()->startOfMonth();
        $endOfMonth = $parsedMonth->copy()->endOfMonth();

        $payments = Payment::with('customer')
            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->orderBy('payment_date', 'asc')
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->orderBy('expense_date', 'asc')
            ->get();

        $totalIncome = $payments->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('reports.index', compact(
            'month',
            'parsedMonth',
            'payments',
            'expenses',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $parsedMonth = Carbon::parse($month);

        $startOfMonth = $parsedMonth->copy()->startOfMonth();
        $endOfMonth = $parsedMonth->copy()->endOfMonth();

        $payments = Payment::with('customer')
            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
            ->orderBy('payment_date', 'asc')
            ->get();

        $expenses = Expense::whereBetween('expense_date', [$startOfMonth, $endOfMonth])
            ->orderBy('expense_date', 'asc')
            ->get();

        $totalIncome = $payments->sum('amount');
        $totalExpense = $expenses->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        $pdf = Pdf::loadView('reports.pdf', compact(
            'month',
            'parsedMonth',
            'payments',
            'expenses',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));

        return $pdf->download('financial-report-'.$parsedMonth->format('M-Y').'.pdf');
    }
}
