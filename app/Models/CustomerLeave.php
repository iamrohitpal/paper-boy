<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLeave extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'customer_id',
        'newspaper_id',
        'date',
        'is_billed',
    ];

    protected $casts = [
        'date' => 'date',
        'is_billed' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function newspaper()
    {
        return $this->belongsTo(Newspaper::class);
    }
}
