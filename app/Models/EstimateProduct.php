<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimateProduct extends Model
{
    protected $table = 'estimate_product';

    public function retail() {
        return $this->hasOne(ProductRetail::class,'p_model','p_model');
    }

    public function title () {
        return $this->retail ? $this->retail->model_name : '';
    }

    public function retailvalue() {
        return $this->retail ? $this->retail->p_retail : 0;
    }

    public function image() {
        return $this->retail ? $this->retail->image_location : '';
    }
}
