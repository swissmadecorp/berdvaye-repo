@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')

<div class="alert-info clearfix" style="padding: 3px">
    <div class="float-right">
    <button type="submit" id="printReturn" class="btn btn-primary">Print Return</button>
    </div>
</div>
<hr>

<div class="table-responsive">
<table id="returns" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Return Id</th>
            <th>Order Id</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    
    @foreach ($returns as $return)
        <?php $amount = 0; $order = $return->orders->first(); ?>
        <?php if ($order) {?>
        <?php foreach ($order->orderReturns as $item) { ?>
            <?php $amount += $item->pivot->amount; ?>
        <?php } ?>
        <tr>
            <td></td>
            <td>{{ $return->id }}</td>
            <td>{{ $order->id }}</td>
            <td>{{$order->b_company != '' ? $order->b_company : $order->b_firstname . ' '.$order->b_lastname }}</td>
            <td>${{ number_format($amount,2) }}</td>
            <td>{{ date('m/d/Y',strtotime($return->date)) }}</td>
        </tr>
        <?php } ?>
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
        var table = $('#returns').dataTable({
            "deferRender": true,
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            "aaSorting": [[ 1, 'desc']],
        });

        $('#returns tbody').on('click', 'td', function () {
            var data = table.api().row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.api().column.index( 'fromVisible', visIdx );

            if (table.api().column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                window.location.href = 'orders/'+data[2]+'/returns/create';
            }
        } );

        
        $('#printReturn').click ( function(e) {
            e.preventDefault();
            id = table.api().rows( { selected: true } ).data();
            
            window.location.href="orders/"+id[0][2]+"/returns/print";

        })

    })    
</script>
@endsection