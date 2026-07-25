<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use BelongsToUser, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'expense_date' => 'date',
    ];
}
