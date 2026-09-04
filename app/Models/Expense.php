<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'expense_date' => 'date',
    ];
}
