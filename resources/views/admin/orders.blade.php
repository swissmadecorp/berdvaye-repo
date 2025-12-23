@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')

<div class="alert-info clearfix" style="padding: 3px">
    <div class="dropdown float-right">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Actions
        </button>
        <div class="dropdown-menu" style="left: -95px" aria-labelledby="dropdownMenu2">
            <button class="dropdown-item" type="button">Edit Order</button>
            <hr>
            <button class="dropdown-item" type="button">Print Order</button>
            <button class="dropdown-item" type="button">Print Statement</button>
            <button class="dropdown-item" type="button">Print Statements Due</button>
            <button class="dropdown-item" type="button">Print Commercial</button>
            <button class="dropdown-item" type="button">Print Packing Slip</button>
            <hr>
            <button class="dropdown-item" type="button">Email Invoice</button>
            @role('superadmin')
            <hr>
            <button class="dropdown-item" type="button">Payments</button>
            <hr>
            <button class="dropdown-item" type="button">Delete</button>
            @endrole
        </div>
    </div>
</div>
<hr/>
<div class="row yearDiv mb-2">
    <div class="col-10">
        <!-- <ul class="years"> -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Order Status
            </button>
            <div class="dropdown-menu status" aria-labelledby="dropdownMenu2">
                <button class="dropdown-item" data-action="unpaid">Display unpaid</button>
                <button class="dropdown-item" data-action="paid">Display paid</button>
                <button class="dropdown-item" data-action="returns">Display returns</button>
                <button class="dropdown-item" data-action="all">Display all</button>
                <button class="dropdown-item backorders" data-action="backorders">Display backorders</button>
                <button class="dropdown-item repairs" data-action="repairs">Display repairs</button>
            </div>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-10 repair">
        
    </div>
</div>
<hr/>
<div class="table-responsive">
<table id="orders" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>CuNo</th>
            <th>Invoice</th>
            <th>PO</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

</div>

<div id="quickpay" style="display:none">
    <label>Enter amount and the reference.</label>
    <form action="" id="creditForm" class="container">
        
            <div class="form-group row">
                <label for="amount-input" class="col-3 col-form-label">Amount:</label>
                <div class="col-12 input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" type="text" name="amount" placeholder="$0" id="amount-input">  
                </div>
            </div>
            <div class="form-group row">
                <label for="ref-input" class="col-3 col-form-label">Reference:  </label>
                <div class="col-12 input-group">
                    <input class="form-control" type="text" name="ref" id="ref-input">  
                </div>
            </div>
        
    </form>
</div>

<div id="contextMenu" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
      <li><a tabindex="-1" href="#">Print Invoice</a></li>
      <li><a tabindex="-1" href="#">Print Statement</a></li>
      <li><a tabindex="-1" href="#">Print Commercial</a></li>
      <hr>
      <li><a tabindex="-1" href="#">Email Invoice</a></li>
      <li><a tabindex="-1" href="#">Payments</a></li>
      @role('superadmin')
      <li><a tabindex="-1" href="#">Quick Pay</a></li>
      @endrole
    </ul>
</div>

<form action="orders/create">
    <button type="submit" class="btn btn-primary">Create New</button>
</form>

<form method="GET" id="paymentForm" style="display: none" action="{{ route('payments.create', array('id' => 0)) }}">
    <button class="btn btn-primary">Payments</button>
</form>

