@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')
<table id="dealers" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($dealers as $dealer)
        <tr>
            <td>{{ $dealer->id }}</td>
            <td>{{ $dealer->customer }}</td>
            <td>{{ $dealer->created_at->format('m-d-Y') }}</td>
            <td style="text-align: center">
            <form method="POST" action="{{route('dealers.destroy',[$dealer->id])}}">
                @csrf
                @method('DELETE')
                <button type="submit" style="padding: 3px 5px" data-id="{{ $dealer->id }}" class="btn btn-danger deleteitem" aria-label="Left Align">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<form action="dealers/create">
    <button type="submit" class="btn btn-success">Create New</button>
</form>
@endsection

@section ('footer')
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        var table = $('#dealers').DataTable({
            "deferRender": true,
            "order": [[ 0, "desc" ]],
            "columns": [
                { "width": "5%" },
                { "width": "25%" },
                { "width": "5%" },
                { "width": "5%" }
            ]
        });

        $('#dealers tbody').on('click', 'td', function () {
            var data = table.row( this ).data();
            var visIdx = $(this).index();
            var dataIdx = table.column.index( 'fromVisible', visIdx );

            if (table.column( this ).index() != 0) {
                //alert( 'You clicked on '+data[1]+'\'s row' );
                window.location.href = 'dealers/'+data[0]+'/edit';
            }
        } );
        
        $('deleteitem').click( function(e) {
            e.stopPropagation()
            if (!confirm('Are you sure you want to delete this dealer?')) {
                e.preventDefault();
                return false
            }

            return true
        })

    })    
</script>
@endsection