@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')

<div class="table-responsive">
<table id="credits" class="table table-striped table-bcredited hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        
    @foreach ($credits as $credit)
        <tr>
            <td></td>
            <td>{{ $credit->id }}</td>
            <td>{{$credit->company == 'N/A' || !$credit->company ? $credit->firstname . ' '.$credit->lastname : $credit->company }}</td>
            <td>{{ $credit->created_at->format('m-d-Y') }}</td>
            <td class="text-right">${{ number_format($credit->amount,2) }}</td>
        </tr>
    @endforeach

    </tbody>
</table>

<hr/><br>
<div class="table-responsive">
<table id="customers" class="table table-striped table-bcredited hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Customer</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($customers as $customer)
        <tr>
            <td></td>
            <td>{{ $customer->id }}</td>
            <td>{{$customer->company == 'N/A' || !$customer->company ? $customer->firstname . ' '.$customer->lastname : $customer->company }}</td>
        </tr>
    @endforeach    
    </tbody>
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
        var table = $('#credits').DataTable({
            "deferRender": true,
            columnDefs: [ {
                creditable: false,
                className: 'select-checkbox',
                targets:   0,
            } ],
            "createdRow": function( row, data, dataIndex){
                if( data[5] ==  'Unpaid'){
                    $(row).addClass('unpaid');
                }
            },
            select: {
                style:    'os',
                selector: 'td:first-child'
            }
        });

        $('#credits tbody').on('click', 'td', function () {
            var data = table.row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.column.index( 'fromVisible', visIdx );

            if (table.column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                window.location.href = 'credits/'+data[1]+'/edit';
            }
        } );
        
        var tableCustomers = $('#customers').DataTable({
            "deferRender": true,
            columnDefs: [ {
                creditable: false,
                className: 'select-checkbox',
                targets:   0,
            } ],
            "createdRow": function( row, data, dataIndex){
                if( data[5] ==  'Unpaid'){
                    $(row).addClass('unpaid');
                }
            },
            select: {
                style:    'os',
                selector: 'td:first-child'
            }
        });

        $('#customers tbody').on('click', 'td', function () {
            var data = tableCustomers.row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = tableCustomers.column.index( 'fromVisible', visIdx );

            if (tableCustomers.column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                window.location.href = 'credits/'+data[1]+'/create';
            }
        } );

        $('.dropdown-menu button').click( function(e) {
            e.preventDefault();
            
            id = table.rows( { selected: true } ).data();
            
            if (id[0] == undefined) return;

            if (e.currentTarget.innerText == 'Delete') {
                if (confirm('Are you sure you want to delete selected product?')) {
                    window.location.href="credits/"+id[0][1]+'/destroy';
                }
            } else if (e.currentTarget.innerText == 'Print credit') {
                $('#paymentForm').attr('action','/admin/credits/'+id[0][1]+'/print')
                $('#paymentForm').submit();
            } 
            
        })
    })    
</script>
@endsection