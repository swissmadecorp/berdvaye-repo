@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/public/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')

<form method="POST" action="{{route('dealers.update',[$dealer->id])}}" accept-charset="UTF-8" >
    @csrf
    @method('PATCH')
    <input type="hidden" value="dealer" name="blade" />
    <input type="hidden" value="{{ $dealer->customer }}" name="title" />
    <input type="hidden" value="{{ $dealer->id }}" name="_id" />

    <div class="form-group row">
        <label for="customer-input" class="col-3 col-form-label">Customer Name</label>
        <div class="col-9 input-group">
            <input class="form-control" value="<?= !empty($dealer->customer) ? $dealer->customer : '' ?>" type="text" placeholder="Enter customer" name="dealer" id="customer-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="address-input" class="col-3 col-form-label">Address</label>
        <div class="col-9 input-group">
            <textarea name="address" id="address-input" rows="5" class="form-control"><?= !empty($dealer->address) ? $dealer->address : '' ?></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="website-input" class="col-3 col-form-label">Website</label>
        <div class="col-9">
            <input class="form-control" autocomplete="off"  value="<?= !empty($dealer->website) ? $dealer->website : '' ?>" type="text" placeholder="Enter website address" name="website" id="website-input">
        </div>
    </div>

    <div class="form-group row">
        <label for="phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9">
            <input class="form-control" autocomplete="off"  value="<?= !empty($dealer->phone) ? $dealer->phone : '' ?>" type="text" placeholder="Enter phone number" name="phone" id="phone-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="latitude-input" class="col-3 col-form-label">Latitude</label>
        <div class="col-9">
            <input class="form-control" autocomplete="off"  value="<?= !empty($dealer->lat) ? $dealer->lat : '' ?>" type="text" placeholder="Enter Google Latitude" name="lat" id="latitude-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="longitude-input" class="col-3 col-form-label">Longitude</label>
        <div class="col-9">
            <input class="form-control" autocomplete="off"  value="<?= !empty($dealer->lng) ? $dealer->lng : '' ?>" type="text" placeholder="Enter Google Longitude" name="lng" id="longitude-input">
        </div>
    </div>
    @if (!empty($dealer->logo))
    <div class="form-group row">
        <label for="reference-input" class="col-3 col-form-label">Customer Logo</label>
        <div class="col-9">
            <div class="image-container">
                <div class="image">
                    <div class="delete-image">X</div>
                    <img alt="{{ $dealer->customer }}" data-id="{{$dealer->id}}" src="/public/images/logo/{{ $dealer->logo }}" >
                </div>
            </div>
            <div id="dropzoneFileUpload" style="display:none" class="dropzone"></div>
        </div>
    </div>
    @else
    <div class="form-group row">
        <label for="images-input" class="col-3 col-form-label">Customer Logo</label>
        <div class="col-9">
            <div id="dropzoneFileUpload" class="dropzone"></div>
        </div>
    </div>
    @endif

    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>
    
 
</div>

    @include('admin.errors')
</form>
@endsection

@section ('footer')
<script src="{{ asset('/public/js/dropzone.js') }}"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
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
                _form: $('#dealerform').serialize(),
            },
        });

        myDropzone.on("successmultiple", function(file,resp){
            if(resp.message!="success"){
                alert("Faild to upload image!");
            }

            window.location.href = "/admin/dealers"
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
                url: "{{route('delete.files')}}",
                data: { 
                    _token: "{{csrf_token()}}",
                    dealer_id: $(_this).next().attr('data-id'),
                },
                success: function (result) {
                    $(_this).parent('.image').remove();
                    $('#dropzoneFileUpload').show();
                    $('.image-container').hide();
                }
            })

            request.fail( function (jqXHR, textStatus) {
                //alert ("Requeset failed: " + textStatus)
            })
        })
    })
</script>
@endsection