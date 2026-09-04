<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyHoliday extends Model
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
