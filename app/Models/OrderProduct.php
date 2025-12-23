<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_product';
    protected $guarded = [];
    public $timestamps = false;

    public function image() {
        return $this->belongsTo(ProductRetail::class,'p_model','model');
    }
}
