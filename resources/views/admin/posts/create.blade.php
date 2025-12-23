@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/public/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')
{{  Form::open(array('action'=>array('PostsController@store'), 'id' => 'postform')) }}
    <input type="hidden" value="posts" name="blade" />
    <input type="hidden" name="new_id" id="new_id" />

    <div class="form-group row">
        <label for="title-input" class="col-3 col-form-label">Title *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('title')) ? old('title') : '' ?>" type="text" placeholder="Enter title" name="title" id="title-input" required>
        </div>
    </div>  

    <div class="form-group row">
        <label for="subtitle-input" class="col-3 col-form-label">Sub Title</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('subtitle')) ? old('subtitle') : '' ?>" type="text" placeholder="Enter subtitle" name="subtitle" id="subtitle-input">
        </div>
    </div>  

    <div class="form-group row">
        <label for="image-input" class="col-3 col-form-label">Image</label>
        <div class="col-9">
            <div id="dropzoneFileUpload" class="dropzone"></div>
        </div>
    </div>

    <div class="form-group row">
        <label for="post-input" class="col-3 col-form-label">Post</label>
        <div class="col-9">
            <textarea rows="4" class="form-control" value="<?php echo !empty(old('posts')) ? old('posts') : '' ?>" type="text" placeholder="Enter post" name="posts" id="post-input"></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>
    @include('admin.errors')
{{  Form::close() }}  
@endsection

@section ('footer')
<script src="{{ asset('/public/js/dropzone.js') }}"></script>
<script src="{{ asset('/public/js/tinymce/tinymce.min.js') }}"></script>
@endsection

@section ('jquery')
<script>
    tinymce.init({ 
        selector:'textarea', 
        theme: "modern",
        height:300,
        plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code',
    ],
        branding: false,
        force_br_newlines : true,
        force_p_newlines : false,
        forced_root_block : '',
        advlist_bullet_styles: "square"  // only include square bullets in list
    });

    $(document).ready( function() {
        Dropzone.autoDiscover = false;
        
        var myDropzone = new Dropzone("div#dropzoneFileUpload", {
            url: "{{action('DropzoneController@uploadFiles')}}",
            maxFilesize: 10, // MB
            maxFiles: 1,
            parallelUploads: 1,
            dictDefaultMessage:'Drop a single file or click to upload manually',
            addRemoveLinks: true,
            autoProcessQueue:false,
            uploadMultiple: true,
            params: {
                _token: "{{csrf_token()}}",
                _id: 0
            },
        });

        myDropzone.on("sending", function(file, xhr, formData){
            $('#post-input').val(tinyMCE.get('post-input').getContent());
            formData.append('_form',$('#postform').serialize());
        });

        myDropzone.on("success", function(file,resp){
            if(resp.message=="success"){
                //alert("Faild to upload image!");
                $('#new_id').val(resp.id);
                //$('#postform').submit();
                window.location.href = "/admin/posts"
            }
        });

        $(".uploadPhoto").click( function(e) {
            if ($('.dz-image-preview').length > 0)
                e.preventDefault();
                
            myDropzone.processQueue();
        })

    })
</script>
@endsection