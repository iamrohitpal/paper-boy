<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyHoliday extends Model
{
    use BelongsToUser, HasFactory;

    protected $fillable = [
        'date',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
