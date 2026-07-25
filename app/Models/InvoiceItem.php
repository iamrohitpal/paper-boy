<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Database\Factories\InvoiceItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /** @use HasFactory<InvoiceItemFactory> */
    use BelongsToUser, HasFactory;

    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
