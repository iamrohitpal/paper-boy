<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait BelongsToTenant
{
    use BelongsToUser;

    protected static function bootBelongsToTenant()
    {
        // Add the global scope to filter all queries by the logged-in user's tenant
        static::addGlobalScope(new TenantScope);

        // Automatically assign the logged-in user's tenant ID when creating a record
        static::creating(function ($model) {
            if (auth()->check() && empty($model->tenant_id)) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
