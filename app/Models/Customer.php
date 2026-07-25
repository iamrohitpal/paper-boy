<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use BelongsToUser, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

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
