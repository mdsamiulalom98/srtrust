<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariable extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class)->select('id', 'name', 'stock', 'stock_alert', 'new_price');
    }
    public function image()
    {
        return $this->belongsTo(Productimage::class)->select('id', 'image', 'product_id')->latest();
    }
}
