<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;

class WatchesController extends Controller
{
    private function loadFilteredProducts(Request $request, $id='',$name='',$status='') {
        if ($id == 'watches') $id=$name;
        
        if (count($request->all())==0) {
            $products = Product::with('categories')->paginate(12);
            $name = 'All Brands';
        } 

        $products = $this->filter($id,$request);

        if (!$products->isEmpty())
            $name=$products->first()->categories->category_name;
        elseif ($id)
            $name = Category::find($id)->category_name;
            
        return $products;
    }

    public function show (Request $request, $id='',$name='',$status='') {
        $categories = Category::all();
        $products = $this->loadFilteredProducts($request, $id, $name);      

        return view('products.product', ['products' => $products,'currentPage'=>$name,'categories' => $categories]);
            
    }

    public function welcome (Request $request, $id='',$name='',$status='') {
        $categories = Category::all();
        $products = $this->loadFilteredProducts($request, $id,$name);

        if ($id == 'watches') {
            $category = Category::find($name);
            $name = $category->category_name;
            
            return view('products.product', ['products' => $products,'currentPage'=>$name,'categories' => $categories]);            
        }
        
        return view('welcome', ['products' => $products,'currentPage'=>'All Brands','categories' => $categories]);
            
    }

    public function search (Request $request, $id='',$name='',$status='') {
        
        $categories = Category::all();
        $term = $request['p'];
        
        $products = Product::whereHas('categories', function($query) use($term,$request) {
            $query->where('category_name', 'like', '%'.$term.'%');
            $condition='';$price='';$status='';

            if (isset($request['price'])) {
                $price = $request['price'];
                $prices = $this->getPrices($price);
                $query->whereBetween('p_price',$prices);  
            } 
            if (isset($request['condition'])) {
                $condition = $request['condition'];
                $condition_key = $this->getCondition($condition);
                $query->where('p_condition',$condition_key);
            } 
            
            if (isset($request['status'])) {
                $status = $request['status'];
                $status = $this->getStatus($status);
                $query->where('p_status',$status);
            }

        })->orWhere('p_model','LIKE','%'.$term.'%')->paginate(12);
            
        return view('search',['products' => $products,'currentPage'=>'Results for: '.$term,'categories' => $categories]);
    }

    private function getCondition($condition) {
        
        foreach (Conditions() as $key => $_condition) {
            $condition = str_replace('-',' ',$condition);
            if (strtolower($_condition) == $condition) {
                return $key;
            }
        }

        return '';
    }

    private function getStatus($status) {
        foreach (status() as $key => $_status) {
            $status = str_replace('-',' ',$status);
            if (strtolower($_status) == strtolower($status)) {
                
                return $key;
            }
        }

        return '';
    }

    private function getPrices($price) {
        $prices = explode("-",$price);
        if (!$prices[1]){
            $prices[1] = '9999999999999';
            return $prices ;
        }elseif (!$prices[0]){
            $prices[0] = '0';
            return $prices;
        }else return $prices;
    }

    private function filter($id='',$request) {

        $products = Product::with('categories');

        if (isset($request['condition'])) {
            $condition = $request['condition'];
            $condition_key = $this->getCondition($condition);
            $products = $products->where('p_condition',$condition_key);
        }

        if ($id)
            $products = $products->where('category_id',$id);
                
        if (isset($request['price'])) {
            $price = $request['price'];
            $prices = $this->getPrices($price);
            $products = $products->whereBetween('p_price',$prices);
        }

        if (isset($request['status'])) {
            $status = $request['status'];
            $status = $this->getStatus($status);
            
            $products = $products->where('p_status',$status);
        }

        $products = $products->paginate(12);

        return $products;
    }

}
