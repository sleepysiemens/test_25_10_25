<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $fillable = [
        'type',
        'from',
        'to',
        'amount',
        'comment',
    ];
}
