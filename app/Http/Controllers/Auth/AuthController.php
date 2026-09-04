<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'language' => ['required', 'in:en,hi'],
        ]);

        \DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'language' => $request->language,
            ]);

            $tenant = Tenant::create([
                'name' => $request->business_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'owner_id' => $user->id,
                'status' => 'trial', // Default trial status
            ]);

            $user->update([
                'tenant_id' => $tenant->id,
                'owner_id' => $user->id, // Tenant admins are their own owners
            ]);

            // Ensure Basic Vendor role exists and assign it
            $vendorRole = Role::firstOrCreate(['name' => 'Basic Vendor']);
            $user->assignRole($vendorRole);

            // Assign Starter Plan
            $plan = Plan::where('slug', 'starter')->first();
            if ($plan) {
                TenantSubscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'status' => 'trial',
                    'starts_at' => now(),
                    'trial_ends_at' => now()->addDays($plan->trial_days),
                    'customer_limit_snapshot' => $plan->customer_limit,
                ]);
            }

            \DB::commit();

            Auth::login($user);

            return redirect()->route('onboarding.step1');
        } catch (\Exception $e) {
            \DB::rollBack();

            return back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
