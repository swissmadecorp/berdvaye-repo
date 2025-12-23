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
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <button class="dropdown-item" type="button">Edit Order</button>
            <hr>
            <button class="dropdown-item" type="button">Print Order</button>
            <button class="dropdown-item" type="button">Print Statement</button>
            <button class="dropdown-item" type="button">Print Statements Due</button>
            <button class="dropdown-item" type="button">Print Commercial</button>
            <button class="dropdown-item" type="button">Print Packing Slip</button>
            <hr>
            <button class="dropdown-item" type="button">Email Invoice</button>
            
            <hr>
            <button class="dropdown-item" type="button">Payment</button>
            <hr>
            <button class="dropdown-item" type="button">Delete</button>
            
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
<form action="orders/create">
    <button type="submit" class="btn btn-primary">Create New</button>
</form>

<form method="GET" id="paymentForm" style="display: none" action="{{ route('payments.create', array('id' => 0)) }}">
    <button class="btn btn-primary">Payment</button>
</form>

@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        $.ajax({
            url: '{{route("get.backorders")}}',
            success: function (result) {
                if (result > 0) {
                    $('.backorders').html($('.backorders').html() + ' (' + result + ') ');
                    $('.status').css('width','200px');
                } else $('.backorders').remove();
            }
        })
    
        $.ajax({
            url: '{{route("get.repairs")}}',
            success: function (result) {
                if (result > 0) {
                    $('.repairs').html($('.repairs').html() + ' (' + result + ') ');
                    $('.status').css('width','200px');
                } else $('.repairs').remove();
            }
        })

        var table = $('#orders').DataTable({
            "deferRender": true,
            stateSave: true,
            ajax: {
                url: "{{ action('OrdersController@ajaxOrderStatus') }}",
                data: function(d){
                    return status
                },
                dataSrc: function(json) {
                    return json.data;
                }   
            },
            scrollX:        true,
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
            "aaSorting": [[ 7, 'desc'],[ 1, 'desc']],
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

        $('#orders tbody').on('click', 'td', function () {
            var data = table.row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.column.index( 'fromVisible', visIdx );

            if (visIdx==1)
                if ($(data[visIdx])[0].tagName=="A") return
            
            id = $(data[1]).text().replace(/\s|\*/gi,'')
            if (table.column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                if (id.indexOf(' ')>0)
                    text =  id.substr(0,data[1].indexOf(' '))
                else text= id;
                window.location.href = 'sculptureorders/'+text;
            }
        } );
        
        $('.dropdown-menu button').click( function(e) {
            e.preventDefault();
            
            data = table.rows( { selected: true } ).data();
            
            if (e.currentTarget.innerText == 'Print Statements Due') {
                $('#paymentForm').attr('action','/admin/printstatementsdue')
                $('#paymentForm').submit();
            }

            if (data[0] == undefined) return;
            if (e.currentTarget.innerText == 'Payment')
                id = data[0][2] 
            else id = $(data[0][1]).text().replace(/\s|\*/gi,'')
            
            if (e.currentTarget.innerText == 'Delete') {
                if (confirm('Are you sure you want to delete selected product?')) {
                    window.location.href="sculptureorders/"+id+'/destroy';
                }
            } else if (e.currentTarget.innerText == 'Payment') {
                $('#paymentForm').attr('action','/admin/sculptureorders/'+id+'/payments/create')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Edit Order') {
                $('#paymentForm').attr('action','/admin/sculptureorders/'+id+'/edit')
                $('#paymentForm').submit();                
            } else if (e.currentTarget.innerText == 'Email Invoice') {
                window.location.href='sculptureorders/'+id+'/print/email';
            } else if (e.currentTarget.innerText == 'Print Packing Slip') {
                $('#paymentForm').attr('action','/admin/sculptureorders/'+id+'/print/packing_slip')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Print Order') {
                $('#paymentForm').attr('action','/admin/sculptureorders/'+id+'/print')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Print Statement') {
                $('#paymentForm').attr('action','/admin/sculptureorders/'+id+'/printstatement')
                $('#paymentForm').submit();
            } else if (e.currentTarget.innerText == 'Print Commercial') {
                var printWindow = window.open("/admin/sculptureorders/"+id+"/print/commercial", "_blank", "toolbar=no,scrollbars=yes,resizable=yes,top=0,left=500,width=600,height=800");
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
            status = $(this).attr('data-action');
            table.ajax.url("/admin/ajaxorderstatus?action="+status).load();
            //table.ajax.reload( null, true )
        })
    })    
</script>
@endsection