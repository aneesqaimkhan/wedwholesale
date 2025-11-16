<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'area_id',
    ];

    /**
     * Get the area that the customer belongs to.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
