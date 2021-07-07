<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-secondary">Awaiting Confirmation</span>';
        } elseif ($this->status == 2) {
            return '<span class="badge badge-danger">Rejected</span>';
        }

        return '<span class="badge badge-info">Approved</span>';
    }
}
