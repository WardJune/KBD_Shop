<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $with = ['district','orderDetails'];

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function orderDetails(){
        return $this->hasMany(OrderDetail::class);
    }
    use HasFactory;
}
