<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\TenantSubscription;

class SubscriptionLimitService
{
    /**
     * Get the active subscription for the given tenant.
     */
    public function getActiveSubscription(Tenant $tenant): ?TenantSubscription
    {
        return TenantSubscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trial'])
            ->latest()
            ->first();
    }

    /**
     * Get the customer limit for the given tenant.
     */
    public function getCustomerLimit(Tenant $tenant): int
    {
        $subscription = $this->getActiveSubscription($tenant);

        if (! $subscription) {
            return 0; // No active subscription means no customers allowed
        }

        // If the limit is null, it means unlimited
        if (is_null($subscription->customer_limit_snapshot)) {
            return PHP_INT_MAX;
        }

        return $subscription->customer_limit_snapshot;
    }

    /**
     * Get the number of customers currently used by the tenant.
     */
    public function getUsedCustomers(Tenant $tenant): int
    {
        // Bypass the global scope to manually count just in case this is called from an admin context
        return Customer::withoutGlobalScopes()->where('tenant_id', $tenant->id)->count();
    }

    /**
     * Get the remaining customer slots.
     */
    public function getRemainingCustomers(Tenant $tenant): int
    {
        $limit = $this->getCustomerLimit($tenant);
        $used = $this->getUsedCustomers($tenant);

        return max(0, $limit - $used);
    }

    /**
     * Check if the tenant has reached their customer limit.
     */
    public function hasReachedCustomerLimit(Tenant $tenant): bool
    {
        return $this->getUsedCustomers($tenant) >= $this->getCustomerLimit($tenant);
    }

    /**
     * Check if the tenant can create another customer.
     */
    public function canCreateCustomer(Tenant $tenant): bool
    {
        return ! $this->hasReachedCustomerLimit($tenant);
    }
}
