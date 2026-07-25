<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use BelongsToUser, HasFactory;

    protected $fillable = [
        'newspaper_id',
        'purchase_date',
        'quantity',
        'rate',
        'return_quantity',
        'return_rate',
        'total_amount',
        'amount_paid',
        'balance_due',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'quantity' => 'integer',
        'rate' => 'decimal:2',
        'return_quantity' => 'integer',
        'return_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    public function newspaper()
    {
        return $this->belongsTo(Newspaper::class);
    }
}