@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    var status='';
    $(document).ready( function() {
        var orderStatus = "unpaid";
        $.ajax({
            url: '{{route("get.backorders")}}',
            success: function (result) {
                if (result > 0) {
                    $('.backorders').html($('.backorders').html() + ' (' + result + ') ');
                    $('.status').css('width','200px');
                } else $('.backorders').remove();
            }
        })
    
        $(document).on('click', '.repair a', function(e) {
            e.preventDefault();
            
            
            table.search( 'repair' ).draw();
            //$(el).trigger({type: 'keypress', which: 13, keyCode: 13});
        })

        $('input[type="search"]').on( 'focus', function () {
            table.search( this.value ).draw();
        });

        $.ajax({
            url: '{{route("get.repairs")}}',
            success: function (result) {
                if (result > 0) {
                    $('.repairs').html($('.repairs').html() + ' (' + result + ') ');
                    $('.status').css('width','200px');
                } else $('.repairs').remove();
            }
        })

        var table = $('#orders').dataTable({
            "deferRender": true,
            stateSave: true,
            ajax: {
                url: "{{ route('ajax.order.status') }}",
                data: function(d){
                    orderStatus = status
                    
                    if (status == "")
                        orderStatus = "unpaid"
                        
                    return status
                },
                dataSrc: function(json) {
                    if ($('.repair a').length > 0)
                        $('.repair a').remove()

                    $('.repair').append(json.repair)
                    if (json.needAttention) {
                        needAttention = json.needAttention;
                        $.each(needAttention,function (i) {
                            $.alert(needAttention[i][3] + ' for order # ' 
                                + needAttention[i][1] 
                                + ' serial # ' 
                                + needAttention[i][2] 
                                + ' is at the repair center for ' 
                                + needAttention[i][0] 
                                + ' days. Please mark it received if received.' 
                            )
                        })
                        
                    }
                    return json.data;
                }   
            },
            scrollX: true,
            'processing': true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': '<div class="spinner"></div>'
                },
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0,
            },{
                className: 'dt-body-right',
                targets:   7
            },{
                className: 'dt-body-right',
                targets:   8
            }],
            "aaSorting": [[ 1, 'desc']],
            "createdRow": function( row, data, dataIndex){
                if( data[6] ==  'Unpaid'){
                    $(row).addClass('unpaid');
                }
            },
            select: {
                style:    'os',
                selector: 'td:first-child'
            }
        });

        //table.api().column( 9 ).visible( false );

        $('#orders tbody').on('click', 'td', function () {
            var data = table.api().row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.api().column.index( 'fromVisible', visIdx );

            if (visIdx==1)
                if ($(data[visIdx])[0].tagName=="A") return
            
            id = $(data[1]).text().replace(/\s|\*/gi,'')
            if (table.api().column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                if (id.indexOf(' ')>0)
                    text =  id.substr(0,data[1].indexOf(' '))
                else text= id;
                window.location.href = 'orders/'+text;
            }
        } );
        
        var $contextMenu = $("#contextMenu");
        var rowSel

        $("body").on("contextmenu", "table tr", function(e) {
            $contextMenu.css({
                display: "block",
                left: e.pageX-200,
                top: e.pageY-40
            });
            //debugger;
            //$(rowSel).removeClass('unpaid');
            rowSel = $(this)
            //$(rowSel).removeClass('unpaid');
            return false;
        });

        $('html').click(function(e) {
            //$(rowSel).addClass('unpaid');
            $contextMenu.hide();
        });

        $("#contextMenu li a").click(function(e){
            e.preventDefault();
            var f = $(rowSel).children(0).eq(1).find("a");

            if ($(this).html() == "Print Invoice") {
                printInvoice(f.html(),'invoice');
            } else if ($(this).html() == "Print Statement") {
                printInvoice($(rowSel).children(0).eq(2).text(),'statement');
            } else if ($(this).html() == 'Email Invoice') {
                window.location.href='orders/'+f.html()+'/print/email';
            } else if ($(this).html() == 'Print Commercial') {
                printInvoice(f.html(),'commercial');
            } else if ($(this).html() == 'Payments') {
                $('#paymentForm').attr('action','/admin/orders/'+$(rowSel).children(0).eq(2).html()+'/payments/create')
                $('#paymentForm').submit();
            } else if ($(this).html() == 'Quick Pay') {
                QuickPayment($(rowSel).children(0).eq(1).text(),$(rowSel).children(0).eq(2).text());
            }
            //debugger;
        });

        function QuickPayment(orderId,custId) {
            $.confirm({
                title: 'Quick Pay',
                content: $('#quickpay').html(),
                boxWidth: '30%',
                useBootstrap: false,
                buttons: {
                    formApply: {
                        text: 'Apply',
                        btnClass: 'btn-blue',
                        action: function () {
                            amount = this.$content.find('#amount-input').val();
                            ref = this.$content.find('#ref-input').val();
                            
                            var stForm = "creditused=0&amount="+amount+"&ref="+ref+"&customer_id="+custId;

                            var request = $.ajax({
                                type: "POST",
                                url: "{{ URL::route('credit_payment') }}",
                                data: { 
                                    ids: orderId,
                                    form: stForm
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
        }

        function printInvoice(id,command) {
            var printWindow, showWindow
            
            if (command == 'invoice') {
                showWindow = "/admin/orders/"+id+"/print", "_blank";
            } else if (command == 'statement') {
                showWindow = "/admin/orders/"+id+"/"+orderStatus+"/printstatement";
            } else if (command == 'slip') {
                showWindow = "/admin/orders/"+id+"/print/packingslip";
            } else if (command == 'commercial') {
                showWindow = "/admin/orders/"+id+"/print/commercial";
            }
            
            printWindow = window.open(showWindow, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=0,left=500,width=600,height=800");
            
            var printAndClose = function() {
                if (printWindow.document.readyState == 'complete') {
                    printWindow.print();
                    clearInterval(sched);
                    setTimeout(function() {
                        printWindow.close()
                    }, 6000);
                }
            }
            var sched = setInterval(printAndClose, 1000);
        }

        $('.dropdown-menu button').click( function(e) {
            e.preventDefault();
            
            data = table.api().rows( { selected: true } ).data();
            
            if (e.currentTarget.innerText == 'Print Statements Due') {
                $('#paymentForm').attr('action','/admin/printstatementsdue')
                $('#paymentForm').submit();
            }

            id = table.api().rows( { selected: true } ).data();
            
            if (id[0] == undefined) return;

            _id = $(id[0][1]).text().replace(/\s|\*/gi,'')
            if (_id.indexOf(' ')>0)
                text =  _id.substr(0,_id.indexOf(' '))
            else text=_id;
            
            if (e.currentTarget.innerText == 'Delete') {
                if (confirm('Are you sure you want to delete selected product?')) {
                    window.location.href="orders/"+text+'/destroy';
                }
            } else if (e.currentTarget.innerText == 'Payments') {
                $('#paymentForm').attr('action','/admin/orders/'+id[0][2]+'/payments/create')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Edit Order') {
                $('#paymentForm').attr('action','/admin/orders/'+text+'/edit')
                $('#paymentForm').submit();                
            } else if (e.currentTarget.innerText == 'Email Invoice') {
                window.location.href='orders/'+text+'/print/email';
            } else if (e.currentTarget.innerText == 'Print Packing Slip') {
                $('#paymentForm').attr('action','/admin/orders/'+text+'/print/packing_slip')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Print Order') {
                $('#paymentForm').attr('action','/admin/orders/'+text+'/print')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Print Statement') {
                
                $('#paymentForm').attr('action','/admin/orders/'+id[0][2]+"/"+orderStatus+'/printstatement')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Print Commercial') {
                var printWindow = window.open("/admin/orders/"+text+"/print/commercial", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=0,left=500,width=600,height=800");
                var printAndClose = function() {
                    if (printWindow.document.readyState == 'complete') {
                        printWindow.print();
                        clearInterval(sched);
                    }
                }
                var sched = setInterval(printAndClose, 1000);
            } 
            
        })
        
        $('.status button').click( function () {
            orderStatus = $(this).attr('data-action');
            table.api().ajax.url("/admin/ajaxorderstatus?action="+orderStatus).load();
            //table.api().ajax.reload(null,true); //.url("/admin/ajaxorderstatus").load();
        })
    })    
</script>
@endsection