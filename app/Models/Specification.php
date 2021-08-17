<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Specification extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    public function searchableAs()
    {
        return 'specs';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
