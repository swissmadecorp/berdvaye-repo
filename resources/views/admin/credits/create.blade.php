@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')
@inject('countries','App\Libs\Countries')

@if ($orders->count())
<div class="customer_address pb-2">
    <?php 
        $address2 = ''; $returnAmount = 0;
        
        $state_s = $countries->getStateCodeFromCountry($orders[0]->s_state);
        $country = $countries->getCountry($orders[0]->s_country);

        echo $orders[0]->s_company.'<br>';
        echo !empty($orders[0]->s_address1) ? $orders[0]->s_address1 .'<br>' : '';
        echo !empty($orders[0]->s_address2) ? $orders[0]->s_address2 .'<br>' : '';
        echo !empty($orders[0]->s_city) ? $orders[0]->s_city .', '. $state_s . ' ' . $orders[0]->s_zip.'<br>': '';
        
        echo !empty($orders[0]->b_phone) ? $orders[0]->b_phone . '<br>' : '';
        echo !empty($orders[0]->po) ? 'PO #: '.$orders[0]->po . '<br>' : '';
    ?>

</div>
<hr>
<?php $totalOwed = 0; ?>
@foreach($orders as $order)
    <?php $totalOwed+=$order->total-$order->payments->sum('amount')-$returnAmount; ?>
    
    <?php $credit = $order->customers->first()->credit; ?>
@endforeach

<?php if ($totalOwed==0) {  ?>
    <div class="paid-in-full">&nbsp;</div>
<?php } ?>

<form method="POST" action="{{route('credits.store')}}" id="creditForm">
@csrf
    <input type="hidden" name="creditused" id="creditused" value=0>
    <input type="hidden" name="response" id="response">
    <?php $custId = 0 ?>
    @if ($totalOwed!=0.0)
    <?php $custId = $orders[0]->customers()->first()->id ?>
    <input type="hidden" value="{{$custId}}" name="customer_id" id="customer_id">
    <div class="form-group row">
        <label for="amount-input"  class="col-3 col-form-label">Amount Received *</label>
        <div class="col-9">
        @role('superadmin')
        <input type="text" id="amount"  class='form-control' autofocus name="amount" required/>
        @else
        <span class='form-control p-3'></span>
        @endrole
        </div>    
    </div>
    <div class="form-group row">
        <label for="ref-input" class="col-3 col-form-label">Reference *</label>
        <div class="col-9">
        @role('superadmin')
        <input type="text" id="ref" class='form-control' autofocus name="ref" required/>
        @else
        <span class='form-control p-3'></span>
        @endrole
        </div>    
    </div>    

    @if ($credit) <br><b>Available Credit: <span id="credit" data-amount="{{$credit->amount}}" data-ref="{{$credit->ref}}">{{ '$'.number_format($credit->amount,2) }}</span></b>
    <button class="btn btn-primary btn-sm" id="usecredit">Use Credit</button>
    @endif
