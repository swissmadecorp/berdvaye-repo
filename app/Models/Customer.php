<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = ['orders_length'];

    public function orders() {
        return $this->belongsToMany(Order::class)->withPivot('customer_id');
    }

    public function credit() {
        return $this->hasOne(Credit::class);
    }
}
