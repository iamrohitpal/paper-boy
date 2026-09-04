<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantSwitcherController extends Controller
{
    public function switch(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        session(['active_tenant_id' => $tenant->id]);
        session(['active_tenant_name' => $tenant->name]);

        return redirect()->route('dashboard')->with('success', "Switched to tenant: {$tenant->name}");
    }

    public function clear(Request $request)
    {
        session()->forget(['active_tenant_id', 'active_tenant_name']);

        return redirect()->route('dashboard')->with('success', 'Returned to Global Administration Mode.');
    }
}
