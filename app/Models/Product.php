<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_code',
        'product_name',
        'pcs_in_box',
        'supplier_id',
        'bonus_type',
        'expire_date',
        'packing',
        'opening_qty_box',
        'opening_qty_pcs',
        'minimum_stock_box',
        'minimum_stock_pcs',
        'n_price_box',
        'n_price_pcs',
        't_price_box',
        't_price_pcs',
        'r_price_box',
        'r_price_pcs',
        'sales_tax',
        'rate_in_percent',
        'default_rate_type',
        'company_id',
    ];

    protected $casts = [
        'pcs_in_box' => 'integer',
        'supplier_id' => 'integer',
        'expire_date' => 'date',
        'opening_qty_box' => 'integer',
        'opening_qty_pcs' => 'integer',
        'minimum_stock_box' => 'integer',
        'minimum_stock_pcs' => 'integer',
        'n_price_box' => 'decimal:2',
        'n_price_pcs' => 'decimal:2',
        't_price_box' => 'decimal:2',
        't_price_pcs' => 'decimal:2',
        'r_price_box' => 'decimal:2',
        'r_price_pcs' => 'decimal:2',
        'sales_tax' => 'decimal:2',
        'rate_in_percent' => 'decimal:2',
        'company_id' => 'integer',
    ];
}

