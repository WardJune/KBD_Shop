<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, CanResetPassword;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function addressBooks()
    {
        return $this->hasMany(AddressBook::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getIsVerifiedLabelAttribute()
    {
        if ($this->email_verified_at == null) {
            return '<div class="badge badge-secondary">Unverified</div>';
        }

        return '<div class="badge badge-success">Verified</div>';
    }
}
