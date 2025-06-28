<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Allow 'key' and 'value' to be mass-assigned
    protected $fillable = ['key', 'value'];
}