@endif
<table id="orders" class="table table-striped table-bordered hover">
    <thead>
        <tr>
        <th></th>
        <th>Order Id</th>
        <th>Invoice Date</th>
        <th>Total Owed</th>
        <th>Amount Paid</th>
        <th>Total Due</th>
        <th>Reference</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php $totalOwed = 0 ?>
    @foreach($orders as $order)     
            <?php $returnAmount = 0;  ?>
            @foreach($order->returns->all() as $return)
                @php
                    $returnAmount += $return->pivot->amount*$return->pivot->qty;
                @endphp
            @endforeach
            
            <?php $totalPaid=0;$diff= $order->total-$returnAmount; ?>
            @foreach($order->payments->all() as $payment)
                <?php $paidMethod = $payment->paid_method; ?>
                
                <?php 
                    $literalMethod = '';
                    if ($payment->paid_method) 
                        $literalMethod = " (" . $payment->paid_method . ')';
                 ?>
                <tr>
                    <td></td>
                    <td><a style="color:#6294d3" href="/admin/orders/{{ $order->id }}">{{ $order->id }}</a></td>
                    <td class="text-right">{{ $payment->created_at->format('m/d/Y') }}</td>
                    <td class="text-right">${{ number_format($diff,2) }}</td>
                
                    <td class="text-right">-${{ number_format($payment->amount,2) . $literalMethod }}</td>
                    <td class="text-right">${{ number_format($diff-$payment->amount,2) }}</td>
                    <td>{{ $payment->ref }}</td>
                    <td class="text-center">
                        @role('superadmin')
                        
                        <!-- <form method="POST" action="{{route('destroy.payment',['id' => $payment->id])}}" >
                            @csrf
                            @method('DELETE') -->

                            <button type="submit" style="padding: 3px 5px" data-paymentId ='{{$payment->id}}' data-paymethod='{{$payment->paid_method}}' class="btn btn-danger deleteitem" aria-label="Left Align">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>
                        <!-- </form> -->
                        @endrole
                    </td>  
                </tr>
                <?php 
                    if ($payment->amount > $order->total-$returnAmount) {
                        $diff = $payment->amount-$diff;
                    } else $diff -= $payment->amount;
                ?>
            @endforeach

            <?php $diff-=$order->discount ?>
            @if ($order->status==0)
                @if ($diff != 0)
                <tr>
                    <td></td>
                    <td><a style="color:#6294d3" href="/admin/orders/{{ $order->id }}">{{ $order->id }}</a></td>
                    <td class="text-right">{{ $order->created_at->format('m/d/Y') }}</td>
                    <td class="text-right">${{ number_format($diff,2) }}</td>
                    <td class="text-right"></td>
                    <td class="text-right">${{ number_format($diff,2) }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            @endif
            <?php $totalPaid = 0; $totalOwed+=$order->total-$order->payments->sum('amount')-$returnAmount; ?>
        @endforeach
        
    </tbody>
    <tfoot>

        @if ($totalOwed!=0.0)
        <tr>
            <td colspan="5"></td>
            <td class="text-right" style="font-weight:bold">Total Due</td>
            <td colspan="2" class="text-right" style="font-weight:bold">$ {{number_format(abs($totalOwed),2) }}</td>
            
        </tr>
        @else
        <tr>
        <td colspan="8" style="text-align: center; color: green">Paid in full</td>
        </tr>
        @endif
    </tfoot>
</table>

<span id="amountdue" data-amount="{{$totalOwed}}"></span>
@role('superadmin')
<input class="btn btn-primary save" type="submit" value="Save">
@endrole

@include('admin.errors')
</form>

@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {

        var table = $('#orders').dataTable({
            "deferRender": true,
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            }],
            select: {
                style:    'os', // or multi
                selector: 'td:first-child'
            },
            'rowCallback': function( row, data, index ){
                if( data[4].indexOf('-',0)==0) {
                     $(row).find('td:first-child').removeClass('select-checkbox')
                }
            },
            "aaSorting": [[1, 'desc'], [ 6, 'asc']],
            "createdRow": function( row, data, dataIndex){
                if( data[4].indexOf('-',0))
                    $(row).addClass('unpaid');
                else $(row).addClass('paid');
            },
        });

        $('.deleteitem').click( function(e) {
            e.preventDefault();
                // var pmtMethod = '';
                // var amtPaid = $(this).parents("tr").children('td').eq(4).text()
                // if (amtPaid.indexOf("(") == -1 || amtPaid.indexOf("(P)") != -1) {
                //     pmtMethod = "P"
                // } 
                var _this = this;
                $.confirm({
                    title: 'Payment',
                    content: "You have already applied payment to this order. Do you want to apply a credit or delete this payment?",
                    boxWidth: '30%',
                    useBootstrap: false,
                    onOpenBefore: function(){
                        if (_this.dataset.paymethod != "C") {
                            this.buttons.applyCredit.hide()
                        }
                    },
                    buttons: {
                        applyCredit: {
                            text: 'Apply Credit',
                            btnClass: 'btn-blue',
                            action: function () {
                                this.buttons.applyCredit.disable();
                                var request = $.ajax({
                                    type: "POST",
                                    url: "{{ route('destroy.payment') }}",
                                    data: { 
                                        custId: {{$custId}},
                                        paymentId: _this.dataset.paymentid,
                                        paymethod: _this.dataset.paymethod
                                    },
                                    success: function (result) {
                                        location.reload(true);
                                    }
                                })
                                request.fail( function (jqXHR, textStatus) {
                                    $.alert (textStatus)
                                })
                            }
                        },
                        delete: {
                            text: 'Delete',
                            btnClass: 'btn-red',
                            action: function () {
                                $('#response').val('D');
                                this.buttons.applyCredit.disable();
                                var request = $.ajax({
                                    type: "POST",
                                    url: "{{ route('destroy.payment') }}",
                                    data: { 
                                        custId: {{$custId}},
                                        paymentId: _this.dataset.paymentid,
                                        paymethod: _this.dataset.paymethod
                                    },
                                    success: function (result) {
                                        location.reload(true);
                                    }
                                })
                                request.fail( function (jqXHR, textStatus) {
                                    $.alert (textStatus)
                                })
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {
                        // bind to events
                        var jc = this;
                        
                    }
                });

                //return false
        })

        $('#usecredit').click(function(e) {
            e.preventDefault();
            var totalAmt = $('#credit').attr('data-amount');
            //var amtAwed = $('#amountdue').attr('data-amount');
            var ref = $('#credit').attr('data-ref');

            // if (totalAmt > amtAwed)
            //     $('#amount').val(amtAwed);
            // else
            if ($(this).text()=="Cancel") {
                <?php if ($credit && isset($credit->amount)) { ?>
                $('#credit').text('$'+parseInt("{{$credit->amount}}").formatMoney(2,'.',','))
                $('#amount').val('');
                $('#creditused').val(0);
                <?php } ?>
            } else  {
                $('#credit').text('$0.00');
                $('#amount').val(totalAmt);
                $('#creditused').val(1);
            }
            $(this).text($(this).text()=="Use Credit" ? "Cancel" : "Use Credit");
            
            
            
            $('#ref').val(ref);
            
        })

        $('#amount').bind('input', function () {
            if ($('#usecredit').text()=="Cancel") {
                if ($(this).val()=='') {
                    <?php if ($credit) { ?>
                    var stt = parseInt("{{$credit->amount}}").formatMoney(2,'.',',');
                    <?php } ?>
                } else {
                    <?php if ($credit) { ?>
                        var stt = (parseInt("{{$credit->amount}}") - parseInt($(this).val())).formatMoney(2,'.',',');
                    <?php } ?>
                }
                $("#credit").text("$"+stt);
            }
        });

        $('#orders tbody').on('click', 'td', function () {
            var data = table.api().row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.api().column.index( 'fromVisible', visIdx );

            if (visIdx==1)
                if ($(data[visIdx])[0].tagName=="A") return
            
            // id = $(data[1]).text().replace(/\s|\*/gi,'')
            // if (table.api().column( this ).index() != 0) {
            //     //alert( 'You clicked on '+data[1]+'\'s row' );
            //     if (id.indexOf(' ')>0)
            //         text =  id.substr(0,data[1].indexOf(' '))
            //     else text= id;
            //     window.location.href = 'orders/'+text;
            // }
        } );

        function calculateTotalOwed(dt,indexes) {
            var owed=0;
            var data = dt.rows(indexes).data();
            var inactiveRecord = table.rows('.selected').data();

            if (inactiveRecord[0][4].indexOf('-',0)==-1) {
                
                    $.each(inactiveRecord, function (idx, value) {
                        owed += parseFloat(value[5].replace(/\$|,/g, ''));

                    });
                

                //$('#amount').val(owed)
                $('#ref').focus();
            }
        }

        table.on( 'select', function ( e, dt, type, indexes ) {
            //if ($('#amount').val()) return
            calculateTotalOwed(dt,indexes)
        } );

        table.on( 'deselect', function ( e, dt, type, indexes ) {
            calculateTotalOwed(dt,indexes)
        } );

        $('.save').click( function (e) {
            e.preventDefault();
            var _ids = [];
            ids = table.api().rows( { selected: true } ).data();
            
            if (!$('#amount').val() || !$('#ref').val()) {
                $('#amount').focus()
                $.alert ('Amount and/or reference cannot be left blank.');
                return;
            } else if (ids[0] == undefined) {
                $.alert ('No Invoice selected.');
                return;
            } 
            
            $.each (ids, function(key,value) {
                id=$(value[1]).text();
                
                _ids.push(id);
            })

            $.ajax({
                type: "POST",
                url: "{{ URL::route('credit_payment') }}",
                data: { 
                    ids: _ids,
                    form: $('#creditForm').serialize()
                },
                success: function (result) {
                    location.reload(true);
                }
            })
        })
    })
</script>
@endsection
@else
    @section ('content')
    <h2>No Invoice found for this customer.</h2>
    @endsection
@endif