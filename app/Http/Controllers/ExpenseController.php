<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Services\ExpenseService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $expenses = $this->expenseService->getPaginatedExpenses($search);

        return view('expenses.index', compact('expenses', 'search'));
    }

    public function create()
    {
        $categories = ['Salary', 'Fuel', 'Printing', 'Maintenance', 'Office Supplies', 'General', 'Other'];

        return view('expenses.create', compact('categories'));
    }

    public function store(ExpenseRequest $request)
    {
        $this->expenseService->createExpense($request->validated());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit($id)
    {
        $expense = $this->expenseService->getExpense($id);
        $categories = ['Salary', 'Fuel', 'Printing', 'Maintenance', 'Office Supplies', 'General', 'Other'];

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(ExpenseRequest $request, $id)
    {
        $this->expenseService->updateExpense($id, $request->validated());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $this->expenseService->deleteExpense($id);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
