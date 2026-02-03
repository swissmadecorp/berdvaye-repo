<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Estimate extends Model
{
    
    protected $guarded = ['id','qty','price','created_at','customer_id'];

    public function customers() {
        return $this->belongsToMany(Customer::class)
            ->withPivot('customer_id')->orderBy('pivot_customer_id','asc');
    }

    public function orders() {
        return $this->hasOne(Order::class);
    }

    // public function products() {
    //     return $this->belongsToMany(Product::class)->withPivot('id','qty','price','stock','product_name');
    // }

    public function retail() {
        return $this->hasOne(ProductRetail::class,'id','product_retail_id');
    }
    
    public function products() {
        //return $this->belongsTo(ProductRetail::class,'product_id','p_model');;
        //return $this->hasManyThrough(EstimateProduct::class, ProductRetail::class,'p_model','estimate_id','id','id');
        /*
            Estimate
                id
            
            Estimate Product
                id
                product_id
                estimate_id

            Product Retail
                id
                p_model

        */
        return $this->hasMany(EstimateProduct::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function scopeTotalQty($query) {
        return $query->with(['returns' => function($q) 
            {
                return $q->select(DB::raw('product_id,sum(qty)'))
                    ->groupBy('product_id');
            }]);
    }

    public function setPoAttribute($value) {
        if ($value)
            $this->attributes['po'] = strtoupper($value);
        else $this->attributes['po'] = '';
    }
// select product_id, sum(qty) total from `order_returns` 
//     group by product_id
}
