@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 

<link href="/js/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/js/fancybox/jquery.fancybox.min.css">
@endsection

@section ('content')
@inject('countries','App\Libs\Countries')
<p><b>Purchase Date: {{ $order->created_at->format('m/d/Y') }}</b></p>
<div class="alert-info clearfix" style="padding: 3px">
    <div class="dropdown float-right">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Actions
        </button>
        <div class="dropdown-menu"  style="left: -72px"aria-labelledby="dropdownMenu2">
            <a href="{{ URL::to('admin/orders/'.$order->id.'/print') }}" class="dropdown-item print">Print PDF</a>
            <a href="{{ URL::to('admin/orders/'.$order->id.'/print') }}/Invoice_{{$order->id}}" class="dropdown-item print">Print JPG</a>
            @role('SuperAdmins')
            <a href="{{ URL::to('admin/orders/'.$order->id.'/edit') }}" class="dropdown-item">Edit</a>
            
            <hr>
            <a href="{{ route('payments.create', array('id' => $order->customers->first()->id)) }}" class="dropdown-item">Payments</a>
            <a href="/admin/orders/{{$order->id}}/returns/create" class="dropdown-item">Go to Returns</a>
            <hr>
            @if ($order->method == 'On Memo' || $order->method == 'On Hold')
            <a href="{{ URL::to('admin/orders/'.$order->id.'/memotransfer') }}" class="dropdown-item">Transfer to Invoice</a>
            @endif
            <a href="{{ URL::to('admin/orders/'.$order->id.'/copyinvoice') }}" class="dropdown-item">Copy Invoice</a>
            @endrole
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-6">
        <label for="po-input" class="col-form-label">PO Number</label>
        <span name="po-input">{!! $order->po ? $order->po : "&nbsp;" !!}</span>
    </div>    
    <div class="col-6">
    <label for="payment-input" class="col-form-label">Payment Method</label>
    <span style="display: block">{{ $order->method }}</span>
    
    <label for="payment-input" class="col-form-label">Payment Option</label>
    <span style="display: block">{{ PaymentsOptions()->get($order->payment_options) }}</span>
    </div>    
</div>

<div class="order-group billing" style="margin-right: 8px;margin-bottom: 8px;">
    <div class="group-title">Billing Address</div>
    <div class="p-1">
        <div class="form-group row">
            <label for="b-firstname-input" class="col-3 col-form-label">First Name</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_firstname }}</span>
                
            </div>
        </div>
        <div class="form-group row">
            <label for="b-lastname-input" class="col-3 col-form-label">Last Name</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_lastname }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="b-company-input" class="col-3 col-form-label">Company</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_company }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="b-address1-input" class="col-3 col-form-label">Address 1</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_address1 }}</span>
            </div>
        </div>
        @if ($order->b_address2)
        <div class="form-group row">
            <label for="b-address2-input" class="col-3 col-form-label">Address 2</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_address2 }}</span>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="b-phone-input" class="col-3 col-form-label">Phone</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_phone }}</span>
            </div>
        </div>        
        <div class="form-group row">
            <label for="b-city-input" class="col-3 col-form-label">City</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_city }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="b-state-input" class="col-3 col-form-label">State</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $countries->getStateFromCountry($order->b_state) }}</span>
            </div>
        </div>        
        <div class="form-group row">
            <label for="b-zip-input" class="col-3 col-form-label">Zip Code</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->b_zip }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="b-country-input" class="col-3 col-form-label">Country</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $countries->getCountry($order->b_country) }}</span>
            </div>
        </div>        
        <div class="form-group row">
            <label for="b-email-input" class="col-3 col-form-label">Email</label>
            <div class="col-9 input-group">
                <span class="form-control">{{ $order->email }}</span>
            </div>
        </div>        
    </div>
</div>

