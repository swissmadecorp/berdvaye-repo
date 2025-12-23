@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')

<div class="alert-info clearfix" style="padding: 3px">
    <div class="dropdown float-right">
        <button class="btn btn-secondary" id="delete" type="button">Delete</button>
    </div>
</div>
<hr>
<div class="table-responsive">
<table id="returns" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Id</th>
            <th>Ref.</th>
            <th>Model</th>
            <th>Retail Price</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    
    @foreach ($productretails as $productretail)
        <tr>
            <td></td>
            <td>{{ $productretail->id }}</td>
            <td>{{ strtoupper($productretail->p_model) }}</td>
            <td>{{ $productretail->model_name }}</td>
            <td>${{ number_format($productretail->p_retail,2) }}</td>
            <td>{{ $productretail->created_at->format('m/d/Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</div>

@role('superadmin')
<form action="productretail/create">
    <button type="submit" class="btn btn-primary">Create New</button>
</form>
@endrole

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
            stateSave: true,
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            "aaSorting": [[ 1, 'asc']],
        });

        $('#returns tbody').on('click', 'td', function () {
            var data = table.api().row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.api().column.index( 'fromVisible', visIdx );

            if (table.api().column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                window.location.href = 'productretail/'+data[1]+'/edit';
            }
        } );

        $('#delete').click( function(e) {
            e.preventDefault();
            
            id = table.api().rows( { selected: true } ).data();
            
            if (id[0] == undefined) {
                alert('Please select a product and try again.');
                return;
            }
            if (e.currentTarget.innerText == 'Delete') {
                if (confirm('Are you sure you want to delete selected product?')) {
                    window.location.href="productretail/"+id[0][1]+"/destroy";
                }
            }

        })
    })    
</script>
@endsection