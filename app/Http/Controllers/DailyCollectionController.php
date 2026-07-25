<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyCollectionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'Daily');

        // Fetch customers with Daily or Weekly payment frequency
        $customers = Customer::where('status', 'Active')
            ->where('payment_frequency', $type)
            ->with(['subscriptions' => function ($q) {
                $q->where('status', 'Active');
            }])
            ->get();

        // Calculate expected daily collection per customer
        foreach ($customers as $customer) {
            $dailyTotal = 0;
            $isSunday = Carbon::today()->isSunday();

            foreach ($customer->subscriptions as $sub) {
                $price = ($isSunday && $sub->price_sunday) ? $sub->price_sunday : $sub->price;
                $dailyTotal += ($price * $sub->quantity);
            }
            $customer->expected_collection = $dailyTotal;

            // Check if they already paid today
            $customer->paid_today = Payment::where('customer_id', $customer->id)
                ->whereDate('payment_date', Carbon::today())
                ->exists();
        }

        return view('daily-collections.index', compact('customers', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
        ]);

        $ids = $request->customer_ids;
        $isSunday = Carbon::today()->isSunday();
        $count = 0;

        DB::transaction(function () use ($ids, $isSunday, &$count) {
            foreach ($ids as $id) {
                $customer = Customer::with(['subscriptions' => function ($q) {
                    $q->where('status', 'Active');
                }])->find($id);

                if (! $customer) {
                    continue;
                }

                $dailyTotal = 0;
                foreach ($customer->subscriptions as $sub) {
                    $price = ($isSunday && $sub->price_sunday) ? $sub->price_sunday : $sub->price;
                    $dailyTotal += ($price * $sub->quantity);
                }

                if ($dailyTotal > 0) {
                    Payment::create([
                        'customer_id' => $customer->id,
                        'amount' => $dailyTotal,
                        'payment_date' => Carbon::today(),
                        'payment_method' => 'Cash',
                        'reference_number' => 'Daily Collection',
                        'notes' => 'Bulk Daily Collection',
                    ]);
                    $count++;
                }
            }
        });

        return redirect()->back()->with('success', "Successfully marked $count customers as paid for today.");
    }
}
