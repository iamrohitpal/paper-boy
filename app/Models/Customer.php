<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use BelongsToTenant, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::creating(function ($customer) {
            if (empty($customer->customer_id)) {
                $lastCustomer = static::withoutGlobalScopes()->orderBy('id', 'desc')->first();
                $nextNum = $lastCustomer ? ($lastCustomer->id + 1) : 1;
                $customer->customer_id = 'CUST' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            }
            if (empty($customer->start_date)) {
                $customer->start_date = now()->format('Y-m-d');
            }
            if (empty($customer->payment_frequency)) {
                $customer->payment_frequency = 'monthly';
            }
        });
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function extraNewspapers()
    {
        return $this->hasMany(ExtraNewspaper::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function leaves()
    {
        return $this->hasMany(CustomerLeave::class);
    }
}
