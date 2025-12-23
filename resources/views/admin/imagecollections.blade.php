@extends('layouts.admin-default')

@section ('content')
<table id="images" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Thumnail</th>
            <th>Title</th>
            <th>Filename</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($images as $image)
        <tr>
            <td>{{ $image->id }}</td>
            <td><img src="/public/uploads/collections/thumbs/{{ $image->location }}" style="width: 100px" /></td>
            <td>{{ $image->title }}</td>
            <td>{{ $image->location }}</td>
            <td style="text-align: center">
                <button type="button" style="padding: 3px 5px" data-id="{{ $image->id }}" class="btn btn-danger deleteitem" aria-label="Left Align">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>


    <a href="imagecollections/create" class="btn btn-success">Create New</a>

@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        $('.deleteitem').click( function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete selected payment?')) {
                window.location.href=window.location.href+'/'+$(this).attr('data-id')+'/destroy';
            }
        })

        $('.table tr').click( function () {
            window.location.href=window.location.href+'/'+$('button',this).attr('data-id')+'/edit';
        })
    })    
</script>
@endsection