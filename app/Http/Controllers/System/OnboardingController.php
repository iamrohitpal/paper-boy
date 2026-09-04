<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Newspaper;
use App\Services\SubscriptionLimitService;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function step1()
    {
        return view('onboarding.step1');
    }

    public function postStep1(Request $request)
    {
        // Currently we just collected this info at registration, so we can just proceed
        // If we needed to collect GST, Address etc, we would validate here.
        return redirect()->route('onboarding.step2');
    }

    public function step2()
    {
        return view('onboarding.step2');
    }

    public function postStep2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'daily_price' => 'required|numeric|min:0',
        ]);

        $price = $request->daily_price;

        Newspaper::create([
            'name' => $request->name,
            'language' => 'English',
            'mrp' => $price,
            'mrp_sunday' => $price,
            'purchase_price' => $price,
            'purchase_price_sunday' => $price,
            'selling_price' => $price,
            'selling_price_sunday' => $price,
            'status' => 'Active',
        ]);

        return redirect()->route('onboarding.step3');
    }

    public function step3()
    {
        return view('onboarding.step3');
    }

    public function postStep3(Request $request, SubscriptionLimitService $limitService)
    {
        if ($limitService->hasReachedCustomerLimit(auth()->user()->tenant)) {
            return redirect()->route('dashboard')->with('error', 'You have reached your customer limit. Upgrade your plan to add more customers.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
        ]);

        Customer::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'address' => 'N/A',
            'delivery_charge' => 0,
            'payment_frequency' => 'monthly',
        ]);

        return redirect()->route('dashboard')->with('success', 'Onboarding completed! Welcome to your dashboard.');
    }

    public function skip()
    {
        return redirect()->route('dashboard');
    }
}
