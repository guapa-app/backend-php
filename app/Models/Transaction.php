<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'transaction_number',
        'amount',
        'operation',
        'transaction_type',
        'transaction_date',
        'invoice_link',
        'status',
        'order_id',
    ];

    protected $casts = [
        'transaction_type' => TransactionType::class,
        'transaction_date' => 'datetime', // Cast transaction_date as a datetime
        'status' => TransactionStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
