<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $orders = Order::selectRaw('date_format(created_at,"%Y-%m-%d") date, sum(subtotal) total')
        ->groupBy('date')
        ->get();

        $products = DB::table('order_product')->selectRaw("count(products.p_model) c_model,products.p_model,model_name")
        ->join('products','products.id','=','product_id')
        ->join('product_retails','product_retails.p_model','=','products.p_model')
        ->groupBy('products.p_model')
        ->get();


        $backorders = Order::with('products')->whereHas('products', function($query) {
            $query->where('serial','Backorder');
        })->orderBy('created_at', 'asc')->get();

        $repairs = Order::with('products')->whereHas('products', function($query) {
            $query->whereNotNull('repair_date');
            $query->whereNull('returned_date');
        })->get();

        $repairOrders = array();
        if ($repairs) {
            foreach ($repairs as $order) {
                foreach($order->products as $product) {
                    if ($product->pivot->repair_date)
                        $repairOrders[]=['id'=>$order->id,'company'=>$order->b_company,'product'=>$product->pivot->product_name, 'serial'=>$product->pivot->serial];
                }
            }
        }

        $from = date('Y-m-d',strtotime("0 days"));
        $invoices = Order::where("status",0)
            ->where('created_at', '<=', $from)
            ->get();
        
        return view('admin.dashboard',
            [
                'pagename'=>'Dashboard', 
                'orders'=>$orders,
                'products'=>$products,
                'backorders'=>$backorders,
                'repairs'=>$repairOrders,
                'invoices'=>$invoices
            ]
        ); 

    }
}
