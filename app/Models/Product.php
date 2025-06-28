<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * THE FIX: This line tells Laravel that the 'products' table does not have
     * the default 'created_at' and 'updated_at' columns.
     */
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'price',
        'quantity',
        'image',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
