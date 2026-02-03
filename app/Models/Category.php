<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name'];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public static function categories() {
        return static::selectRaw("*, lower(replace(category_name,' ','-')) as category_url")
            ->get();
    }
}
