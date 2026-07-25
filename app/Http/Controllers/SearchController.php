<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Newspaper;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        $results = [
            'customers' => collect(),
            'invoices' => collect(),
            'newspapers' => collect(),
        ];

        if (! empty($query)) {
            $results['customers'] = Customer::where('name', 'like', "%{$query}%")
                ->orWhere('mobile', 'like', "%{$query}%")
                ->orWhere('customer_id', 'like', "%{$query}%")
                ->take(10)
                ->get();

            $results['invoices'] = Invoice::where('invoice_number', 'like', "%{$query}%")
                ->take(10)
                ->get();

            $results['newspapers'] = Newspaper::where('name', 'like', "%{$query}%")
                ->orWhere('publisher', 'like', "%{$query}%")
                ->take(10)
                ->get();
        }

        return view('search.results', compact('results', 'query'));
    }
}
