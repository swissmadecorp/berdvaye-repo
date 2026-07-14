@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')
<h4>Purchased Products</h4>
<table id="products" class="table table-striped table-bordered hover">
    <thead>
        <tr>
        <th>Order Id</th>
        <th style="width:120px">Product</th>
        <th style="width:30px">Serial</th>
        <th style="width:30px">Method</th>
        <th>Created At</th>
        <th>Customer</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>{{$product->order_id}}</td>
            <td style="width: 200px"><a href="/admin/orders/{{ $product->order_id }}">{{ $product->model_name  }}</a></td>
            <td>{{ $product->p_serial }}</td>
            <td>{{ $product->method }}</td>
            <td>{{ $product->company }}</td>
            <td>{{ date('m-d-Y',strtotime($product->created_at)) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td><input type="text" name="search_product" value="Search product" class="search_init"></td>
            <td><input type="text" name="search_product" class="search_init"></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
</table>
<hr>
<div>
    <h4>Unpaid Orders</h4>
    <div class="alert-info clearfix" style="padding: 3px">
        <div class="float-right">
            <button type="submit" id="printStatements" class="btn btn-primary">Print Statements</button>
            <button type="submit" id="printItems" class="btn btn-primary">Print Itemized</button>
            <a href="reports/printUnpaid/0" target="_blank" id="printUnpaid" class="btn btn-primary">Print Unpaid</a>
        </div>
        <div class="float-right">
            <input type="text" style="width: 90%;padding: 5px;" placeholder="Year Filter" id="datepicker2">
        </div>
    </div>
    <hr>
    <table id="orders" class="table table-striped table-bordered hover">
        <thead>
            <tr>
            <th>Order Id</th>
            <th>Invoice Date</th>
            <th>Customer</th>
            <th>PO</th>
            <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $subtotal=0;//$grandTotal = 0;?>
            @foreach ($orders as $order)
                <?php $subtotal = $order->total - $order->payments->sum('amount') ?>
                
                @foreach($order->orderReturns as $returns)
                    <?php $subtotal -= $returns->pivot->amount*$returns->pivot->qty; ?>
                @endforeach

                <?php if ($subtotal>0) { ?>
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->created_at->format('m-d-Y') }}</td>
                <td>{{$order->s_company != '' ? $order->b_company : $order->b_firstname . ' '.$order->s_lastname }}</td>
                <td>{{$order->po}}</td>
                <td class="text-right" style="color:red">${{ number_format($subtotal,2) }}</td>
            </tr>
                <?php } ?>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="text-right" style="font-weight:bold">Row Total</td>
                <td colspan="3" class="text-right" style="font-weight:bold"></td>
            </tr>
        </tfoot>
    </table>
</div>

<hr>
<div>
    <h4>Paid Orders</h4>
    <div class="alert-info clearfix" style="padding: 3px">
        <div class="float-right">
            <button type="submit" id="printPaid" class="btn btn-primary">Print Order Statement</button>
            <button type="submit" id="printItems1" class="btn btn-primary">Print Order Items</button>
        </div>
        <div class="float-right">
            <input type="text" style="width: 90%;padding: 5px;" placeholder="Year Filter" id="datepicker1">
        </div>
    </div>
    <hr>
    <table id="paidOrders" class="table table-striped table-bordered hover">
        <thead>
            <tr>
            <th>Invoice Id</th>
            <th>Invoice Date</th>
            <th>Customer</th>
            <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $subtotal=0;//$grandTotal = 0;?>
            @foreach ($paidOrders as $order)
                <?php //$grandTotal += $order->total ?>
                <?php $subtotal = $order->total ?>
                
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->created_at->format('m-d-Y') }}</td>
                <td>{{$order->b_company != '' ? $order->b_company : $order->b_firstname . ' '.$order->s_lastname }}</td>
                <td class="text-right" style="color: green">${{ number_format($subtotal,2) }}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="text-right" style="font-weight:bold">Row Total</td>
                <td colspan="2" class="text-right" style="font-weight:bold"></td>
            </tr>
        </tfoot>
    </table>
</div>

