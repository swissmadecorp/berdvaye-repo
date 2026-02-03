<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRetail extends Model
{
    protected $guarded = ['_method','_token','blade','_id'];

    public function estimateproducts() {
        return $this->belongsToMany(EstimateProduct::class);
    }
}
