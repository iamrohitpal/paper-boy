<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Super Admin logic
            if ($user->hasRole('Super Admin')) {
                // If the Super Admin has explicitly selected a tenant to impersonate
                if (session()->has('active_tenant_id')) {
                    $builder->where($model->getTable().'.tenant_id', session('active_tenant_id'));
                }

                // Otherwise, they are in Global Mode (see all records)
                return;
            }

            $tenantId = $user->tenant_id;

            if ($tenantId) {
                $builder->where($model->getTable().'.tenant_id', $tenantId);
            } else {
                // If the user has no tenant, they shouldn't see any tenant data
                $builder->whereRaw('1 = 0');
            }
        }
    }
}
