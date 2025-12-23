@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/public/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')
{{  Form::open(array('action'=>array('ImageCollectionController@store'), 'id' => 'imagecollectionform')) }}
    <input type="hidden" value="imagecollection" name="blade" />
    <div class="form-group row">
        <label for="image-input" class="col-3 col-form-label">Image *</label>
        <div class="col-9">
            <div id="dropzoneFileUpload" class="dropzone" required></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="title-input" class="col-3 col-form-label">Title</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('title')) ? old('title') : '' ?>" type="text" placeholder="Enter title" name="title" id="title-input">
        </div>
    </div>

    <div class="form-group row">
        <label for="order-input" class="col-3 col-form-label">Order</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('order')) ? old('order') : '' ?>" type="text" placeholder="Enter sort order" name="order" id="order-input">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>
    @include('admin.errors')
{{  Form::close() }}  
@endsection

@section ('footer')
<script src="{{ asset('/public/js/dropzone.js') }}"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        Dropzone.autoDiscover = false;
        
        var myDropzone = new Dropzone("div#dropzoneFileUpload", {
            url: "{{action('DropzoneController@uploadFiles')}}",
            maxFilesize: 10, // MB
            maxFiles: 1,
            parallelUploads: 1,
            dictDefaultMessage:'Drop files here or click to upload manually',
            addRemoveLinks: true,
            autoProcessQueue:false,
            uploadMultiple: true,
            params: {
                _token: "{{csrf_token()}}",
                _id: 0
            },
        });

        myDropzone.on("sending", function(file, xhr, formData){
            formData.append('_form',$('#productform').serialize());
        });

        myDropzone.on("success", function(file,resp){
            if(resp.message=="success"){
                //alert("Faild to upload image!");
                $('#productform').submit();
            }
        });

        $(".uploadPhoto").click( function(e) {
            e.preventDefault();
            myDropzone.processQueue();
        })

    })
</script>
@endsection        