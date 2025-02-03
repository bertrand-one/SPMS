<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'Pcode',
        'Inquantity',
        'Inprice',
        'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'Pcode', 'id'); // Define relationship to Product model using the correct foreign and local keys
    }
}