<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $with = ['category', 'merk'];
    protected $casts = [
        'desc' => 'array'
    ];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Disable</span>';
        }
        return '<span class="badge badge-success">Active</span>';
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
}
