<?php

namespace App\Http\Controllers;

use App\Models\ProductRetail;
use Illuminate\Http\Request;

class ProductRetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productretails = ProductRetail::all();
        return view('admin.productretail',['pagename' => 'Product Prices','productretails' => $productretails]);
    }

    public function getRetailPrice(Request $request) {
        $model = $request['model'];
        $p_r = new \App\Libs\RetailPrice;
        $retail = $p_r->RetailPrice($model);
        if ($retail) {
            $price = $retail->p_retail;
            return ['price' => '$' . number_format($price,2)]; //, 'price' => '$' . number_format($price,2), 'percent' => ' ('.$margin.'% off)'];
        }

        return ['price' => '$0.00']; //, 'percent' => ' (0% off)'];
        // $margin = Margins()->get($model);
        // $price = $retail - ($retail * ($margin/100) );
        // \Log::debug($price);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $models = ProductRetail::select('p_model','model_name')->get();

        return view('admin.productretail.create',['pagename' => 'New Product Retail','models' => $models]);
    }

    public static function createProduct($output,$fileName='') {
        $model = strtolower($output['p_model']);

        $data = ['p_retail' => $output['p_retail'],
            'heighest_serial' => $output['heighest_serial'],
            'p_model' => $model,
            'model_name' => strtoupper($model).' - '.ucwords($output['model_name'])
        ];

        if ($fileName)
           $data['image_location'] = $fileName;

        $new_id=\App\Models\ProductRetail::create($data);
    }

    public static function updateProduct($id,$output,$filename='') {
        $productretail=\App\Models\ProductRetail::find($id);
        if ($filename)
            $output['image_location'] = $filename;

        $productretail->update($output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //ProductRetail::create($request->all());

        $this::createProduct ($request->all());
        return redirect('admin/productretail');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductRetail  $productRetail
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductRetail  $productRetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productretail = ProductRetail::find($id);
        if (!$productretail)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        return view('admin.productretail.edit',['pagename' => 'Edit Product Retail', 'productretail' => $productretail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductRetail  $productRetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //$productretail = ProductRetail::find($id);
        //$productretail->update($request->all());
        $is_active = isset($request['is_active']) ? 1 : 0;

        $request['is_active'] = $is_active;
        $this::updateProduct($id,$request->all());
        return redirect('admin/productretail');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductRetail  $productRetail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $productretail = ProductRetail::findOrFail($id);
        $productretail->delete();

        return redirect('admin/productretail');
    }
}
