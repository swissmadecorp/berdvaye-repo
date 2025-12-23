@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/public/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')
<form method="POST" action="{{route('dealers.store')}}">
    @csrf
    <input type="hidden" value="dealer" name="blade" />
    
    <div class="form-group row">
        <label for="dealer-input" class="col-3 col-form-label">Dealer Name</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('dealer')) ? old('dealer') : '' ?>" type="text" name="dealer" id="dealer-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="address-input" class="col-3 col-form-label">Address</label>
        <div class="col-9 input-group">
            <textarea rows="6" class="form-control" name="address" id="address-input"><?php echo !empty(old('address')) ? old('address') : '' ?></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="website-input" class="col-3 col-form-label">Website</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('website')) ? old('website') : '' ?>" type="text" name="website" id="website-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('phone')) ? old('phone') : '' ?>" type="text" name="phone" id="phone-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="latitude-input" class="col-3 col-form-label">Latitude</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('lat')) ? old('lat') : '' ?>" type="text" name="lat" id="latitude-input">
        </div>
    </div>
    <div class="form-group row">
        <label for="longitude-input" class="col-3 col-form-label">Longitude</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('lng')) ? old('lng') : '' ?>" type="text" name="lng" id="longitude-input">
        </div>
    </div>

    <button type="submit" class="btn btn-primary uploadPhoto">Save</button>

    @include('admin.errors')
</form>
@endsection

@section ('footer')
<script src="{{ asset('/public/js/jquery.autocomplete.min.js') }}"></script>
<script src="{{ asset('/public/js/dropzone.js') }}"></script>
@endsection

      