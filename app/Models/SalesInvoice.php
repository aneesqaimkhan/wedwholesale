<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $table = 'sales_invoices';

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'salesman_code',
        'salesman_name',
        'customer_code',
        'customer_name',
        'address',
        'remarks',
        'previous_balance',
    ];

    public $timestamps = false; // created_at handled by DB default

    public function items(): HasMany
    {
        return $this->hasMany(SalesInvoiceItem::class, 'invoice_id');
    }
}


