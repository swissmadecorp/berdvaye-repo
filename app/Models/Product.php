<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['model_name','_token'];

    public function categories() {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class,'product_id','id')->orderBy('position');
    }

    public function orders() {
        return $this->belongsToMany(Order::class,"order_product")->withPivot('product_id');
    }

    public function returns() {
        return $this->hasMany('App\Models\OrderReturn');
    }

    public function customers() {
        return $this->hasOne(CustomerOrder::class,'customer_id','order_id');
    }

    public function retail() {
        return $this->hasOne(ProductRetail::class,'id','product_retail_id');
    }

    public function scopeOrderByQtyID($query) {
        return $query->orderBy('p_qty','desc')->orderBy('id','desc');
    }

    public function scopePriceGreaterThanZero($query) {
        return $query->where('p_newprice','>','0');
    }

    public function scopeQtyGreaterThanZero($query) {
        return $query->where('p_qty','>',0);
    }

    public function scopeQtyEqualToZero($query) {
        return $query->where('p_qty','=',0);
    }

    public function title () {
        return $this->retail ? $this->retail->model_name : '';
    }

    public function retailvalue() {
        return $this->retail ? $this->retail->p_retail : 0;
    }

    public function image() {
        return $this->retail ? "/images/gallery/thumbnail/". strtolower($this->retail->p_model) .'_thumb.jpg' : '';
    }

    public function scopeByModel(Category $category, Builder $query) {
        return $query->where('category_name', function($q) use
            ($p_model) {
                $q->where('p_model', $category->category_name);
            });
    }

    public function scopeSByModel($query, $criteria) {
        return $query->where('p_model','like','%'.$criteria.'%');
    }

    public function scopeByCategoryName($query, $criteria) {
        return $query->where('category_name','like','%'.$criteria.'%');
    }

    public function setPSerial($value) {
        $this->attributes['p_serial'] = strtoupper($value);
    }

    protected function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if(strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode( ' ', $words);

        return $searchTerm;
    }

    // public function setPQtyAttribute($value) {
    //     if ($value == 0) {
    //         $this->attributes['p_status'] == 3;
    //     } else {

    //         $this->attributes['p_qty'] == $value;
    //     }
    // }

    public function scopeSearch($query, $term)
    {

        $columns = implode(',',$this->searchable);

        $query->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $this->fullTextWildcards($term))
            ->where('p_qty','>',0)
            ->where('p_status','<>',3)
            ->where('p_status','<>',4);

        return $query;
    }

}
