<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
use PDF;
use App\Models\Product;
use App\Models\ProductRetail;
use App\Models\Order;
use Session;
use DB;
use App\Models\ProductImage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class ProductsController extends Controller
{

    public function lvproducts() {
        return view('admin.lvproducts');
    }

    public function lvmodels() {
        return view('admin.lvmodels');
    }

    public function __invoke($page) {
        return view($page,['activePage' =>$page]);
    }

    public function productDetailsNew($model) {
        return view('product-details');
    }

    public function temp() {
        return view('temp');
    }

    public function productDetails($model) {
        $model = str_replace('-',' ',$model);
        $productArray = array();

        $products = ProductRetail::where('model_name',"LIKE",'%'.$model.'%')->get();
        if ($products->count() > 1) {
            foreach ($products as $product)
                $productArray[] = ['model'=>$product->p_model,"parts"=>$product->total_parts,"size"=>$product->size,'weight'=>$product->weight,'dimensions'=>$product->dimensions,'price' => $product->p_retail];

            // Step 2: Sort to prioritize models ending in "S" over those ending in "L"
            usort($productArray, function ($a, $b) {
                $aPriority = str_ends_with($a['model'], 'S') ? 1 : 2; // Priority: 1 for "S", 2 for "L"
                $bPriority = str_ends_with($b['model'], 'S') ? 1 : 2;

                return $aPriority <=> $bPriority; // Sort by priority
            });

            $filteredProducts = $products->reject(function ($product) {
                return str_ends_with($product->p_model, 'L'); // Exclude SPL, CBL, HGL
            });
            $product = $filteredProducts->first();

        } else $product = $products->first();

        return view('product',['product' => $product, 'productArray' => $productArray]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$ocr = new TesseractOCR();
        //$ocr->image(public_path('/images/ocr.jpg'));

        //print_r(str_replace("\n",'<br />',$ocr->run()));
        //die;
        // $products = Product::latest()->get();
        return view('admin.products',['pagename' => 'Product Page']);
    }

    // public function welcome() {
    //     return view('welcome');
    // }

    // public function sculptures() {
    //     return view('sculptures');
    // }

    public function ajaxGetProductImages() {
        $directory = base_path().'/public/images/gallery/thumbnail';

        if (!is_dir($directory)) {
            exit('Invalid diretory path');
        }

        $files = array();
        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..') {
                $files[] = "<li class='ellipsis p-1 cursor-pointer'><img class='w-5' src='/images/$file' /><span class='p-1'>$file</span></li>";
            }
        }

        return $files;
    }

    public function ajaxSelectFoundProduct(Request $request) {

        if ($request->ajax()) {
            $id = $request['_id'];
            $blade = $request['_blade'];
            $orderPage = false;
            $status = '';

            if (is_numeric($id) && $blade == 'order') {
                $product = Product::find($id);
                $existingOrder = $product->orders->first();

                if ($existingOrder)
                    $status = $existingOrder->method;

                if ($product->p_status != 0 && $product->id != 491 ) { //491 is a miscellaneous item
                    $data['status'] = $status;
                    $data['statusText'] = 'This item is currently in ' . $status .'. If it has returned, please mark it as returned and then try again.<br><a target="_blank" href="/admin/orders/'. $existingOrder->id .'">Click here to go to that order '.$existingOrder->id.'</a> ';
                    return $data;
                }
                $status='';
                $orderPage = true;
            } else {
                // $product = Product::where('p_model',$id)->first();
                // return $id;
                // if (!$product) {
                $productRetail = ProductRetail::find($id);

// return $product;
                    // $productRetail = ProductRetail::where('p_model',$id)->orWhere('model_name',$id)->first();
                    //return $productRetail;
                    //$explode = explode(' - ',$productRetail->model_name);
                    $model = strtoupper($productRetail->p_model);
                    $reference = $productRetail->model_name;
                    if ($productRetail->image_location)
                        $imageElem = '/images/gallery/thumbnail/'.$productRetail->image_location;
                    else
                        $imageElem = '/images/no-image.jpg';

                    $data = [
                        'id'=>$productRetail->id,
                        'image'=>"<img class='tblimg' style='width: 70px' src='$imageElem' />",
                        'imgname' => $imageElem,
                        'title' => "$reference ($model)",
                        'heighestserial' => $productRetail->heighest_serial,
                        'model' => $model,
                        'reference' => $reference,
                        'qty'=>1,
                        'price'=>$productRetail->p_retail / 2,
                        'retail'=>$productRetail->p_retail,
                        'status' => $status
                    ];
                    return $data;

            }

            $imageElem='';
            if ($product->image()) {
                $imageElem = '/images/thumbs/'.$product->image();
            } else {
                $imageElem = '/images/no-image.jpg';
            }

            $product_name= $product->title();
            $retail = $product->retailvalue();
            $model = strtoupper($product->retail->p_model);
            $reference = $product->retail->model_name;

            $data = [
                'id'=>$product->id,
                'image'=>"<img class='tblimg' style='width: 70px' src='$imageElem' />",
                'imgname' => $imageElem,
                'title' => "$product_name ($model)",
                'heighestserial' => $product->retail->heighest_serial,
                'model' => $model,
                'reference' => $reference,
                'qty'=>1,
                'price'=>$retail / 2,
                'retail'=>$retail,
                'status' => $status
            ];

            if ($orderPage)
                $data['serial'] = $product->p_serial;
            else $data['serial'] = $product->p_model;

            return $data;
        }
    }

    public function ajaxGetRetailProducts(Request $request) {
        if ($request->ajax()) {
            // return $request;
            $id = $request['query'];
            $products = \App\Models\ProductRetail::whereRaw("p_model LIKE '%$id%' OR model_name LIKE '%$id%'")->get();

            if (!$products)
                return response()->json(array('error'=>1));

            if ($products) {
                $data=array();
                foreach ($products as $product) {
                    $data['suggestions'][] = array('value'=>$product->p_model,'data' => $product->id);
                }

                return response()->json($data);
            }

            return response()->json(array('error'=>1));
        }
    }

    public function ajaxFindProduct(Request $request) {
        if ($request->ajax()) {
            $id = $request['query'];
            $products = Product::whereRaw("(p_model LIKE '%$id%' or p_serial LIKE '%$id%') AND p_qty>0")
                ->where('p_qty','<>','0')->get();

            if (!$products)
                return response()->json(array('error'=>1));

            if ($products) {
                $data=array();
                foreach ($products as $product) {
                    $product_name=$product->title() .' - '. $product->p_serial;
                    $data['suggestions'][] = array('value'=>$product_name,'data' => $product->id);
                }

                return response()->json($data);
            }

            return response()->json(array('error'=>1));
        }
    }

    public function ajaxCreateEmptyRowForInvoice(Request $request) {
        if ($request->ajax()) {
            ob_start();

            if ($request['_blade'] == 'invoice_edit') {
        ?>
            <tr>
                <td></td>
                <td>
                    <input class="form-control product_id" type="hidden" name="product_id[]">
                    <input type="hidden" name="op_id[]" />
                </td>
                <td><input class="form-control" class="product_name" name="product_name[]" type="text" placeholder="Use Serial field" disabled></td>
                <td><input class="form-control qtycalc" name="qty[]" pattern="\d*" type="number"></td>
                <td>
                    <div class="col-2 input-group">
                        <div class="input-group-addon">$</div>
                        <input style="width: 80px" class="form-control" name="price[]" pattern="^\d*(\.\d{0,2})?$" type="text" value="0">
                    </div>
                </td>
                <td></td>
                <td><input style="width: 100px" class="form-control serial-input" name="serial[]" placeholder="SPS" type="text"></td>
                <td style="text-align: center">
                <button type="button" style="text-align:center" class="btn btn-primary deleteitem" aria-label="Left Align">
                    <i class="fas fa-trash-alt" aria-hidden="true"></i>
                </button>
                </td>
            </tr>
        <?php  } elseif ($request['_blade'] == 'create-estimator') { ?>
            <tr>
                <td></td>
                <td ><input type="text" class="form-control" name="product_name[]" placeholder="Use Serial field" disabled /> </td>
                <td><input type="text" class="form-control" style="width: 50px" name="qty[]" /></td>
                <td style="text-align: right">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="price[]"></input>
                    </div>
                </td>
                <td style="text-align: right"><input style="width:80px" type="text" placeholder="SPS" class="form-control serial-input" name="serial[]" /></td>
                <td style="text-align: right;">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="retail[]"></input>
                    </div>
                </td>
                <td style="width: 30px;text-align: center">
                    <a class="btn btn-danger deleteitem nonsubmit"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </td>
            </tr>
        <?php } elseif ($request['_blade'] == 'invoice_new') { ?>
            <tr>
                <td>
                    <input type="hidden" name="product_id[]" />
                    <input type="hidden" name="model[]" />
                    <input type="hidden" class="tblimg" name="img_name[]" />
                </td>
                <td ><input type="text" class="form-control" name="product_name[]" placeholder="Use Serial field" disabled /> </td>
                <td><input type="text" class="form-control" style="width: 50px" name="qty[]" /></td>
                <td style="text-align: right">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="price[]"></input>
                    </div>
                </td>
                <td style="text-align: right"><input style="width:80px" type="text" placeholder="SPS" class="form-control serial-input" name="serial[]" /></td>
                <td style="text-align: right;">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="retail[]"></input>
                    </div>
                </td>
                <td style="width: 30px;text-align: center"></td>
            </tr>
        <?php } elseif ($request['_blade'] == 'create-order-estimator') { ?>
                <tr>
                <td></td>
                <td>
                    <input style="width: 40px" type="hidden" name="index[]" value="{{ $product->id }}">
                    <input style="width: 40px" type="hidden" name="p_model[]" value="{{ $product->p_model }}">
                    <input class="form-control" name="product_name[]" placeholder="Use Serial field" disabled>
                </td>
                <td><input style="width: 50px" type="text" class="form-control" name="qty[]"></td>
                <td style="text-align: right">
                    <div class="input-group">
                    <div class="input-group-addon">$</div>
                        <input style="width: 80px" type="text" name="price[]" class="form-control">
                    </div>
                </td>
                <td><input style="width: 80px" autocomplete="off" class="form-control serial-input" type="text" name="serial[]"></td>
                <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
                <td style="text-align: right;">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="retail[]"></input>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm deleteitem" aria-label="Left Align">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm backorder" aria-label="Left Align">Backorder</button>
                </td>
                </tr>
                <?php }

            $content = ob_get_clean();
            return response()->json($content);
        }
    }

    public function ajaxEstimatedProducts(Request $request) {
        if ($request->ajax()) {
            $blade = $request['_blade'];

            if ($blade!='create-order-estimator') {
                $products = Product::select('id','p_title','p_reference','p_model')
                    ->whereIn('p_model',['CBL','CBS','SKL','SKS','SPL','SPS','FRL','FRS','LS-S','LS-L','HGS','HGL','CBL-SK','CBL-GR','CBL-GA','CBL-HA'])
                    ->groupBy('p_model')
                    ->orderBy('sort_order')
                    ->limit(16)
                    ->get();
                $content=view('admin.estimated-products-ajax',['products' => $products,'selection'=>'partial']);
                return response()->json(['content'=>$content->renderSections(),'selection'=>'partial']);
            } else {
                $products = Product::latest()->get();
                $content=view('admin.estimated-products-ajax',['products' => $products,'selection'=>'all']);
                return response()->json(['content'=>$content->renderSections(),'selection'=>'all']);
            }

        }
    }

    public function getAll(Request $request) {
        if ($request->ajax()) {
            $total=0;$qty=0;

            if ($request['display']=='Display not on-hand')
                $display=0;
            else $display=1;

            $grandTotalPrice = 0;$grandTotalRetail = 0; $qty=0;
            $products = Product::with('categories');

            if ($display==1)
                $products=$products->QtyGreaterThanZero();
            else $products=$products->QtyEqualToZero();

            $products=$products->OrderByQtyID()->get();

            $data=array();

            foreach ($products as $product) {
                $retail_value= $product->retailvalue();
                $title = $product->title();
                $status = "<span class='availability'>".Status()->get($product->p_status) . "</span>";
                $data[]=array('',$product->id,$title.$status,$product->p_serial,'$'.number_format($retail_value/2,0),'$'.number_format($retail_value,0),$product->p_qty,$product->created_at->format('m/d/Y'));
                // if ($product->p_qty>0) {
                    $qty += $product->p_qty;
                    $grandTotalRetail += $retail_value ? $retail_value : 0;
                    $grandTotalPrice += ($retail_value/2);
                // }
            }

            return response()->json(array('data'=>$data,'totalprice'=>'$'.number_format($grandTotalPrice,2),'totalretail'=>'$'.number_format($grandTotalRetail,2),'qty'=>$qty));
        }
    }

    public function ajaxProducts(Request $request) {
        if ($request->ajax()) {
            $products = Product::latest()->get();

            $content=view('admin.products-ajax',['products' => $products]);

            return response()->json($content->renderSections());
        }
    }

    public function printMissingProducts($model) {

        for ($i=1;$i <= 400; $i++){
            $serials_not[]=$i;
        }

        $models = ProductRetail::select('p_model')->where("is_active",1)->pluck('p_model')->all();
        //dd($models);
        $excludeModels = array('LSL','FRL',
            'FRS','WSL','LSS','MISC','QNS',
            'SPBB','SKL-XL','FRL-L','FRS-L',
            'PNT','BRI-BRS','BRI-BRL','FRL-SBY');

        foreach ($models as $model) {
        if (in_array($model,$excludeModels)) continue;
        $products = Product::select('p_serial')->where('p_model', $model)->get();
        if ($products->isEmpty()) return 'No model was found with the name: '.$model;

        $serials_are = array();
        foreach ($products as $product) {
            $serials_are[] =$product->p_serial;
        }

        $p = array_values(array_diff($serials_not,$serials_are));

        // set document information

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        PDF::setHeaderCallback(function($pdf) {
            // Logo
            $pdf->SetFont('helvetica', 'I', 8);
            // Page number
            $pdf->Cell(0, 10, date('F d, Y',time()), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        PDF::setFooterCallback(function($pdf){
            // Position at 15 mm from bottom
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('helvetica', 'I', 8);
                // Page number
            $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        // set header and footer fonts
        $pdf::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-10, PDF_MARGIN_RIGHT);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(PDF_MARGIN_FOOTER-15);

        // set auto page breaks
        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-7);

        // set image scale factor
        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        // ---------------------------------------------------------
        // add a page
        $pdf::AddPage();

        $pdf::SetFont('helvetica', '', 10);
        $start_page = $pdf::getPage();

        ob_start();$c=0;$max=6;
        ?>
            <table cellpadding="3" border="1" nobr="true">
                <thead>
                    <tr>
                        <?php for($i=0;$i < $max;$i++) { ?>
                        <th style="width: 50px"><b>Model</b></th>
                        <th style="width: 60px"><b>Serial#</b></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php for($j=0;$j < count($p);$j++) { ?>
                        <?php if ($c+$max>=count($p)) { ?>

                            <tr>
                            <?php for($i=$c;$i<count($p);$i++) { ?>
                                <td style="width: 50px"><?= $model ?></td>
                                <td style="width: 60px"><?= $p[$i] ?></td>
                            <?php } ?>
                            <?php $c+=$max; ?>
                            </tr>
                            <?php break;
                        }?>
                        <tr>
                        <?php for($i=$c;$i<$max+$c;$i++) { ?>
                            <td style="width: 50px"><?= $model ?></td>
                            <td style="width: 60px"><?= $p[$i] ?></td>
                        <?php } ?>
                        <?php $c+=$max; ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php

        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');

        }
        //Close and output PDF document
        PDF::Output('inventory.pdf', 'I');
    }

    public function print() {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


        // set auto page breaks
        //$pdf::SetAutoPageBreak(TRUE, 30);

        // set image scale factor
        //$pdf::setImageScale(1);


        // define barcode style
        $style = array(
            'position' => '',
            'align' => '',
            //'stretch' => true,
            //'fitwidth' => false,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            //'fgcolor' => array(0,0,128),
            //'bgcolor' => array(255,255,128),
            //'text' => true,
            //'label' => 'CUSTOM LABEL',
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 0
        );

        // set font
        $pdf::SetFont('helvetica', '', 11);

        // add a page
        $pdf::AddPage();

        // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
        $pdf::write1DBarcode('1234567', 'I25', '', '', '', 18, 0.4, $style, 'N');

        PDF::Output('barcode.pdf', 'I');
    }

    public function printInventory($id,$params,$date='') {
        $qty=1;
        $title = '';
        $countSold='';
        if ($params=='all') {
            $products = Product::orderBy('p_model')
            ->get();
            $title = 'Berd Vaye Products';
        } elseif ($params=='stock'){
            $products = Product::where('p_qty',1)
            ->where('p_status',0)
            ->orderBy('p_model')
            ->get();
            // $products = Product::whereHas('orders',function($query) {
            //     $query->where('method', 'Invoice');
            // })->where('p_qty',1)
            // ->orderBy('p_model')
            // ->get();
            // dd($products);
            $title = 'Berd Vaye Products Instock';
        } elseif ($params=='sold'){
            $products = Product::join('order_product','product_id','=','products.id')
                ->join('orders','orders.id','=','order_product.order_id')
                ->where('order_product.serial','<>',0)
                ->where('p_qty',0)
                ->where('method','Invoice')
                //->where('p_status','>',0)
                ->orderBy('p_model');

            if ($date) {
                $products=$products->whereYear('orders.created_at','=',$date);
                $title = "Berd Vaye Products Instock ($date)";
            } else
                $title = 'Berd Vaye Products Instock';

            $products=$products->get();
            $countSold = 1;

        } elseif ($params=='nomemo') {
            // $products = Product::leftJoin(DB::raw("(select order_id, product_id, method
            //     FROM order_product
            //     INNER JOIN orders ON order_product.order_id = orders.id
            //     WHERE orders.method <>  'On Memo') a"), function ($join) {
            //         $join->on('products.id', '=', 'a.product_id');
            //     })
            //     ->orderBy('products.id', 'asc')
            //     ->get();
            // $products = Product::select('p_qty','p_serial','p_title','p_reference','p_model')->whereIn('id', function ($query) {
            //     $query->select("product_id")
            //         ->from('order_product')
            //         ->join('orders','orders.id','=','order_product.order_id')
            //         ->where('method','<>','On Memo');
            // })
            // ->orderBy('p_model')
            // ->get();
            $products = Product::where('p_qty','=',1)
                //->where('p_status','=',0)
                ->orderBy('p_model')
                ->get();
            $title = 'Berd Vaye Products Not In-Memo';
        }else {
            $qty = 0;
            $products = Product::where('p_qty',0)
            ->orderBy('p_model')
            ->get();
            $title = 'Berd Vaye Products Not Instock';
        }

        // set document information

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        PDF::setHeaderCallback(function($pdf) use ($title){
            // Logo
            $pdf->SetFont('helvetica', 'I', 8);
            // Page number
            $pdf->Cell(0, 10, $title . ' - '.date('F d, Y',time()), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        PDF::setFooterCallback(function($pdf){
            // Position at 15 mm from bottom
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('helvetica', 'I', 8);
                // Page number
            $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        // set header and footer fonts
        $pdf::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-10, PDF_MARGIN_RIGHT);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(PDF_MARGIN_FOOTER-15);

        // set auto page breaks
        $pdf::SetAutoPageBreak(TRUE, 32);

        // set image scale factor
        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        // ---------------------------------------------------------
        // add a page
        $pdf::AddPage();

        $pdf::SetFont('helvetica', '', 10);
        $count = 0;$sub_count=0;$oldModel='';
        ob_start();
        $total_price = 0;
        ?>
            <table cellpadding="3" border="1">
                <thead>
                    <tr>
                        <th style="width: 50px"><b>Model</b></th>
                        <th style="width: 250px"><b>Product Name</b></th>
                        <th style="width: 60px"><b>Serial#</b></th>
                        <th><b>Retail</b></th>
                        <th><b>Price</b></th>
                        <th style="width: 50px"><b>Qty</b></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) { ?>
                    <?php
                        $price = $product->retailvalue() / 2;
                        if ($product->p_model==$oldModel || $oldModel =='') {
                            if($product->p_qty>0 && $params=='all') {
                                $sub_count ++;
                                $total_price += $price;
                            } elseif ($params!='all' && $params!='nomemo') $sub_count ++;
                            elseif ($params=='nomemo' && $product->p_qty>0) $sub_count ++;
                        } else { ?>
                            <tr nobr="true">
                                <td></td>
                                <td></td>
                                <td style="background-color: #b9b9b9"><b>Subtotal</b></td>
                                <td style="background-color: #b9b9b9"></td>
                                <td style="background-color: #b9b9b9"></td>
                                <td style="background-color: #b9b9b9"><b><?= ($sub_count) ?></b></td>
                            </tr>
                            <?php
                            $sub_count = 1;
                        }
                    ?>
                    <tr nobr="true">
                        <td style="width: 50px"><?= $product->p_model ?></td>
                        <td style="width: 250px"><?= ' '.$product->title().' '.$product->p_reference ?></td>
                        <td style="width: 60px"><?= $product->p_serial ?></td>
                        <td><?= number_format($product->retailvalue(),2) ?></td>
                        <td><?= number_format($price, 2) ?></td>
                        <?php if($product->p_qty<$qty) {?>
                            <td style="width: 50px;background-color:#e49a9a"><?= $product->p_qty ?></td>
                            <? if ($countSold) {
                                 $count ++;
                            }?>
                        <?php } else { ?>
                            <td style="width: 50px"><?= $product->p_qty ?></td>
                            <?php $count ++; ?>
                        <?php } ?>
                        <?php $oldModel= $product->p_model?>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="4" style="text-align:right;background-color: #b9b9b9"><b>Subtotal</b></td>
                        <td style="background-color: #b9b9b9"></td>
                        <td style="background-color: #b9b9b9"><b><?= $sub_count ?></b></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right"><b><?= $qty==0 ? 'Total Out of Stock' : 'Total On Hand' ?></b></td>
                        <td><b><?= $total_price>0 ? '$' . number_format($total_price,2) : '' ?></b></td>
                        <td><b><?= $count ?></b></td>
                    </tr>
                </tfoot>
            </table>

        <?php

            $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');

        //Close and output PDF document
        PDF::Output('inventory.pdf', 'I');
    }

    public function export() {
        // $products = Product::whereHas('retail', function() {

        // });
        // foreach ($products as $product) {
        //     dd($product->retail->model_name);
        // }

        //return Excel::download(new ProductsExport, 'public/uploads/products.xlsx');
        $products = Product::join('product_retails', 'product_retails.id','=', 'products.product_retail_id')
                ->selectRaw('model_name,product_retails.p_model, p_retail,p_qty,p_serial,image_location')
                ->where('p_qty', '>', 0)
                ->where('p_retail', '>', 0)
                ->get();

        return Excel::download(new ProductsExport($products), 'products.xlsx');
    }

    public function ajaxgetProduct(Request $request) {

        if ($request->ajax()) {
            $ids = $request['_ids'];
            $blade = $request['_blade'];

            $products = Product::whereIn('id',$ids)->get();

            ob_start();
            foreach ($products as $product) {
                if ($blade == 'create') {
                ?>
                    <tr>
                        <td><img style="width: 80px" src="/images/thumbs/<?=  $product->image()  ?>"</td>
                        <td>
                            <input type="hidden" value="<?= $product->id ?>" name="product_id[]">
                            <input type="text" value="<?= $product->title() .' ('.$product->p_model.')' ?>" name="product_name[]"/>
                        </td>
                        <td><input style="width: 50px" type="text" name="qty[]" value="1"></td>
                        <td><?= $product->p_qty ?></td>
                        <td>$<input style="width: 100px" type="text" name="price[]" value="<?= $product->p_price ?>"></td>
                        <td><input type="hidden" name="serial[]" value="<?= $product->p_serial ?>">
                            <?= $product->p_serial ?></td>
                        <td style="text-align: center">
                            <button type="button" class="btn btn-warning deleteitem btn-sm" aria-label="Left Align">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                } elseif ($blade=='create-order-estimator') {
                    ?>
                    <tr>
                        <td><img style="width: 80px" src="/images/thumbs/<?=  $product->image()  ?>"</td>
                        <td>
                        <input type="hidden" name="model[<?= $product->id?>]" value="<?= $product->p_model ?>" />
                            <?= ' '.$product->title() . ' ' . $product->p_reference ?></td>
                        <td><input style="width: 50px;" type="text" name="qty[<?= $product->id?>]" value="1"></td>
                        <td>$<input style="width: 80px" type="text" name="price[<?= $product->id?>]" value="<?= $product->p_price ?>"></td>
                        <td><input autocomplete="off" style="width: 80px" type="text" name="serial[<?= $product->id?>]" required /></td>
                        <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
                        <td>
                            <button type="button" class="btn btn-warning deleteitem btn-sm" aria-label="Left Align">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                } elseif ($blade=='memotransfer') {
                    ?>
                    <tr>
                        <td><img style="width: 80px" src="/images/thumbs/<?=  $product->image()  ?>"</td>
                        <td>
                        <input type="hidden" name="model[<?= $product->id?>]" value="<?= $product->p_model ?>" />
                            <?= ' '.$product->title() . ' ' . $product->p_reference ?></td>
                        <td><input style="width: 50px;" type="text" name="qty[<?= $product->id?>]" value="1"></td>
                        <td>$<input style="width: 80px" type="text" name="price[<?= $product->id?>]" value="<?= $product->p_price ?>"></td>
                        <td><input autocomplete="off" style="width: 80px" type="text" name="serial[<?= $product->id?>]" value="<?= $product->p_serial ?>" required /></td>
                        <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
                        <td>
                            <button type="button" class="btn btn-warning deleteitem" aria-label="Left Align">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                } elseif ($blade=='edit-estimator') {
                    ?>
                    <tr>
                        <td><img style="width: 80px" src="/images/thumbs/<?=  $product->image()  ?>" /></td>
                        <td><?= ' '.$product->title() . ' ' . $product->p_reference ?> </td>
                        <td><input style="width: 50px" type="text" name="qty[<?= $product->id?>]" value="1"></td>
                        <td style="text-align: right"><?= number_format($product->p_retail,2)  ?></td>
                        <td style="text-align: right">$<input style="width: 100px" type="text" name="price[<?= $product->id?>]" value="<?= $product->p_price ?>"></td>
                        <td style="text-align: right">
                            <input type="hidden" name="id[<?= $product->id?>]" value="new" />
                            <button type="button" class="btn btn-warning deletenew" aria-label="Left Align">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                } elseif ($blade=='create-estimator') {
                    ?>
                    <tr>
                        <td><img style="width: 80px" src="/images/thumbs/<?=  $product->image()  ?>" /></td>
                        <td><?= $product->title() . ' ' . $product->p_reference .' ('.')' ?> </td>
                        <td><input style="width: 50px" type="text" name="qty[<?= $product->id?>]" value="1"></td>
                        <td style="text-align: right"><?= number_format($product->p_retail,2)  ?></td>
                        <td style="text-align: right">$<input style="width: 100px" type="text" name="price[<?= $product->id?>]" value="<?= $product->p_price ?>"></td>
                        <td>
                            <button type="button" class="btn btn-warning deleteitem" aria-label="Left Align">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                <?php
                } else {
                    ?>
                    <tr>
                        <td><img style="width: 80px" src="/images/thumbs/<?=  $product->image()  ?>" /></td>
                        <td>
                            <input type="text" value="<?= $product->title() . ' ' . $product->p_reference ?>" name="product[name]" />
                            <input type="hidden" name="product[id]" value="<?= $product->id ?>" />
                        </td>
                        <td><?= $product->p_qty ?></td>
                        <td style="text-align: right"><input type="hidden" name="product[serial]" value="<?= $product->p_serial ?>">
                            <?= $product->p_serial ?></td>
                        <td style="text-align: right"><?= number_format($product->p_retail,2)  ?></td>
                        <td style="text-align: right">$<input style="width: 100px" type="text" name="product[price]" value="<?= $product->p_price ?>"></td>
                        <td style="text-align: right">
                            <button class="btn btn-warning deleteitem" data-id="<?= $product->id ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                <?php
                }
            }

            $content = ob_get_clean();
            return response()->json($content);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.create',['pagename' => 'New Product']);
    }

    public function duplicate($id='')
    {
        if ($id) {
            $product = Product::find($id);
            return view('admin.products.duplicate',['pagename' => 'New Product','product' => $product]);
        }

        return view('admin.products.duplicate',['pagename' => 'New Product']);
    }

    public function passInfo(Request $request) {
        return $request;
    }

    public function storeDuplicate(Request $request,$id=''){
        $message = ['serial.unique' => 'The serial number must be a unique number. Please correct the problem and try again.'];
        $hasId ='NULL';
        $validator = \Validator::make($request->all(), [
            //'condition' => 'required',
            'title' => 'required',
            'reference' => 'required',
            'model' => 'required',
            'quantity' => 'required',
            'serial' => 'required|unique:products,p_serial,NULL,id,p_model,'.$request['model'],
            'size' => 'required',
            'price' => 'required',
            'retail' => 'required',
            'heighest_serial' => 'required'
        ], $message);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        }

        $this->store($request,$id);

        return redirect('admin/products');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id='')
    {
        if (!$id) {
            $message = ['serial.unique' => 'The serial number must be a unique number. Please correct the problem and try again.'];
            $validator = \Validator::make($request->all(), [
                //'condition' => 'required',
                'model_name' => 'required',
                'p_qty' => 'required',
                'p_serial' => 'required|unique:products,p_serial,NULL,id,p_model,'.$request['p_model'],
            ]);


            if ($validator->fails()) {
                return back()->withInput($request->all())->withErrors($validator);
            }
        }
        //dd($request->all());
        $order = Order::with('products')->whereHas('products', function($query) {
            $query->where('serial','Backorder');
            $query->orWhere('qty','-1');
        })->orderBy('created_at', 'asc')->first();

        if ($order) {
            foreach ($order->products as $product) {
                if ($product->pivot->serial == 'Backorder' && $request['p_model'] == $product->pivot->model) {
                    $new_product = Product::create($request->all());
                    session()->put('order_reminder',
                        array(
                            'order_id'=>$order->id,
                            'item' => $product->pivot->product_name,
                            'price' => $product->pivot->price,
                            'model' => $product->pivot->model,
                            'product_id' => $new_product->id,
                            'serial' => $new_product->p_serial
                        )
                    );

                    return redirect('admin/products');
                }
            }
        }
        // dd($request->all());
        $product = Product::create($request->all());
        return redirect('admin/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product)
            return response()->view('errors/admin-notfound',['id'=>$id],404);


        return view('admin.products.show',['pagename' => 'Product - '.$product->p_reference.'('.$product->p_model.')', 'product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        return view('admin.products.edit',['pagename' => 'Edit Product', 'product' => $product]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = ['serial.unique' => 'Combination of serial number and model number already exists, please enter another serial number.'];
        $product = Product::find($id);
        $validator = \Validator::make($request->all(), [
            'quantity' => 'required',
            'serial' => 'required|unique:products,p_serial,'.$product->id.',id,p_model,'.$product->p_model,
        ],$message);

        if ($validator->fails()) {
            return back()->withInput($request->all())->withErrors($validator);
        } else {
            $qty = $request['quantity'];

            if ($request['p_status'] >=3 && $request['p_status'] != 5)
                $qty = 0;

            $productData = [
                'p_qty' => $qty,
                'p_model' => $request['model'],
                'p_status' => $request['p_status'],
                'product_retail_id' => $request['_id'],
                'p_serial' => $request['serial'],
                'comments' => $request['comments']
            ];

            // dd($productData);
            $product->update($productData);

            $product->save();
            return redirect('admin/products');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product = Product::find($id);
        $product->delete();

        Session::flash('message', "Successfully deleted product!");
        return redirect('admin/products');
    }
}
