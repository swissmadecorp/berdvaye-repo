@extends('layouts.admin-default')

@section ('header')
<link href="/css/dropzone.css" rel="stylesheet">
<link href="/editable-select/jquery-editable-select.css" rel="stylesheet">
@endsection

@section ('content')

    <form method="POST" action="{{route('productretail.store')}}" id="productretailform">
    @csrf
    <input type="hidden" name="title" id="title">
    <input type="hidden" name="blade" value="productretail">

    <div class="clearfix mb-4"></div>
    @include('admin.errors')
    <div class="form-group row">
        <label for="model-input" class="col-3 col-form-label">Model *</label>
        <div class="col-9">
            <input class="form-control" style="text-transform: uppercase" placeholder="Model name e.g. SPL" value="<?= !empty(old('p_model')) ? old('p_model') : '' ?>" type="text" name="p_model" id="model_name-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="model_name-input" class="col-3 col-form-label">Model Name *</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty(old('model_name')) ? old('model_name') : '' ?>" type="text" name="model_name" id="model_name-input" required>
        </div>
    </div>     
    <div class="form-group row">
        <label for="retail_price-name-input" class="col-3 col-form-label">Retail Price *</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty(old('p_retail')) ? old('p_retail') : '' ?>" type="text" name="p_retail" id="retail_price-name-input" required>
        </div>
    </div> 
    <div class="form-group row">
        <label for="heighest_serial-name-input" class="col-3 col-form-label">Heighest Serial *</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty(old('heighest_serial')) ? old('heighest_serial') : '' ?>" type="text" name="heighest_serial" id="heighest_serial-name-input" required>
        </div>
    </div> 

    <div class="form-group row">
        <label for="total_parts-input" class="col-3 col-form-label">Total parts *</label>
        <div class="col-9">
            <input class="form-control" placeholder="2,000 - 3,000" value="<?= !empty(old('total_parts')) ? old('total_parts') : '' ?>" type="text" name="total_parts" id="total_parts-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="size-input" class="col-3 col-form-label">Size</label>
        <div class="col-9">
        <input class="form-control" placeholder="Small / Large" value="<?= !empty(old('size')) ? old('size') : '' ?>" type="text" name="size" id="size-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="weight-input" class="col-3 col-form-label">Total Weight *</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty(old('weight')) ? old('weight') : '' ?>" type="text" name="weight" id="weight-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="dimensions-input" class="col-3 col-form-label">Dimensions *</label>
        <div class="col-9">
            <input class="form-control" value="<?= !empty(old('dimensions')) ? old('dimensions') : '' ?>" type="text" name="dimensions" id="dimensions-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="description-input" class="col-3 col-form-label">Description *</label>
        <div class="col-9">
            <textarea rows="5" class="form-control"  name="description" id="description-input"><?= !empty(old('dimensions')) ? old('dimensions') : '' ?></textarea>
        </div>
    </div>


    @if (!isset($product))
    <div class="form-group row">
        <label for="images-input" class="col-3 col-form-label">Images *</label>
        <div class="col-9">
            <div id="dropzoneFileUpload" class="dropzone" required></div>
        </div>
    </div>
    @endif

    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>

    @include('admin.errors')
</form>

@endsection

@section ('footer')
<script src="/js/dropzone.js"></script>
<script src="/editable-select/jquery-editable-select.js"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        $('#model').change( function() {
            $('#model_name').val($('option:selected',this).text());
        })

        Dropzone.autoDiscover = false;
        
        var myDropzone = new Dropzone("div#dropzoneFileUpload", {
            url: "{{route('upload.files')}}",
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

        $('#model').editableSelect({ effects: 'fade' })
            //.on('select.editable-select', function (e, li) {
                //$('#category_selected').val(li.val());
        //});
       
        myDropzone.on("sending", function(file, xhr, formData){
            $('#model_name').val($('option:selected').text());
            $('#title').val($('#model_name').val());
            formData.append('_form',$('#productretailform').serialize());
        });

        myDropzone.on("successmultiple", function(file,resp){
            if(resp.message=="success"){
                //alert("Faild to upload image!");
                //$('#productretailform').submit();
                window.location.href = "/admin/productretail"
            }
        });

        @if (!isset($productretail))
        $(".uploadPhoto").click( function(e) {
            if ($('.dz-image-preview').length > 0)
                e.preventDefault();
                
            myDropzone.processQueue();
        })
        @endif

    })    
</script>
@endsection