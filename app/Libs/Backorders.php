<?php

namespace App\Libs;
use App\Models\OrderProduct;

class Backorders {
    
    public function fromProduct($previous_product_id,$previous_order_id,$model) {
        $op = null;
        
        if ($previous_product_id)
            $op = OrderProduct::where('previous_product_id',$previous_product_id)
                ->where('model',$model)->first();
        
        if ($op) {
            $op = OrderProduct::where('product_id',$previous_product_id)
                ->where('previous_order_id',$previous_order_id)
                ->where('model',$model)->first();
            return $op;
        } else return null;
    }
}