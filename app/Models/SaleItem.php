<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    /**
     * THE FIX: This line tells Laravel that this table does not have
     * the default 'created_at' and 'updated_at' columns.
     */
    public $timestamps = false;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price_per_unit',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
