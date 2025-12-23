@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')

<form method="POST" action="{{route('productretail.update',[$productretail->id])}}" accept-charset="UTF-8" id="productretailform">
    @csrf
    @method('PATCH')
    <input type="hidden" name="title" id="title">
    <input type="hidden" name="blade" value="productretail">
    <input type="hidden" name="_id" value="{{$productretail->id}}" />
    
    <div class="clearfix mb-4"></div>
    @include('admin.errors')
    
    <div class="form-group row">
        <label for="model-number" class="col-3 col-form-label">Model Id</label>
        <div class="col-9">
            <input class="form-control" style="text-transform: uppercase" value="{{$productretail->p_model}}" type="text" name="p_model" id="model-number" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="model-name" class="col-3 col-form-label">Model Name</label>
        <div class="col-9">
            <input class="form-control" value="{{$productretail->model_name}}" type="text" name="model_name" id="model_name" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="retail_price-input" class="col-3 col-form-label">Retail Price *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty($productretail->p_retail) ? $productretail->p_retail : '' ?>" type="text" name="p_retail" id="retail_price-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="heighest_serial-input" class="col-3 col-form-label">Heighest Serial *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty($productretail->heighest_serial) ? $productretail->heighest_serial : '' ?>" type="text" name="heighest_serial" id="heighest_serial-input" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="total_parts-input" class="col-3 col-form-label">Total parts *</label>
        <div class="col-9">
            <input class="form-control" placeholder="2,000 - 3,000" value="<?php echo !empty($productretail->total_parts) ? $productretail->total_parts : '' ?>" type="text" name="total_parts" id="total_parts-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="size-input" class="col-3 col-form-label">Size</label>
        <div class="col-9">
            <input class="form-control" placeholder="Small / Large" value="<?php echo !empty($productretail->size) ? $productretail->size : '' ?>" type="text" name="size" id="size-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="weight-input" class="col-3 col-form-label">Total Weight *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty($productretail->weight) ? $productretail->weight : '' ?>" type="text" name="weight" id="weight-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="dimensions-input" class="col-3 col-form-label">Dimensions *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty($productretail->dimensions) ? $productretail->dimensions : '' ?>" type="text" name="dimensions" id="dimensions-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="description-input" class="col-3 col-form-label">Description *</label>
        <div class="col-9">
            <textarea rows="5" class="form-control"  name="description" id="description-input"><?php echo !empty($productretail->description) ? $productretail->description : '' ?></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="image-name-input" class="col-3 col-form-label">Image</label>
        <div class="col-9">
            @if (!empty($productretail->image_location))
            <div class="image-container">
                <div class="image">
                    <div class="delete-image">X</div>
                    <img class="form-control" src="/images/thumbs/{{$productretail->image_location}}" type="text" placeholder="Enter new image name" name="image" id="image-name-input" />
                </div>
            </div>
            <div id="dropzoneFileUpload" style="display:none" class="dropzone"></div>
            @else 
                <div id="dropzoneFileUpload" class="dropzone"></div>
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label for="is_active-input" class="col-3 col-form-label">Is Active</label>
        <div class="col-9 align-content-center col-9 flex-column">
            <input <?php echo !empty($productretail->is_active) ? 'checked' : '' ?> type="checkbox" name="is_active" id="is_active-input" >
        </div>
    </div>
    @role('superadmin')
    <button type="submit" class="btn btn-primary uploadPhoto">Update</button>
    @endrole

    @include('admin.errors')
    
</form>  
@endsection

@section ('footer')
<script src="{{ asset('/js/dropzone.js') }}"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("div#dropzoneFileUpload", {
            url: "{{route('upload.files')}}",
            maxFilesize: 10, // MB
            maxFiles: 6,
            parallelUploads: 6,
            dictDefaultMessage:'Drop files here or click to upload manually',
            addRemoveLinks: true,
            autoProcessQueue:false,
            uploadMultiple: true,
            params: {
                _token: "{{csrf_token()}}",
            },
        });

        myDropzone.on("sending", function(file, xhr, formData){
            $('#title').val($('#model_name').val());
            formData.append('_form',$('#productretailform').serialize());
        });

        myDropzone.on("successmultiple", function(file,resp){
            if(resp.message!="success"){
                alert("Faild to upload image!");
            }

            window.location.href = "/admin/productretail"
        });

        $(".uploadPhoto").click( function(e) {
            if ($('.dz-image-preview').length > 0)
                e.preventDefault();
                
            myDropzone.processQueue();
        })

        $('.delete-image').click( function() {
            var _this = $(this);
            var request = $.ajax({
                type: "POST",
                url: "{{route('delete.productretail.image')}}",
                data: { 
                    _token: "{{csrf_token()}}",
                    id: {{ $productretail->id }}
                },
                success: function (result) {
                    $(_this).parent('.image').remove();
                    
                    if ($('.delete-image').length==0) {
                        $('.image-container').remove();
                        $('#dropzoneFileUpload').show()
                    }

                }
            })

            request.fail( function (jqXHR, textStatus) {
                //alert ("Requeset failed: " + textStatus)
            })
        })
    })
</script>
@endsection