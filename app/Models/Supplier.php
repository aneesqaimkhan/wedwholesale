<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'area_id',
    ];

    /**
     * Get the area that the supplier belongs to.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}

