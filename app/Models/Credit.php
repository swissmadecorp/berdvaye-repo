<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credit_customer';
    protected $guarded = [];

    public function customers() {
        return $this->belongsToMany(Customer::class,'credit_customer','id','customer_id');
    }

}
