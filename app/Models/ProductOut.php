<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'Pcode',
        'Outquantity',
        'Outprice',
        'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'Pcode', 'id'); // Define relationship to Product model using the correct foreign and local keys
    }
}