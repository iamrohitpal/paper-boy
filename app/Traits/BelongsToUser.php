<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToUser
{
    protected static function bootBelongsToUser()
    {
        // Add a global scope to filter all queries by the logged-in user or their owner
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->check()) {
                $userId = auth()->user()->owner_id ?? auth()->id();
                $builder->where($builder->getModel()->getTable().'.user_id', $userId);
            }
        });

        // Automatically assign the logged-in user's ID or their owner's ID when creating a record
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->user()->owner_id ?? auth()->id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
