<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Returns extends Model
{
    protected $fillable = ['order_id','product_id','qty','comment','reason'];
    public $timestamps = false;
    
    public function orders() {
        return $this->belongsToMany(Order::class)->withPivot('amount');
    }

    public function products() {
        return $this->belongsToMany(Product::class,'order_returns')->withPivot('qty','amount','created_at');
    }

    public function orderreturn() {
        return $this->hasMany(OrderReturn::class);
    }
}
