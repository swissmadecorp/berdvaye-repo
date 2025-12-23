<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Order extends Model
{

    protected $guarded = ['id','product_name','product','qty','price','serial','created_at','product_id','customer_id','model','order_id','creditamount'];

    public function customers() {
        return $this->belongsToMany(Customer::class)
            ->withPivot('customer_id')->orderBy('pivot_customer_id','asc');
    }

    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('id','qty','price','retail','serial','product_name','memo_id','model','tracking','shipped','repair_date','returned_date','img_name');
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function returns() {
        return $this->belongsToMany(Returns::class)->withPivot('id','qty','amount','created_at','product_id');
    }

    public function scopeSortit($query) {
        return $query->orderBy('created_at','desc');
    }

    public function orderReturns() {
        return $this->belongsToMany(Returns::class,'order_returns')->withPivot('product_id','returns_id','amount','qty','created_at','order_id');
    }

    public function orderProducts() {
        return $this->belongsToMany(OrderProduct::class,"order_product")->withPivot('product_id','price','product_name');
    }

    public function setPoAttribute($value) {
        if ($value)
            $this->attributes['po'] = strtoupper($value);
        else $this->attributes['po'] = '';
    }

    public function scopeTotalQty($query) {
        return $query->with(['returns' => function($q)
            {
                return $q->select(DB::raw('product_id,sum(qty)'))
                    ->groupBy('product_id');
            }]);
    }


// select product_id, sum(qty) total from `order_returns`
//     group by product_id
}
