<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['district.city.province'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
