<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Database\Factories\NewspaperFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newspaper extends Model
{
    /** @use HasFactory<NewspaperFactory> */
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'subscriptions')
            ->withPivot(['quantity', 'start_date', 'end_date', 'delivery_days', 'price', 'status']);
    }
}