<div class="order-group shipping">
    <div class="group-title">Shipping Address</div>
    <div class="p-1">
        <div class="form-group row">
            <label for="s-firstname-input" class="col-3 col-form-label">First Name</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-firstname-input" >{{ $order->s_firstname }}</span>
                
            </div>
        </div>
        <div class="form-group row">
            <label for="s-lastname-input" class="col-3 col-form-label">Last Name</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-lastname-input" >{{ $order->s_lastname }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="s-company-input" class="col-3 col-form-label">Company</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-company-input" >{{ $order->s_company }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="s-address1-input" class="col-3 col-form-label">Address 1</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-address1-input" >{{ $order->s_address1 }}</span>
            </div>
        </div>
        @if ($order->s_address2)
        <div class="form-group row">
            <label for="s-address2-input" class="col-3 col-form-label">Address 2</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-address2-input" >{{ $order->s_address2 }}</span>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <label for="s-phone-input" class="col-3 col-form-label">Phone</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-phone-input" >{{ $order->s_phone }}</span>
            </div>
        </div>            
        <div class="form-group row">
            <label for="s-city-input" class="col-3 col-form-label">City</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-city-input" >{{ $order->s_city }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="s-state-input" class="col-3 col-form-label">State</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-state-input" >{{ $countries->getStateFromCountry($order->s_state) }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="s-zip-input" class="col-3 col-form-label">Zip Code</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-zip-input" >{{ $order->s_zip }}</span>
            </div>
        </div>
        <div class="form-group row">
            <label for="s-country-input" class="col-3 col-form-label">Country</label>
            <div class="col-9 input-group">
                <span class="form-control" id="s-country-input" >{{ $countries->getCountry($order->s_country) }}</span>
            </div>
        </div>        
    </div>
</div>

@php 
    $totalPaid=0; $creditPaid=0; $totalReturnedAmount=0;
    $grandTotal = $order->total
@endphp

@foreach($order->payments as $payment)
    @php 
        if ($payment->paid_method=="P" || !$payment->paid_method)
            $totalPaid += $payment->amount; 
        else
            $creditPaid += $payment->amount;

        $grandTotal -= $payment->amount;
    @endphp
@endforeach

@foreach ($order->orderReturns as $returns) 
<?php $returnAmount = $returns->pivot->amount*$returns->pivot->qty; ?>
<?php $grandTotal -= $returnAmount;?>
<?php $totalReturnedAmount += $returnAmount?>
@endforeach

<div class='table-responsive'>
<table class="table order-products">
    <thead>
        <tr>
        <th>Image</th>
        <th>Product Name</th>
        <th>Qty</th>
        <th>Serial #</th>
        <th>Retail</th>
        <th>Price</th>
        <th>Shipped Date</th>
        <th>Repair</th>
        </tr>
    </thead>
    <tbody>
        @inject('backorder','App\Libs\Backorders')
        <?php $i=0; ?>
        @foreach ($order->products as $product)
        
        <?php
        
            if ($product->pivot->img_name)
                $img = '/images/gallery/thumbnail/'.$product->pivot->img_name;
            else {
                if ($product->image())
                    $img =  '/images/thumbs/'.$product->image();
                else $img = '/images/no-image.jpg';
            }
        ?>
        <tr>
            <td><img style="width: 80px" src="{{$img}}" />
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <input type="hidden" name="id" class="id" value="{{$product->pivot->id}}">
            </td>
            <td>
                <a href="/admin/products/{{ $product->id }}/edit">
                    @if ($product->pivot->product_name)
                        {{ $product->pivot->product_name }}
                    @else {{ $product->p_reference . ' (' . $product->p_model . ')' }}
                    @endif
                </a>

                <?php $foundProduct = 0; $returnQty=$product->pivot->qty;
                    if ($product->returns) {
                        foreach ($product->returns as $return) {
                            if ($order->id == $return->order_id) {
                                $foundProduct= $return->returns_id ;
                                $returnQty = $return->qty;
                            }
                        }
                    }
                    ?>

                @if ($product->p_model != "MISC")
                <p class="diamensions">
                    @if ($product->p_model=='SPL')
                        <span>{{ "16X16X16" }}</span>
                        <span>{{ "32.5LBS" }}</span>
                    @elseif ($product->p_model=='FRL')
                        <span>{{ "36X31X3" }}</span>
                        <span>{{ "13LBS" }}</span>
                    @elseif ($product->p_model=='TDS')
                        <span>{{ "14X14X14" }}</span>
                        <span>{{ "6LBS" }}</span>
                    @elseif ($product->p_model=='CBS')
                        <span>{{ "11X11X9" }}</span>
                        <span>{{ "14LBS" }}</span>
                    @elseif ($product->p_model=='SKS')
                        <span>{{ "14X14X14" }}</span>
                        <span>{{ "15LBS" }}</span>
                    @elseif ($product->p_model=='SPS')
                        <span>{{ "14X14X14" }}</span>
                        <span>{{ "20LBS" }}</span>
                    @elseif ($product->p_model=='KGL' || $product->p_model=='QNL')
                        <span>{{ "24X18X12" }}</span>
                        <span>{{ "32LBS" }}</span>
                    @elseif ($product->p_model=='CBL' || $product->p_model=='CBL-HA' || $product->p_model=='CBL-GR' || $product->p_model=='CBL-GA')
                        <span>{{ "16X16X16" }}</span>
                        <span>{{ "45LBS" }}</span>
                    @elseif ($product->p_model=='SKL')
                        <span>{{ "16X16X16" }}</span>
                        <span>{{ "33LBS" }}</span>
                    @elseif ($product->p_model=='HGL')
                        <span>{{ "20X14X12" }}</span>
                        <span>{{ "35LBS" }}</span>
                    @elseif ($product->p_model=='HGS')
                        <span>{{ "16X10X10" }}</span>
                        <span>{{ "13LBS" }}</span>
                    @endif
                </p>
                @endif
                @if ($product->pivot->memo_id)
                    <span style="color:red; ">(Transferred from Memo # <a href="/admin/orders/{{$product->pivot->memo_id}}">{{$product->pivot->memo_id}}</a>)</span>
                @endif
                @if ($foundProduct) 
                    <span style="color:red; ">(Returned)</span>
                @endif
            </td>
            
            <td>@if ($foundProduct)-@endif{{ $returnQty }} </td>
            <td style="text-align: right">{{ $product->pivot->serial }} </td>
            <td style="text-align: right">{{ $product->pivot->retail ? number_format($product->pivot->retail,2) : number_format($product->retailvalue(),2) }} </td>
            <td style="text-align: right">@if ($foundProduct)-@endif{{ number_format($product->pivot->price,2) }} </td>
            <td style="text-align: center">
                @if ($product->pivot->shipped) 
                    {{ date('m/d/Y',strtotime($product->pivot->shipped)) }}
                    @if ($product->pivot->tracking)
                    <br><a target="_blank" class="btn btn-success btn-sm" href="https://www.fedex.com/fedextrack/?trknbr={{$product->pivot->tracking}}">{{$product->pivot->tracking}}</a>
                    @endif
                @else 
                    @role('superadmin') 
                        <input type="checkbox" class="btn-check is_shipped" name="is_shipped" id="is_shipped{{$i}}" autocomplete="off">
                        <label class="btn btn-outline-success btn-sm" for="is_shipped{{$i}}">Ship</label>
                    @endrole 
                @endif
            </td>
            <td style="text-align: center">
                @if ($product->pivot->repair_date && $product->pivot->returned_date == null) 
                    {{ date('m/d/Y',strtotime($product->pivot->repair_date)) }}
                    @role('superadmin') 
                    <br><button class="btn btn-success btn-sm returned">Mark as returned</button>
                    @endrole
                @elseif ($product->pivot->returned_date)
                    {{ date('m/d/Y',strtotime($product->pivot->returned_date)) }}
                    <br>Returned
                @else
                    @role('superadmin') 
                        <input type="checkbox" class="btn-check send_to_repair" name="repair" id="is_repair{{$i}}" autocomplete="off">
                        <label class="btn btn-primary btn-sm" for="is_repair{{$i}}">Send for Repair</label>
                    @endrole
                @endif
            </td>
        </tr>
        <?php $i+=1; ?>
        @endforeach
    </tbody>


    <tfoot>
        <tr>
            <td style="text-align: right;border-top: 1px solid #b3afaf" colspan="7"><b>Sub Total</b></td>
            <td style="text-align: right;border-top: 1px solid #b3afaf">{{ number_format($order->subtotal,2) }}</td>
        </tr>
        @if ($order->customers->first()->cgroup==0 && $order->method!='On Memo')
        <tr>
            <td style="text-align: right" colspan="7"><b>Tax</b></td>
            <td style="text-align: right">{{ number_format($order->taxable,3) }}</td>
        </tr>
        @endif
        @if ($order->returns->count()>0)
        <tr>
            <td style="text-align: right" colspan="7"><b>Total Retuned</b></td>
            <td style="text-align: right;color:red">({{ number_format($totalReturnedAmount,2) }})</td>
        </tr> 
        @endif
        @if ($order->discount>0)
        <tr>
            <td style="text-align: right" colspan="7"><b>Discount</b></td>
            <td style="text-align: right;color:red">({{ number_format($order->discount,2) }})</td>
        </tr> 
        @endif       
        <tr>
            <td style="text-align: right" colspan="7"><b>Freight</b>
            @if ($order->ship_method)
            ({{$order->ship_method}})
            @endif
            </td>
            <td style="text-align: right">{{ number_format($order->freight,2) }}</td>
        </tr>
        @if ($creditPaid)
        <tr>
            <td style="text-align: right" colspan="7"><b>Credit Applied</b></td>
            <td style="text-align: right;color:green">$({{ number_format($creditPaid,2) }})</td>
        </tr>
        @endif
        @if ($totalPaid != 0 && $totalPaid != $order->total) 
        <tr>
            <td style="text-align: right" colspan="7"><b>Partial Payment</b></td>
            <td style="text-align: right;color:green">$({{ number_format($totalPaid,2) }})</td>
        </tr>
        @endif
        <tr>
            <td style="text-align: right" colspan="7"><b>Total</b></td>
            @if ($grandTotal<0)
                <td style="text-align: right">$({{number_format(abs($grandTotal),2)}})</td>
            @else
                <td style="text-align: right">${{number_format($grandTotal,2)}}</td>
            @endif
        </tr>
        @if ($totalPaid != 0 && $totalPaid != $order->total) 
        <tr>
            <td style="text-align: right" colspan="7"><b>Grand Total</b></td>
            @if ($grandTotal-$order->discount<0)
                <td style="text-align: right;color:red">$({{ number_format(abs($grandTotal-$order->discount),2) }})</td>
            @else
                <td style="text-align: right;color:red">${{ number_format($grandTotal-$order->discount,2) }}</td>
            @endif
        </tr>
        @endif    
    </tfoot>
</table>
</div>

@if ($order->previous_id)
    <div class="form-group row">
        <div class="col-12">
            <label for="previous_id-input" class="col-form-label">Order No.</label>
            {{$order->previous_id}}
        </div>    
    </div>
@endif

@if ($order->tracking)
<div class="form-group row">
    <div class="col-12">
        <label for="comments-input" class="col-form-label">Tracking No.</label>
        <div id="tracking-input" >
            @if (strpos($order->tracking,',') > 0)
                @php
                    $trackings = explode(",",$order->tracking);
                @endphp

                @foreach ($trackings as $tracking)
                <a target="_blank" href="https://www.fedex.com/fedextrack/?trknbr={{ $tracking }}" class="btn btn-primary btn-sm">{{ $tracking }}</a></button>
                @endforeach
            @else
            <a target="_blank" href="https://www.fedex.com/fedextrack/?trknbr={{ $order->tracking }}" class="btn btn-primary btn-sm">{{ $order->tracking }}</a></button>
            @endif
        </div>
    </div>    
</div>
@endif

<?php if ($order->payments->count()) { ?>
    <hr>
    <div style="background: #f7f7f7; box-shadow: 0 0 7px 1px #ccc;border-radius: 7px;padding: 5px;margin-bottom: 15px;">
    <b>Payments / Credits</b><br><hr>   
    <table cellpadding="4" class="table table-light orders_payments">
        <tr><th style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0;">Reference</th>
        <th style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0;">Amount</th>
        <th style="border-left: 1px solid #d0d0d0;border-bottom: 1px solid #d0d0d0;">Date</th></tr>
        <?php foreach ($order->payments as $payment)  { 
            $literalMethod = '';
            if ($payment->paid_method) 
                $literalMethod = " (" . $payment->paid_method . ')';
            
            echo '<tr><td>'.$payment->ref.'</td>'.
                '<td style="text-align: right">$'.number_format($payment->amount,2). $literalMethod.  '</td>'.
                '<td style="text-align: right">'.$payment->created_at->format('m-d-Y').'</td></tr>';
            }
        ?>
        
        </table>

        <div style="border-top: 1px solid #ccc;font-size: 11px;">(C) - Credit, (P) - Payment</div>
    </div>

<?php } ?>

<div class="form-group row">
    <div class="col-12">
        <label for="add-info-input" class="col-form-label">Additional Information (seen by customers)</label>
        <span class="form-control" id="add-info-input" style="height: 100px; overflow-y:auto;overflow-x:hidden" >{!! str_replace("\n","<br>",$order->add_info) !!}</span>
    </div>    
</div>

<div class="form-group row">
    <div class="col-12">
        <label for="comments-input" class="col-form-label">Comments</label>
        <span class="form-control" id="comments-input" style="height: 150px; overflow-y:auto;overflow-x:hidden" >{!! str_replace("\n","<br>",$order->comments) !!}</span>
    </div>    
</div>

<div class="alert-info clearfix" style="padding: 3px">
    <div class="dropdown float-right">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Actions
        </button>
        <div class="dropdown-menu"  style="left: -72px;top:-263px"aria-labelledby="dropdownMenu2">
            <a href="{{ URL::to('admin/orders/'.$order->id.'/print') }}" class="dropdown-item">Print Invoice</a>
            <a href="{{ URL::to('admin/orders/'.$order->id.'/print/commercial') }}" class="dropdown-item">Print Commercial</a>
            <a href="{{ route('payments.create', array('id' => $order->customers->first()->id)) }}" class="dropdown-item">Payments</a>
            @role('superadmin')
            <a href="{{ URL::to('admin/orders/'.$order->id.'/edit') }}" class="dropdown-item">Edit</a>
            <hr>
            <a href="/admin/orders/{{$order->id}}/returns/create" class="dropdown-item">Go to Returns</a>
            <hr>
            @if ($order->method == 'On Memo' || $order->method == 'On Hold')
            <a href="{{ URL::to('admin/orders/'.$order->id.'/memotransfer') }}" class="dropdown-item">Transfer to Invoice</a>
            @endif
            <a href="{{ URL::to('admin/orders/'.$order->id.'/copyinvoice') }}" class="dropdown-item">Copy Invoice</a>
            @endrole
        </div>
    </div>
</div>

@endsection

@section ('footer')
<script src="/js/fancybox/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection      

@section ('jquery')
<script>
    $(document).ready( function() {
        $('.print').click( function(e) {
            e.preventDefault();
            var printWindow = window.open($(this).attr('href'), "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=200,left=500,width=500,height=600");
            if ($(this).attr('href')=='Invoice_'+{{$order->id}}) {
                var printAndClose = function() {
                    if (printWindow.document.readyState == 'complete') {
                        printWindow.print();
                        clearInterval(sched);
                    }
                }
                var sched = setInterval(printAndClose, 1000);
            }
        })

        $('.send_to_repair').click( function (e) {
            sendForRepair(this);
        })

        $('.returned').click( function() {
            receiveFromRepair(this);
        })

        function receiveFromRepair(el) {
            $.confirm({
                title: 'Send For Repair',
                content: 'Are you sure you want to mark this item as return from repair?',
                buttons: {
                    formSubmit: {
                        text: 'Yes',
                        btnClass: 'btn-blue',
                        action: function () {
                            var request =  $.ajax({
                                type: "post",
                                url: '{{route("receive.item.from.repair")}}',
                                data: { 
                                    id: $(el).parents('tr').children(0).find('input.id').val()
                                },
                                success: function (result) {
                                    $(el).parent().html(result)
                                }
                            })
                            request.fail( function (jqXHR, textStatus) {
                                $.alert (textStatus)
                            })
                        }
                    },
                    cancel: function () {
                        $(el).prop('checked',false);
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });

        }

        function sendForRepair(el) {
            $.confirm({
                title: 'Send For Repair',
                content: 'Are you sure you want to mark this item as being shipped for repair?',
                buttons: {
                    formSubmit: {
                        text: 'Yes',
                        btnClass: 'btn-blue',
                        action: function () {
                            var request =  $.ajax({
                                type: "post",
                                url: '{{route("set.item.repair")}}',
                                data: { 
                                    id: $(el).parents('tr').children(0).find('input.id').val()
                                },
                                success: function (result) {
                                    $(el).parent().html(result)
                                }
                            })
                            request.fail( function (jqXHR, textStatus) {
                                $.alert (textStatus)
                            })
                        }
                    },
                    cancel: function () {
                        $(el).prop('checked',false);
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });

        }

        function AddTracking(el) {
            $.confirm({
                title: 'tracking #',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Enter tracking number</label>' +
                '<input type="text" autofocus class="tracking form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Submit',
                        btnClass: 'btn-blue',
                        action: function () {
                            var request =  $.ajax({
                                type: "post",
                                url: '{{route("set.item.shipped")}}',
                                data: { 
                                    tracking: this.$content.find('.tracking').val(),
                                    id: $(el).parents('tr').children(0).find('input.id').val()
                                },
                                success: function (result) {
                                    $(el).parent().html(result)
                                }
                            })
                            request.fail( function (jqXHR, textStatus) {
                                $.alert (textStatus)
                            })
                        }
                    },
                    cancel: function () {
                        $(el).prop('checked',false);
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });

        }

        $('.is_shipped').click( function() {
            AddTracking(this)
        })
    })    
</script>
@endsection