<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * THE FIX: This line tells Laravel that this table does not have
     * the default 'created_at' and 'updated_at' columns.
     */
    public $timestamps = false;

    protected $fillable = ['name', 'image'];
}
