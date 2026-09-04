<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Database\Factories\ExtraNewspaperFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraNewspaper extends Model
{
    /** @use HasFactory<ExtraNewspaperFactory> */
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

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
