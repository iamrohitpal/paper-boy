<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function upgrade(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $tenant = auth()->user()->tenant;

        if (auth()->user()->hasRole('Super Admin') && session()->has('active_tenant_id')) {
            $tenant = Tenant::find(session('active_tenant_id'));
        }

        // Simulate an upgrade. In real production, this would redirect to a payment gateway
        // Cancel old subscription
        TenantSubscription::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->update(['status' => 'canceled', 'ends_at' => now()]);

        // Create new subscription
        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $plan->billing_interval === 'monthly' ? now()->addMonth() : now()->addYear(),
        ]);

        return redirect()->back()->with('success', "Successfully upgraded to {$plan->name}.");
    }
}
