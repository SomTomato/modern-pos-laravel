<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // We no longer need custom timestamp rules
    
    protected $fillable = [
        'user_id',
        'customer_id',
        'total_amount',
        'payment_method',
        'payment_provider',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(SaleItem::class); }
}
