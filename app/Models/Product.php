<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'Pcode',
        'Pname',
        'user_id',
    ];

    // Relationship to the User model (optional but recommended):
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}