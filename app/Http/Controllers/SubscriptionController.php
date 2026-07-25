<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Customer;
use App\Models\Newspaper;
use App\Services\CustomerService;
use App\Services\NewspaperService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    protected CustomerService $customerService;

    protected NewspaperService $newspaperService;

    public function __construct(
        SubscriptionService $subscriptionService,
        CustomerService $customerService,
        NewspaperService $newspaperService
    ) {
        $this->subscriptionService = $subscriptionService;
        $this->customerService = $customerService;
        $this->newspaperService = $newspaperService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $subscriptions = $this->subscriptionService->getPaginatedSubscriptions($search);

        // Pass newspapers for the bulk action dropdown
        $newspapers = Newspaper::where('status', 'Active')->orderBy('name')->get();

        return view('subscriptions.index', compact('subscriptions', 'search', 'newspapers'));
    }

    public function create()
    {
        // Need to load customers and newspapers for dropdowns
        $customers = Customer::where('status', 'Active')->orderBy('name')->get();
        $newspapers = Newspaper::where('status', 'Active')->orderBy('name')->get();

        return view('subscriptions.create', compact('customers', 'newspapers'));
    }

    public function store(SubscriptionRequest $request)
    {
        $this->subscriptionService->createSubscription($request->validated());

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect()->back()->with('success', 'Subscription created successfully.');
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    public function edit($id)
    {
        $subscription = $this->subscriptionService->getSubscription($id);
        $customers = Customer::orderBy('name')->get();
        $newspapers = Newspaper::orderBy('name')->get();

        return view('subscriptions.edit', compact('subscription', 'customers', 'newspapers'));
    }

    public function update(SubscriptionRequest $request, $id)
    {
        $this->subscriptionService->updateSubscription($id, $request->validated());

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect()->back()->with('success', 'Subscription updated successfully.');
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    public function destroy($id)
    {
        $this->subscriptionService->deleteSubscription($id);

        if (str_contains(url()->previous(), '/customers/')) {
            return redirect()->back()->with('success', 'Subscription deleted successfully.');
        }

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:subscriptions,id',
            'action' => 'required|in:status,newspaper',
            'status' => 'required_if:action,status|in:Active,Paused,Cancelled',
            'newspaper_id' => 'required_if:action,newspaper|exists:newspapers,id',
        ]);

        $ids = $request->selected;
        $data = [];

        if ($request->action === 'status') {
            $data['status'] = $request->status;
        } elseif ($request->action === 'newspaper') {
            $data['newspaper_id'] = $request->newspaper_id;

            // Optionally, we could update the price based on the new newspaper
            $newspaper = Newspaper::find($request->newspaper_id);
            if ($newspaper) {
                // Bulk update doesn't allow setting customer-specific negotiated prices easily
                // For safety, we will just change the newspaper mapping.
                // A more advanced system would calculate the new price per customer here.
                $data['price'] = $newspaper->mrp; // Fallback to MRP
            }
        }

        $this->subscriptionService->bulkUpdateSubscriptions($ids, $data);

        return redirect()->route('subscriptions.index')
            ->with('success', count($ids).' subscriptions updated successfully.');
    }
}
