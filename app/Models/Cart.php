<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Session;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public static function Add($item) {
        $cart = collect([$item]);

        session()->forget('cart_product');
        session()->put('cart_product', $cart);

        return $cart;
    }

    public static  function Find($id) {
        $collection1 = session()->get('cart_product');
        if ($collection1) {
            $first_key = $collection1->where('id',$id);

            if (count($first_key)>0) {
                $key = array_keys($first_key->toArray());
                return $collection1[$key[0]];
            }
        } else return null;
    }

    public static function Insert($item) {
        $collection1 = session()->get('cart_product');

        $cart = $collection1->add($item);

        $dups = $cart->duplicates('id');
        $cartUnique = $cart->unique('id');

        $dups = array_values(array_unique ($dups->toArray()));

        $cart = null;

        foreach ($cartUnique as $value) {
            if (isset($value['qty']))
                if (in_array($value['id'],$dups)) $value['qty']=$value['qty']+1;

            if (!isset($cart))
                $cart = collect([$value]);
            else $cart->add($value);
        }

        session()->put('cart_product', $cart);

        return $cart;
    }

    public static function UpdateItem($id,$attribute,$value) {
        $collection1 = session()->get('cart_product');
        if ($collection1) {
            $first_key = $collection1->where('id',$id);

            if (count($first_key)>0) {
                $key = array_keys($first_key->toArray());
                $item = $first_key->toArray();

                $item[0][$attribute]=$value;

                $collection1 = $collection1->map(function($plan) use ($id,$attribute,$value) {
                    if ($plan['id'] == $id) {
                        $plan[$attribute]=$value;
                        \Log::info($value);
                    }
                    return $plan;
                });

                session()->put('cart_product', $collection1);
            }
        }
    }

    public static function clear() {
        if (session()->has('cart_product')) {
            $collection1 = session()->get('cart_product');
            foreach ($collection1 as $collection) {
                $collection1->forget($collection);
            }

        }
    }

    public static function item($model) {
        $collection1 = session()->get('cart_product');

        $first_key = $collection1->where('model',$model);
        return $first_key;
    }

    public static function products() {
        $collection1 = session()->get('cart_product');
        if ($collection1)
            return $collection1->all();
        else return null;
    }

    public static function Count() {
        if (session()->has('cart_product')) {
            $collection1 = session()->get('cart_product');
            $quanity = 0;

            foreach ($collection1 as $collection) {
                // \Log::info(print_r($collection,true));
                $quanity += $collection['qty'];
            }
            return $quanity;
        } else return 0;
    }

    public static function Remove($id) {
        $collection1 = session()->get('cart_product');

        $first_key = $collection1->where('id',$id);

        if (count($first_key)>0) {
            $key = array_keys($first_key->toArray());
            $collection1 = $collection1->forget($key[0]);
            return $collection1->count();
        }

        return false;
    }
}
