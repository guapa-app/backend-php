<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_number',
        'amount',
        'operation',
        'transaction_type',
        'transaction_date',
        'invoice_link'
    ];

    protected $casts = [
        'transaction_type' => TransactionType::class,
        'transaction_date' => 'datetime', // Cast transaction_date as a datetime
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
