<?php

namespace App\Libs;
use App\Models\ProductRetail;
use App\Models\Product;

class RetailPrice {
    public function RetailPrice($model) {
        if($model) {
            $product = ProductRetail::where('p_model',$model)->first();
            return $product;
        }

        return 0;
    }
}