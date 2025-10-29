<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'sales_invoice_items';

    protected $fillable = [
        'invoice_id',
        'product_code',
        'product_name',
        'pack',
        'box',
        'pcs',
        'rate',
        'b_per_box',
        'stx',
        'discount',
        'net_amount',
    ];

    public $timestamps = false;

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }
}


