<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use App\Models\Order;
use App\Models\Credit;
use App\Models\Returns;
use App\Models\OrderReturn;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class ReturnsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returns = Returns::with('orders')->get();   
        return view('admin.returns',['pagename' => 'Returns','returns' => $returns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {        
        $order = Order::find($id);
        if (!$order)
            return response()->view('errors/admin-notfound',['id'=>$id],404);

        $productName = 'Create Return Order # '.$order->id;
        return view('admin.returns.create',['pagename' => $productName,'order' => $order]);
    }

    public function ajaxReturnAll(Request $request) {
        if ($request->ajax()) {
            parse_str($request['_form'],$output);

            //return response()->json($output);
            
            $order_id = $output['order_id'];
            $order = Order::find($order_id);
            $comment = $output['comments'];

            $return = OrderReturn::where('order_id',$order_id)->first();
            $totalAmount = 0; //
            
            if (!$return) {
            
                $return = Returns::create ([
                    'comment' => $comment
                ]);
                $total = $order->subtotal;
            } else {
                $return=Returns::find($return->returns_id);
                
                $return->update ([
                    'comment' => $comment
                ]);

                $total = $order->returns->sum('pivot.amount');
                $total = $order->subtotal-$total;
            }

            for ($i=0;$i < count($output['product_id']);$i++) {
                $product_id = $output['product_id'][$i];
                $product = $order->products->find($product_id);

                $amount=($product->pivot->price*$product->pivot->qty);
                $totalAmount += $amount;
                $total -= ($product->pivot->price*$product->pivot->qty);
            
                $order->returns()->attach($return,[
                    'product_id' => $product_id,
                    'order_id' => $order_id,
                    'amount' => $amount,
                    'qty' => 1,
                ]);

                $product->update([
                    'p_qty' => 1,
                    'p_status' => 0
                ]);

                // if ($order->method=='Invoice') {
                //     if ($product->p_status!=4)
                //         $product->update([
                //             'p_qty' => 1,
                //             'p_status' => 0
                //         ]);
                    
                // } elseif ($order->method=='On Memo' || $order->method=='Repair') {
                        
                //     if ($product->p_status!=4)
                //         $product->update([
                //             'p_status' => 0,
                //             'p_qty' => 1
                //         ]); 
                // }
            }

            if ($total <= 0) 
                $order->update(['status' => 3]);

            if ($order->payments->sum('amount')) {
                $customer_id=$order->customers()->first()->id;
                $credit = Credit::where('customer_id',$customer_id)
                    ->whereNull('amount')
                    ->orderBy('id','desc');
                
                if (!$credit->first()) {
                    Credit::create([
                        'customer_id' => $customer_id,
                        'previous_amount' => $totalAmount - $order->discount //-$amountOwed
                    ]);

                } else {
                    $credit->update([
                        'previous_amount' => $credit->amount+$totalAmount//-$amountOwed
                    ]);
                }
            }
            
            return response()->json(date('m-d-Y'));
        }
    }

    public function ajaxReturnItem(Request $request){
        
        if ($request->ajax()) {
            
            $id = $request['_id'];
            $order_id = $request['_orderid'];
            $qty =$request['_qty'];
            $product_id = $request['_productid'];
            $comment = $request['_comment'];

            $order = Order::find($order_id);
            $return = OrderReturn::where('order_id',$order_id)->first();
            $product = $order->products->find($product_id);
            
            $amount=$product->pivot->price;
            $serial = $product->pivot->serial;
            $total = $order->subtotal; // 4250

            if (!$return) {
            
                 $return = Returns::create ([
                    'comment' => $comment
                ]);
               //$total = $order->subtotal; // 4250
            } else {
                $return=Returns::find($return->returns_id);
                
                $return->update ([
                    'comment' => $comment
                ]);

                //$total = $order->subtotal - $order->returns->sum('pivot.amount');
                foreach ($order->returns as $return) {
                    $total -= $return->pivot->amount * $return->pivot->qty;
                }
    
            }
            
            // 4250 - 1450
            $total -= $product->pivot->price * $product->pivot->qty;
            $paidAmount = $order->payments->sum('amount'); // 4250

            //return $paidAmount;
            $status = $order->status;
            
            $order->returns()->attach($return,[
                'product_id' => $product_id,
                'amount' => $amount, // 1450
                'qty' => $qty,
            ]);

            $product->update([
                'p_qty' => 1,
                'p_status' => 0
            ]);

            if ($order->method=='Invoice') {
                if ($total == 0) 
                    $order->update(['status' => 3]);
                
            } elseif ($order->method=='On Memo' || $order->method=='Repair') {
                if ($total == 0)
                    $order->update(['status' => 3]); 
            }

            if ($paidAmount) {
                $customer_id=$order->customers()->first()->id;
                $credit = Credit::where('customer_id',$customer_id)->first();
                
                if (!$credit) {
                    Credit::create([
                        'customer_id' => $customer_id,
                        'amount' => $amount, //-$amountOwed
                        'ref' => 'return'
                    ]);
                } else {
                    $credit->update([
                        'amount' => $credit->amount+$amount//-$amountOwed
                    ]);
                }
            }
            
            $order_reminder=null;
            $order = Order::with('products')->whereHas('products', function($query) {
                $query->where('serial','Backorder');
            })
            ->where('id','<>',$order->id)
            ->orderBy('created_at', 'asc')->first();
    
            if ($order) {
                $order_reminder = array();
                foreach ($order->products as $product) {
                    if ($product->pivot->serial == 'Backorder' && $request['_model'] == $product->pivot->model) {
                        $order_reminder = array(
                            'order_id'=>$order->id,
                            'item' => $product->pivot->product_name,
                            'product_id' => $product_id,
                            'serial' => $serial
                        );
                    }
                }
            }
            
            return response()->json(array('qty'=>$product->pivot->qty,'date' => date('m-d-Y'),'order_reminder' => $order_reminder));
        }
    }

    public function print($id) {
        $order=Order::find($id);
        //dd($order->returns);
        foreach ($order->returns as $return) {
            $this->printReturn($order,$return);
        }
    }

    protected function printReturn($order,$return) {
        // set document information

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        PDF::setHeaderCallback(function($pdf){
            // Logo
            $image_file = '/public/images/berdvaye-logo-pdf.png';   
            $pdf->Image($image_file, 14, 10, 35, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            // Set font
            //$pdf->SetFont('helvetica', 'T', 10);
            // Title            
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
        $pdf::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf::SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf::SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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
        $orderStatus = '';
        
        // if ($order->status==0) {
        //     $orderStatus = "Invoice";
        // } else {
        //     $orderStatus = "Order";
        // }
        if ($order->method == 'On Memo'  || $order->method=='Repair'){
            $orderStatus = "Memo Return";
        } else $orderStatus = "Invoice Return";

        $pdf::setXY($pdf::getPageWidth()-85,20);
        ob_start();
        ?>
        <table cellpadding="3">
            <tr>
                <td style="text-align:right"><div style="font-size:25px;color:#6b8dcb;font-weight:bold"><?= $orderStatus?></div></td>
            </tr>
            <tr>
                <td style="font-size: 12px;text-align:right;font-family:helvetica"><?= $return->pivot->created_at->format('F d, Y') ?></td>
            </tr>
            <tr>
                <td style="font-size: 12px;text-align:right;font-family:helvetica"><?= $orderStatus . " No: " . $return->id ?></td>
            </tr>
        </table>
        <?php
        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, ''); 

        //$pdf::writeHTMLCell(40, 10, $pdf::getPageWidth()-46, 23, '<div style="font-size:25px;color:#6b8dcb;font-weight:bold">'.$orderStatus.'</div>', 0, 0, 0, false, 'L', false);
        $pdf::SetFont('helvetica', '', 10);
        //$pdf::setXY($pdf::getPageWidth()-50,33);
        //$pdf::Write(0, date('F d, Y',time()), '', 0, 'L', true, 0, false, false, 0);
        $pdf::setY(24);
        $pdf::WriteHTML('68 Grand Street<br>New City, NY 10956<br>833.237.3829<br>www.berdvaye.com', true, false, false, false, '');
        // -----------------------------------------------------------------------------

        $countries = new \App\Libs\Countries;
        $country = $countries->getCountry($order->b_country);
        $state_b = $countries->getStateCodeFromCountry($order->b_state);
        $state_s = $countries->getStateCodeFromCountry($order->s_state);
        $pdf::setY(45);
        ob_start();
        ?>
            <table cellpadding="1">
                <tr>
                    <td style="width: 43%;background-color:#111;color:#fff">
                        <b>To</b>:
                    </td>
                    <td style="width: 80px"></td>
                    <td style="width: 43%;background-color:#111;color:#fff">
                        <b>Ship To</b>:
                    </td>                    
                </tr>
                <tr>
                <td style="width: 43%;">
                        <?= $order->b_firstname . ' ' . $order->b_lastname ?><br>
                        <?= !empty($order->b_company) ? $order->b_company . '<br>' : '' ?>
                        <?= !empty($order->b_address1) ? $order->b_address1 .'<br>' : ''?>
                        <?= !empty($order->b_address2) ? $order->b_address2 .'<br>' : '' ?>
                        <?= !empty($order->b_city) ? $order->b_city .', '. $state_b . ' ' . $order->b_zip.'<br>': '' ?>
                        <?= !empty($country) ? $country.'<br>' : '' ?>
                        <?= !empty($order->b_phone) ? $order->b_phone . '<br>' : '' ?>
                    </td>
                    <td style="width: 80px"></td>
                    <td style="width: 43%;">
                        <?= $order->s_firstname . ' ' . $order->s_lastname ?><br>
                        <?= !empty($order->b_company) ? $order->s_company . '<br>' : '' ?>
                        <?= !empty($order->s_address1) ? $order->s_address1 .'<br>' : ''?>
                        <?= !empty($order->s_address2) ? $order->s_address2 .'<br>' : '' ?>
                        <?= !empty($order->s_city) ? $order->s_city .', '. $state_s . ' ' . $order->s_zip.'<br>': '' ?>
                        <?= !empty($country) ? $country.'<br>' : '' ?>
                        <?= !empty($order->s_phone) ? $order->s_phone . '<br>' : '' ?>
                    </td>
                </tr>
            </table>

            <table cellpadding="8">
                <thead>
                    <tr style="background-color: #111;color:#fff">
                        <th style="border: 1px solid #ddd;color:#fff"><?= $order->po ? "PO" : $orderStatus ?> #</th>
                        <th style="border: 1px solid #ddd;color:#fff">Invoice Date</th>
                        <th style="border: 1px solid #ddd;color:#fff">Return #</th>
                        <th style="border: 1px solid #ddd;color:#fff">Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="border: 1px solid #ddd"><?= $order->po ? strtoupper($order->po) : $order->id ?></td>
                        <td style="border: 1px solid #ddd"><?= $order->created_at->format('m-d-Y') ?></td>
                        <td style="border: 1px solid #ddd"><?= $return->id ?></td>
                        <td style="border: 1px solid #ddd"><?= $return->pivot->created_at->format('m-d-Y') ?></td>
                    </tr>
                </tbody>
            </table>

            <?php 
                $pdf::Ln();
                $pdf::WriteHTML(ob_get_clean(), true, false, false, false, ''); 
                $amount=0;
                ob_start();
            ?>

            <table cellpadding="5" style="border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #111;color:#fff">
                        <th width="100" style="border: 1px solid #ddd;color:#fff">Image</th>
                        <th width="190" style="border: 1px solid #ddd;color:#fff">Product Name</th>
                        <th width="60" style="border: 1px solid #ddd;color:#fff">Model</th>
                        <th width="60" style="border: 1px solid #ddd;color:#fff">Serial #</th>
                        <th width="50" style="border: 1px solid #ddd;color:#fff">Qty</th>
                        <th style="border: 1px solid #ddd;color:#fff">Retail</th>
                        <th style="border: 1px solid #ddd;color:#fff">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order->products as $product) { ?>
                        <?php if ($product->id==$return->pivot->product_id) {?>
                        <?php $amount += $return->pivot->amount ?>
                    <tr>
                        <td width="100" style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0;color:#fff">
                            <img style="width: 80px" src="<?= '/public/images/'.$product->images->first()->location ?>" />
                        </td>
                        <td style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0;" width="190"><?= $product->p_size . ' ' . $product->p_title . ' ' . $product->p_reference  ?> </td>
                        <td style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0" width="60"><?= $product->p_model ?></td>
                        <td style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0" width="60"><?= $product->pivot->serial ? $product->pivot->serial . ' / ' . $product->heighest_serial : '' ?></td>
                        <td style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0" width="50">-<?= $product->pivot->qty ?></td>
                        <td style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0; text-align: right">-<?= number_format($product->p_retail,2)?></td>
                        <td style="border-right: 1px solid #d0d0d0;border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0; text-align: right;background-color:#eee">-<?= number_format($product->pivot->price*$product->pivot->qty,2)?></td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: right" colspan="6"><b>Sub Total</b></td>
                        <td style="text-align: right">-<?= number_format($amount,2)?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right" colspan="6"><b>Total Return</b></td>
                        <td style="text-align: right">-$<?= number_format($amount,2)?></td>
                    </tr>           
                </tfoot>
            </table>
                        
        <?php
        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');
        $pdf::Ln();
        //$pdf::Write(0, "Thank you for your purchase.", '', 0, 'L', true, 0, false, false, 0);
        $pdf::Write(0, "If you have any questions regarding this return, please contact us.", '', 0, 'C', true, 0, false, false, 0);
        
        //Close and output PDF document
        PDF::Output(str_replace(' ','-',$order->b_company).'-'.$orderStatus.'-'.$order->id.'.pdf', 'I');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
