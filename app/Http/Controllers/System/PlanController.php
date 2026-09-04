<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount(['subscriptions' => function ($query) {
            $query->where('status', 'active');
        }])->get();

        return view('system.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('system.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_interval' => 'required|in:monthly,yearly',
            'customer_limit' => 'nullable|integer|min:0',
            'features' => 'nullable|string', // Comma separated
            'trial_days' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if (! empty($validated['features'])) {
            $validated['features'] = array_map('trim', explode(',', $validated['features']));
        } else {
            $validated['features'] = [];
        }

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('system.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,'.$plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_interval' => 'required|in:monthly,yearly',
            'customer_limit' => 'nullable|integer|min:0',
            'features' => 'nullable|string',
            'trial_days' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        if (! empty($validated['features'])) {
            $validated['features'] = array_map('trim', explode(',', $validated['features']));
        } else {
            $validated['features'] = [];
        }

        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return redirect()->route('plans.index')->with('error', 'Cannot delete a plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully.');
    }
}
