<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','image'];
    
    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function takeImage(){
        return "/storage/" . $this->image;
    }
}