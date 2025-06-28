<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'phone_number',
        'email',
        'hire_date',
        'is_active',
        'user_id',
        'cv_path', // Added the new CV path field
    ];

    /**
     * Get the user account associated with the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
