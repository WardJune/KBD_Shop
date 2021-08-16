<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;
    protected $guarded = [];
    // protected $with = ['category', 'merk'];
    protected $casts = [
        'desc' => 'array'
    ];

    public function searchableAs()
    {
        return 'products_index';
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Disable</span>';
        }
        return '<span class="badge badge-success">Active</span>';
    }

    public function getButtonStatusAttribute()
    {
        if ($this->stock->qty <= 0) {
            return 'disabled';
        }
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock->qty <= 0) {
            return '<span class="small text-muted d-block mb-md-2">Out of Stock</span>';
        }

        return '<span class="small text-muted d-block mb-md-2">In Stock</span>';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function merk()
    {
        return $this->belongsTo(Merk::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }

    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
