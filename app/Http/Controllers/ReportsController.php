<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Models\Returns;
use App\Models\OrderReturns;
use App\Models\Estimate;
use Elibyy\TCPDF\Facades\TCPDF;
use PDF;

class ReportsController extends Controller
{
    public function PrintOwed() {
        $printOrder = new \App\Libs\PrintOrder(); // Create Print Object
        $ret = $printOrder->printOwed(); // Print newly create proforma.

    }

    public function index() {
            $products = DB::table('products')->selectRaw('if(b_company="",concat(b_firstname, " " ,b_lastname),b_company) as company,order_id,model_name,p_serial,COALESCE(product_id,0) product_id,method,orders.created_at created_at')
            ->join('order_product','product_id','=','products.id')
            ->join('orders','orders.id','=','order_id')
            ->join('product_retails','product_retails.p_model','=','products.p_model')
            ->groupBy('model_name','product_id')
            ->orderBy('product_id','asc')
            ->get();

        $orders = Order::where('status','0')
            ->where('method','<>','On Memo')
            ->where('method','<>','On Hold')
            ->where('method','<>','Repair')
            ->get();

        $paidOrders = Order::where('status','1')
            ->where('method','<>','On Memo')
            ->where('method','<>','On Hold')
            ->where('method','<>','Repair')
            ->get();

        $memos = Order::where('status','0')
            ->where('method','=','On Memo')
            ->get();

        return view('admin.reports',['pagename'=>'Reports','products'=>$products,'orders'=>$orders,'memos'=>$memos,'paidOrders'=>$paidOrders]);
    }
    
    private function initializePDF($pdf,$title,$orienation='P') {
        PDF::setHeaderCallback(function($pdf) use ($title) {
            // Logo
            $pdf->SetFont('helvetica', 'I', 8);
            // Page number
            $pdf->Cell(0, 10, $title. " - ".date('F d, Y',time()), 0, false, 'C', 0, '', 0, false, 'T', 'M');
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
        $pdf::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-10);

        // set image scale factor
        $pdf::setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf::setLanguageArray($l);
        }

        // ---------------------------------------------------------
        // add a page
        $pdf::AddPage($orienation);

        $pdf::SetFont('helvetica', '', 10);
        $count = 0;$sub_count=0;$oldModel='';
    }

    public function printMemos() {
        $orders = Order::where('status','0')
            ->where('method','=','On Memo')
            ->get();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->initializePDF($pdf,"Berd Vaye Inc. - Memos",'L');

        ob_start();
        ?>
        <table id="orders" cellpadding="3" >
        <thead>
            <tr>
            <th style="width: 80px;background-color: #b9b9b9"><b>Memo Id</b></th>
            <th style="width: 80px;background-color: #b9b9b9"><b>Date</b></th>
            <th style="width: 210px;background-color: #b9b9b9"><b>Customer</b></th>
            <th style="width: 200px;background-color: #b9b9b9"><b>Product</b></th>
            <th style="width: 60px;background-color: #b9b9b9"><b>Size</b></th>
            <th style="width: 60px;background-color: #b9b9b9"><b>SN</b></th>
            <th style="width: 100px;background-color: #b9b9b9"><b>Cost</b></th>
            <th style="width: 100px;background-color: #b9b9b9"><b>Total Amount</b></th>
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0;$subtotal=0;$cost=0;
                foreach ($orders as $order) {
                    $arr=array();$returned = array();
                    foreach ($order->products as $product) {
                        foreach ($order->orderReturns as $returnItem){
                            $returned[] = $returnItem->pivot->product_id;
                        }

                        if (!in_array($product->id,$returned)) {
                            $cost+=$product->p_price;
                            $product_name= $product->p_title . ' ' . $product->p_reference;
                            $arr[]=array('product_name'=>$product_name,'size'=>$product->p_model,'serial'=>$product->p_serial,'price'=>$product->p_price,'cost'=>$product->pivot->price);
                        }
                    }
                    ?>
                        <?php $grandTotal += $order->total ?>
                        <?php $subtotal = $order->total ?>
                        
                        <?php foreach($order->payments as $payment) { ?>
                            <?php $subtotal -= $payment->amount ?>
                            <?php $grandTotal -= $subtotal ?>
                        <?php } ?>
             

                    <?php foreach ($arr as $pr) {?>
                    <tr>
                        <td style="width: 80px;"><?= $order->id ?></td>
                        <td style="width: 80px;"><?= $order->created_at->format('m-d-Y') ?></td>
                        <td style="width: 210px;"><?=$order->s_company != '' ? $order->s_company : $order->s_firstname . ' '.$order->s_lastname ?></td>
                        <td style="width: 200px;"><?= $pr['product_name'] ?></td>
                        <td style="width: 60px;"><?= $pr['size'] ?></td>
                        <td style="width: 60px;"><?= $pr['serial'] ?></td>
                        <td style="width: 100px;text-align: right">$<?= number_format($pr['price'],2) ?></td>
                        <td style="width: 100px;text-align: right">$<?= number_format($pr['cost'],2) ?></td>
                        
                    </tr>
                    <?php } ?>
                    <?php $cost=0;?>
                    <?php } ?>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
                <tfoot >
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;font-weight:bold;">Total:</td>
                        <td style="text-align: right;font-weight:bold;">$<?= number_format($grandTotal,2) ?></td>
                    </tr>
                </tfoot>
            </table>

        <?php 
            
        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');

        //Close and output PDF document
        PDF::Output('memos.pdf', 'I');
    }

