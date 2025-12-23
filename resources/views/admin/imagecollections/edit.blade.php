@extends('layouts.admin-default')

@section ('content')
{{  Form::model($image, array('route' => array('imagecollections.update', $image->id), 'method' => 'PATCH', 'id' => 'productform')) }} 
    
    <div class="form-group row">
        <label for="image-name-input" class="col-3 col-form-label">Image</label>
        <div class="col-9">
            <img class="form-control" style="width: 300px" src="/public/uploads/collections/thumbs/<?php echo $image->location ?>" type="text" placeholder="Enter new image name" name="image" id="image-name-input" />
        </div>
    </div>

    <div class="form-group row">
        <label for="title-name-input" class="col-3 col-form-label">Title *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo $image->title ?>" type="text" placeholder="Enter new title name" name="title" id="title-name-input" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="order-input" class="col-3 col-form-label">Order</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo $image->order ?>" type="text" placeholder="Enter sort order" name="order" id="order-input">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    @include('admin.errors')
{{  Form::close() }}
@endsection