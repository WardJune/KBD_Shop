<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getStockLabelAttribute()
    {
        if ($this->product->stock->qty <= 0) {
            return 'Out of Stock';
        }

        return 'In Stock';
    }

    public function getButtonStatusAttribute()
    {
        if ($this->product->stock->qty <= 0) {
            return 'disabled';
        }
    }
}
