<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['district.city.province', 'details'];
    protected $withCount = ['return'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="text-warning">New</span>';
        } elseif ($this->status == 1) {
            return '<span class="text-warning">Awaiting Confirmation</span>';
        } elseif ($this->status == 2) {
            return '<span class="text-warning">Process</span>';
        } elseif ($this->status == 3) {
            return '<span class="text-warning">Sent</span>';
        }
        return '<span class="text-warning">Done</span>';
    }

    public function return()
    {
        return $this->hasOne(OrderReturn::class);
    }
}