<hr>
<div>
    <h4>Memo Orders</h4>
    <div class="alert-info clearfix" style="padding: 3px">
        <div class="float-right">
            <button type="submit" id="printItemizedMemo" class="btn btn-primary">Print Itemized</button>
            <a href="reports/printUnpaid/1" target="_blank" id="printMemos" class="btn btn-primary">Print Memo</a>
        </div>
        </div>
    <hr>
    <table id="memos" class="table table-striped table-bordered hover">
        <thead>
            <tr>
            <th>Order Id</th>
            <th>Memo Date</th>
            <th>Customer</th>
            <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $subtotal=0;$grandTotal = 0;?>
            @foreach ($memos as $memo)
                <?php //$grandTotal += $memo->total ?>
                <?php $subtotal = $memo->total ?>
                
                @foreach($memo->payments as $payment)
                    <?php $subtotal -= $payment->amount ?>
                    <?php //$grandTotal -= $subtotal ?>
                @endforeach

                @foreach($memo->orderReturns as $returns)
                    <?php $subtotal -= $returns->pivot->amount; ?>
                @endforeach

            <tr>
                <td>{{ $memo->id }}</td>
                <td>{{ $memo->created_at->format('m-d-Y') }}</td>
                <td>{{$memo->s_company != '' ? $memo->s_company : $memo->s_firstname . ' '.$memo->s_lastname }}</td>
                <td class="text-right">${{ number_format($subtotal,2) }}</td>
            </tr>
            @endforeach
            
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td class="text-right" style="font-weight:bold">Row Total</td>
                <td colspan="2" class="text-right" style="font-weight:bold"></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        var asInitVals = new Array();
        var mTable;

        Number.prototype.format = function(n, x) {
            var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
            return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
        };

        var pTable = $('#products').dataTable({
            "oLanguage": {
                "sSearch": "Search all columns:"
            },
            "order": [[ 0, "desc" ]],

        })

        var oTable = $('#orders').dataTable({
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                pageTotal = api
                    .column( 4, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                $( api.column( 3 ).footer() ).html(
                    '$'+pageTotal.format() +' ( $'+ total.format() +' total )'
                );
            },
            "order": [[ 0, "desc" ]]
       });

       var poTable = $('#paidOrders').dataTable({
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                pageTotal = api
                    .column( 3, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                $( api.column( 3 ).footer() ).html(
                    '$'+pageTotal.format() +' ( $'+ total.format() +' total )'
                );
            },
            "order": [[ 0, "desc" ]]
       });
       var mTable = $('#memos').dataTable({
        'search': 'applied',
        "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Total over this page
                pageTotal = api
                    .column( 3, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                $( api.column( 3 ).footer() ).html(
                    '$'+pageTotal.format() +' ( $'+ total.format() +' total )'
                );
            },
            "order": [[ 0, "desc" ]]
       });

        $("tfoot input").keyup( function () {
        /* Filter on the column (the index) of this element */
            pTable.fnFilter( this.value, $("tfoot input").index(this) );
        } );

         /*
        * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
        * the footer
        */
        $("tfoot input").each( function (i) {
            asInitVals[i] = this.value;
        } );
        
        $("tfoot input").focus( function () {
            if ( this.className == "search_init" )
            {
                this.className = "";
                this.value = "";
            }
        } );
        
        $("tfoot input").blur( function (i) {
            if ( this.value == "" )
            {
                this.className = "search_init";
                this.value = asInitVals[$("tfoot input").index(this)];
            }
        } );

        $('#orders tbody,#paidOrders tbody,#memos tbody').on('click', 'td', function () {
            if ($(this).parents('table').attr('id')=='paidOrders')
                cTable = poTable;
            else if ($(this).parents('table').attr('id')=='orders')
                cTable = oTable;
            else cTable = mTable;

            var data = cTable.api().row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = cTable.api().column.index( 'fromVisible', visIdx );

            window.location.href = 'orders/'+data[0];
            
        } );

        $('#printStatements').click ( function(e) {
            e.preventDefault();
            id = oTable.api().rows( { selected: true } ).data();
            
            window.location.href="reports/print/unpaid/"+$('#datepicker2').val();
        })

        $('#printItems,#printItems1').click ( function(e) {
            e.preventDefault();
            id = oTable.api().rows( { selected: true } ).data();
            
            if (e.currentTarget.id=='printItems') {
                if ($('#datepicker2').val() && !$('#orders_filter input').val())
                    window.location.href="reports/printsales/items/invoice/"+$('#datepicker2').val();
                else if ($('#datepicker2').val() && $('#orders_filter input').val())
                    window.location.href="reports/printsales/items/invoice/"+$('#datepicker2').val()+"/"+$('#orders_filter input').val()
                else window.location.href="reports/printsales/items/invoice/"+$('#orders_filter input').val()

            } else window.location.href="reports/print/items/invoice/"+$('#datepicker2').val();
        })

        $('#printPaid').click ( function(e) {
            e.preventDefault();
            id = oTable.api().rows( { selected: true } ).data();
            
            window.location.href="reports/print/paid/"+$('#datepicker1').val();

        })

        $('#printUnpaid,#printMemos').click ( function(e) {
            e.preventDefault();
            var printWindow = window.open(e.target.href, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=0,left=500,width=600,height=800");
            var printAndClose = function() {
                if (printWindow.document.readyState == 'complete') {
                    printWindow.print();
                    clearInterval(sched);
                }
            }
            var sched = setInterval(printAndClose, 1000);

        })

        $('#printItemizedMemo').click ( function(e) {
            e.preventDefault();
            var _ids = [];

            if ($('#memos_filter input').val()) {
                data = mTable.api().rows( { search: 'applied' } ).data()
                $.each (data, function(key,value) {
                    id=data[key][0];
                    
                    _ids.push(id);
                })
            }

            if (e.currentTarget.innerText == 'Print Itemized') {
                if (_ids.length) 
                    window.location.href="reports/printsales/items/memo/orderid="+_ids;
                else window.location.href="reports/printsales/items/memo";
            }

        })

    })
</script>
@endsection