    protected function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
    
        array_multisort($sort_col, $dir, $arr);
        return $arr;
    }
    
    protected function columnSort($unsorted, $column) { 
        $sorted = $unsorted; 
        
        for ($i=0; $i < sizeof($sorted)-1; $i++) { 
          for ($j=0; $j<sizeof($sorted)-1-$i; $j++) 
          //dd($sorted);
           //echo(is_array($sorted[$j+1]['product']). '-'.$sorted[$j+1]['subtotal'].'<br>');
            if (!isset($sorted['totals'])){
                if ($sorted[$j]['product'][0][$column] > $sorted[$j+1]['product'][0][$column]) { 
                    $tmp = $sorted[$j]; 
                    $sorted[$j] = $sorted[$j+1]; 
                    $sorted[$j+1] = $tmp; 
                }
            } 
        } 

        return $sorted; 
    } 

    public function printSales1($param='',$method='',$date='',$company='') {
        if ($method == 'memo') {
            $method = 'On Memo';
            $memoText = 'Memo';
        }
        else {
            $method = 'Invoice';
            $memoText = 'Invoice';
        }

        
        $orders = Order::with('orderReturns')->where('method','=',$method);

        ;//where('method','=',$method);
        
        if (strpos($date,'orderid')!==false) {
            $orders=$orders->whereIn('id',explode(',',substr($date,8)));
            $title = "Paid & Returned $method(s)";
        } elseif (is_numeric($date) && !$company) {
            $orders=$orders->whereYear('created_at',$date);
            $title = "Paid & Returned $method(s) - ($date)";
        } elseif ($date && $company) {
            $orders=$orders
                ->whereRaw("(b_company LIKE '%$company%' OR b_firstname LIKE '%$company%' OR b_lastname LIKE '%$company%') AND year(`created_at`) = '$date'");
            $title = "Paid & Returned $method - ($date, $company)";
        } elseif (!is_numeric($date)) {
                $orders=$orders->whereRaw("(b_company LIKE '%$date%' OR b_firstname LIKE '%$date%' OR b_lastname LIKE '%$date%')");
                $title = "Paid & Returned $method(s) - ($date)";
        } else {
            $title = "Paid & Returned $method(s)";
        }
        
        $orders=$orders->orderBy('b_company','asc')
            ->get();

        $total = 0;
        if ($param == 'items')
            $orienation = 'L';
        else $orienation = 'P';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->initializePDF($pdf,"Berd Vaye - $title",$orienation);
        ob_start();
        ?>
        
        <table cellpadding="3" >
        <thead>
            <tr>
            <?php if ($param == 'items') {?>
            <th style="width: 80px;background-color: #b9b9b9"><b><?= $memoText ?> Id</b></th>
            <th style="width: 80px;background-color: #b9b9b9"><b>Date</b></th>
            <th style="width: 210px;background-color: #b9b9b9"><b>Customer</b></th>
            <th style="width: 250px;background-color: #b9b9b9"><b>Product</b></th>
            <th style="width: 60px;background-color: #b9b9b9"><b>Size</b></th>
            <th style="width: 30px;background-color: #b9b9b9"><b>SN</b></th>
            <th style="width: 30px;background-color: #b9b9b9"><b>Qty</b></th>
            <th style="width: 100px;background-color: #b9b9b9"><b>Cost</b></th>
            <th style="width: 100px;background-color: #b9b9b9"><b>Status</b></th>
            <?php } else {?>
                <th style="background-color: #b9b9b9"><b>Memo Id</b></th>
                <th style="background-color: #b9b9b9"><b>Date</b></th>
                <th style="background-color: #b9b9b9"><b>Customer</b></th>
                <th style="background-color: #b9b9b9"><b>Total Amount</b></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>

        <?php 
            $temp = 0;$i=0;$total=0;$stuff='';
            foreach ($orders as $order) {
            $company = $order->s_company != '' ? $order->s_company : $order->s_firstname . ' '.$order->s_lastname;

            $returnItemId = array();
            foreach($order->orderReturns as $returns) {
                $returnItemId[] = $returns->pivot->product_id;
            }
           
            $grand_total=0;
            if ($temp == 0) $temp = $order->customers->first()->id;
            foreach ($order->products as $product) {
                if ($order->customers->first()->id == $temp) {
                    backtoloop:
                 
                    if ($product->pivot->qty>0 && $product->pivot->price>0) {
                        $product_name= $product->p_size . ' ' .$product->p_title . ' ' . $product->p_reference;
                        if (in_array($product->id,$returnItemId)) {
                            $sign = '-';
                            $grand_total -= $product->pivot->price;
                            $total -=$product->pivot->price;
                        } else {
                            $sign = '';
                            $grand_total += $product->pivot->price;
                            $total +=$product->pivot->price;
                        }
                        //$total += $grand_total; 
                        ?>
                        <tr>
                        <td style="width: 80px;"><?= $order->id ?></td>
                        <td style="width: 80px;"><?= $order->created_at->format('m-d-Y') ?></td>
                        <td style="width: 210px;"><?= $company ?></td>
                        <td style="width: 250px;"><?= $product_name ?></td>
                        <td style="width: 60px;"><?= $product->p_model ?></td>
                        <td style="width: 30px;text-align: right;"><?= $product->p_serial ?></td>
                        <td style="width: 30px;text-align: right;"><?= $product->pivot->qty ?></td>
                        <td style="width: 100px;text-align: right;<?php echo $sign=='-' ? 'color:red' : '' ?>"><?= '$' . $sign . number_format($product->pivot->price,2) ?></td>
                        <td style="width: 100px;<?php echo $sign=='-' ? 'color:red' : '' ?>"><?= $sign=='-' ? 'Return' : '' ?></td>
                        </tr>
                        <?php
                    }
                } else { 
                    if ($total != 0) {
                        
                    ?>
                    <tr style="background-color:#eee">
                        <td style="background-color:#eee;font-weight:bold">Total</td>
                        <td style="background-color:#eee;text-align: right;font-weight:bold;" colspan="7">$<?= number_format($total < -1 ? 0 : $total,2) .$stuff ?></td>
                    </tr> 
                <?php 
                }
                    $temp = $order->customers->first()->id;
                    $total = 0;
                    goto backtoloop; 
                }
            }
        } 
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td style="font-weight:bold;background-color: #eee" colspan="7">Total Orders</td>
                <td style="text-align: right;font-weight:bold;background-color: #eee">$<?= number_format($grand_total,2) ?></td>
            </tr>
        </tfoot>
        </table>
        <?php

        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');

        //Close and output PDF document
        PDF::Output('sales.pdf', 'I');
    }

    public function printSales($param='',$date='') {
        $orders = Order::selectRaw('id,s_company,s_firstname,s_lastname,status,created_at,subtotal,freight')
            ->where('method','<>','On Memo')
            ->where('method','<>','Repair');

        if ($date) {
            $orders=$orders->whereYear('created_at',$date);
            $title = "Paid/Unpaid Invoices - ($date)";
        } else
            $title = 'Paid/Unpaid Invoices';

        $orders=$orders
            ->orderBy('status','asc')
            ->orderBy('id','asc')
            ->get();

        if ($param == 'items')
            $orienation = 'L';
        else $orienation = 'P';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->initializePDF($pdf,"Berd Vaye - $title",$orienation);

        $arr=array();
        $grandTotal = 0;$subtotal=0;$oldstatus=0;$subtotals=0;$cost=0;$tqty=0;$freight=0;
        foreach ($orders as $order) {
            $appendArray=array();$subtotal=0;
            $company = $order->s_company != '' ? $order->s_company : $order->s_firstname . ' '.$order->s_lastname;
            $year = $order->created_at->format('Y');
            if ($date=='') $date=$year;
            
            $pdate = $order->created_at->format('m-d-Y');

            if ($param=='items') {
                foreach ($order->products as $product) {
                    if ($product->pivot->serial>0 && $product->pivot->qty>0) {
                        if ($date==$year ){ 
                            $product_name= $product->p_size . ' ' .$product->p_title . ' ' . $product->p_reference;
                            $appendArray[]=array(
                                'status'=>$order->status,
                                'id'=>$order->id,
                                'company'=>$company,
                                'date'=>$pdate,
                                'product_id'=>$product->id,
                                'product_name'=>$product_name,
                                'size'=>$product->p_model,
                                'serial'=>$product->p_serial,
                                'price'=>$product->p_price,
                                'cost'=>$product->pivot->price,
                                'onhand'=>$product->p_qty
                            );

                            $subtotal += $product->pivot->price;
                            $tqty++;
                        } 
                    }
                }
            } else {
                $appendArray[]=array(
                    'status'=>$order->status,
                    'company'=>$company,
                    'id'=>$order->id,
                    'date'=>$pdate,
                    'totalamount'=>$order->subtotal
                );
                $subtotal += $order->subtotal;
                $tqty++;
            }

            $arr[]=$appendArray;
            if ($order->status == 0) {
                $subtotals += $order->subtotal;
                $freight+=$order->freight;
                $payments=DB::table('order_payment')->where('order_id',$order->id)->get();
                
                foreach($payments as $payment) {
                    $subtotal -= $payment->amount;
                    $grandTotal -= $subtotal;
                    $subtotals -= $subtotal;
                }
            } else {
                $subtotals += $order->subtotal;
            }
            
            $grandTotal += $subtotal;
            $torder[]=array('subtotal'=>$subtotal,'freight'=>$order->freight,'product'=>$appendArray);
        }

        
        //dd($torder);
        $torder['totals']=$subtotals;
        $torder['grandTotal']=$grandTotal;
        $torder['freight']=$freight;

        //print_r($torder);die;
        $torder=$this->columnSort($torder,'status');
        ob_start();
        ?>
        <table id="orders" cellpadding="3" >
        <thead>
            <tr>
            <?php if ($param == 'items') {?>
            <th style="width: 80px;background-color: #b9b9b9"><b>Invoice Id</b></th>
            <th style="width: 80px;background-color: #b9b9b9"><b>Date</b></th>
            <th style="width: 210px;background-color: #b9b9b9"><b>Customer</b></th>
            <th style="width: 250px;background-color: #b9b9b9"><b>Product</b></th>
            <th style="width: 60px;background-color: #b9b9b9"><b>Size</b></th>
            <th style="width: 60px;background-color: #b9b9b9"><b>SN</b></th>
            <th style="width: 100px;background-color: #b9b9b9"><b>Cost</b></th>
            <th style="width: 100px;background-color: #b9b9b9"><b>Total Amount</b></th>
            <?php } else {?>
                <th style="background-color: #b9b9b9"><b>Memo Id</b></th>
                <th style="background-color: #b9b9b9"><b>Date</b></th>
                <th style="background-color: #b9b9b9"><b>Customer</b></th>
                <th style="background-color: #b9b9b9"><b>Total Amount</b></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php 
            $subtotal=0;
            foreach ($torder as $arr) {
                if (isset($arr['product'])) {
                    foreach ($arr['product'] as $pr) { ?>

                <?php if ($pr['status']!=$oldstatus) {?>
                    <tr>
                        <td colspan="<?= $param=='items' ? 6 : 2?>"></td>
                        <td style="text-align: right;font-weight:bold;background-color: #e4e4e4">Total:</td>
                        <td style="text-align: right;font-weight:bold;background-color: #e4e4e4">$<?= number_format($subtotal,2) ?></td>
                    </tr>    
                <?php $subtotal=0 ?>
                <?php } ?>
            <?php if ($param == 'items') {?>
            <tr>
                <td style="width: 80px;"><?= $pr['id'] ?></td>
                <td style="width: 80px;"><?= $pr['date'] ?></td>
                <td style="width: 210px;"><?= $pr['company'] ?></td>
                <td style="width: 250px;"><?= $pr['product_name'] ?></td>
                <td style="width: 60px;"><?= $pr['size'] ?></td>
                <td style="width: 60px;"><?= $pr['serial'] ?></td>
                <td style="width: 100px;text-align: right">$<?= number_format($pr['price'],2) ?></td>
                <td style="width: 100px;text-align: right;<?= $pr['status'] == 0 ? 'color:red' : 'color:black'?>">$<?= number_format($pr['cost'],2) ?></td>
            </tr> 
            <?php } else {?>
                <tr>
                    <td><?= $pr['id'] ?></td>
                    <td><?= $pr['date'] ?></td>
                    <td><?= $pr['company'] ?></td>
                    <td style="text-align: right;<?= $pr['status'] == 0 && $pr['totalamount']>0 ? 'color:red' : 'color:black'?>">$<?= number_format($pr['totalamount'],2) ?></td>
                </tr> 
            <?php } ?>
            <?php $oldstatus=$pr['status'];
                    } 
                    $subtotal+=$arr['subtotal'];
                } 
            } ?>
            <tr>
                <td colspan="<?= $param=='items' ? 6 : 2?>"></td>
                <td style="text-align: right;font-weight:bold;background-color: #e4e4e4">Total:</td>
                <td style="text-align: right;font-weight:bold;background-color: #e4e4e4">$<?= number_format($subtotal,2) ?></td>
            </tr>
            <tr>
                <td colspan="<?= $param=='items' ? 8 : 4?>"></td>
            </tr>
            </tbody>
            <tfoot >
                <tr>
                    <td colspan="<?= $param=='items' ? 2 : 2?>"></td>
                    <td colspan="<?= $param=='items' ? 2 : 1?>" style="text-align: right;font-weight:bold;background-color: #e4e4e4">Grand Total:</td>
                    <?php if ($param=='items') {?>
                        <td colspan="2" style="text-align: right;font-weight:bold;background-color: #e4e4e4">F: $<?= number_format($torder['freight'],2) ?></td>
                        <td style="text-align: right;font-weight:bold;background-color: #e4e4e4"><?= $tqty?></td>
                    <?php } ?>
                    <td style="text-align: right;font-weight:bold;background-color: #e4e4e4">$<?= number_format($grandTotal,2) ?></td>
                </tr>
            </tfoot>
        </table>

        <?php

        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');

        //Close and output PDF document
        PDF::Output('sales.pdf', 'I');
        //print_r($arr);
    }

    public function printUnpaid($isMemo=0) {

        // if (\Auth::user()->name == 'Edward B') {
        //     $this->printSales1();
        //     die;
        // }

        $orders = Order::where('status','0');
        if ($isMemo == 0) {
            $title = 'Unpaid Invoices';
            $orders=$orders->where('method','<>','On Memo')
                ->where('method','<>','On Hold')
                ->where('method','<>','Repair')
                ->orderBy('id','desc')
                ->get();
        } else {
            $title = 'Memos';
            $orders=$orders->where('method','=','On Memo')
                ->orderBy('id','desc')
                ->get();
        }

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->initializePDF($pdf,"Berd Vaye - $title","P");
        ob_start();
        ?>

        <table id="orders" cellpadding="3" class="table table-striped table-borde#fb8d8d hover">
                <thead>
                    <tr>
                    <th style="width:80px;background-color: #b9b9b9">Order Id</th>
                    <th style="background-color: #b9b9b9">Invoice Date</th>
                    <th style="width:170px;background-color: #b9b9b9">Customer</th>
                    <th style="background-color: #b9b9b9">PO</th>
                    <th style="background-color: #b9b9b9">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $subtotal=0;$grandTotal=0;
                    foreach ($orders as $order) {
                        $subtotal = $order->total - $order->payments->sum('amount');
                        foreach($order->orderReturns as $returns) {
                            $subtotal -= $returns->pivot->amount*$returns->pivot->qty;
                        }

                        if ($subtotal>0) { 
                        $grandTotal += $subtotal; ?>
                        <tr>
                            <td style="width:80px"><?= $order->id ?></td>
                            <td><?= $order->created_at->format('m-d-Y') ?></td>
                            <td style="width:170px;"><?=$order->s_company != '' ? $order->b_company : $order->b_firstname . ' '.$order->s_lastname ?></td>
                            <td><?=$order->po?></td>
                            <td style="text-align: right;color:red">$<?= number_format($subtotal,2) ?></td>
                        </tr>
                        <?php }
                    } ?>
                    
                </tbody>
                <tfoot>
                    <tr style="background-color: #eee">
                        <td></td>
                        <td class="text-right" style="font-weight:bold">Row Total</td>
                        <td colspan="3" style="font-weight:bold;text-align: right">$<?= number_format($grandTotal,2) ?></td>
                    </tr>
                </tfoot>
            </table>
        
        <?php 
        $pdf::WriteHTML(ob_get_clean(), true, false, false, false, '');

        //Close and output PDF document
        PDF::Output('upaid-orders.pdf', 'I');

    }

}
