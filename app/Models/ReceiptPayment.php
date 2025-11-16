<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'payment_from',
        'entity_code',
        'entity_name',
        'supplier_code',
        'supplier_name',
        'receipt',
        'payment',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'receipt' => 'decimal:2',
        'payment' => 'decimal:2',
        'payment_date' => 'date',
    ];
}
