<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Newspaper;
use App\Models\Subscription;
use Illuminate\Http\Request;

class AssignPaperController extends Controller
{
    public function create()
    {
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $newspapers = Newspaper::where('status', 'Active')->orderBy('name')->get();

        return view('assign-paper.create', compact('customers', 'newspapers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'newspaper_id' => 'required|exists:newspapers,id',
            'start_date' => 'required|date',
            'payment_frequency' => 'required|in:Daily,Weekly,Monthly',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'price_monday' => 'nullable|numeric|min:0',
            'price_tuesday' => 'nullable|numeric|min:0',
            'price_wednesday' => 'nullable|numeric|min:0',
            'price_thursday' => 'nullable|numeric|min:0',
            'price_friday' => 'nullable|numeric|min:0',
            'price_saturday' => 'nullable|numeric|min:0',
            'price_sunday' => 'nullable|numeric|min:0',
        ]);

        // 1. Update customer payment frequency
        $customer = Customer::findOrFail($request->customer_id);
        $customer->update(['payment_frequency' => $request->payment_frequency]);

        // 2. Create subscription
        Subscription::create([
            'customer_id' => $request->customer_id,
            'newspaper_id' => $request->newspaper_id,
            'start_date' => $request->start_date,
            'quantity' => $request->quantity,
            'delivery_days' => 'Daily', // Defaulting to daily for quick assign, can be customized later
            'price' => $request->price,
            'price_monday' => $request->price_monday,
            'price_tuesday' => $request->price_tuesday,
            'price_wednesday' => $request->price_wednesday,
            'price_thursday' => $request->price_thursday,
            'price_friday' => $request->price_friday,
            'price_saturday' => $request->price_saturday,
            'price_sunday' => $request->price_sunday,
            'status' => 'Active',
        ]);

        return redirect()->back()->with('success', 'Paper assigned and payment frequency updated successfully!');
    }
}
