@extends('layouts.admin-default')

@section ('content')

    <form method="POST" action="{{route('products.store')}}" id="productform">
    @csrf   
    <div class="clearfix mb-4"></div>
    @include('admin.errors')
    <div class="form-group row">
        <label for="createdat-input" class="col-3 col-form-label">Image</label>
        <div class="col-9">
            <img class="form-control" style="width: 150px" src="/images/{{ $product->image() }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="reference-name-input" class="col-3 col-form-label">Reference Name</label>
        <div class="col-9">
            <input class="form-control" disabled value="<?php echo !empty($product->p_reference) ? $product->p_reference : '' ?>" type="text" placeholder="Enter reference name" name="reference" id="reference-name-input">
        </div>
    </div>    
    <div class="form-group row">
        <label for="model-number-input" class="col-3 col-form-label">Model Number</label>
        <div class="col-9">
            <input class="form-control" disabled value="<?php echo !empty($product->p_model) ? $product->p_model : '' ?>" type="text" placeholder="Enter model number" name="model" id="model-number-input">
        </div>
    </div>            
    <div class="form-group row">
        <label for="serial-input" class="col-3 col-form-label">Serial *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('serial')) ? old('serial') : '' ?>" autofocus type="text" placeholder="Enter serial number" name="serial" id="serial-input" required>
        </div>
    </div> 
    <div class="form-group row">
        <label for="heighest_serial-input" class="col-3 col-form-label">Heighest Serial #</label>
        <div class="col-9">
            <input class="form-control" disabled value="{{$product->retail->heighest_serial}}" type="text" placeholder="Enter heighest serial number" name="heighest_serial" id="heighest_serial-input"></div>
    </div>
    <div class="form-group row">
        <label for="price-input" class="col-3 col-form-label">Price</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span class="form-control disabled">{{ $product->retailvalue() / 2 }}</span>
        </div>
    </div>    
    <div class="form-group row">
        <label for="retail-input" class="col-3 col-form-label">Retail</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span class="form-control disabled">{{ $product->retailvalue() }}</span>
        </div>
    </div>
    <div class="form-group row">
        <label for="quantity-input" class="col-3 col-form-label">Quantity *</label>
        <div class="col-9">
            <input class="form-control" value="1" type="text" placeholder="Enter amount on hand" name="quantity" id="quantity-input" required>
        </div>
    </div>    
    <div class="form-group row">
        <label for="description-input" class="col-3 col-form-label">Comments</label>
        <div class="col-9">
            <textarea rows="6" class="form-control" type="text" name="comments" id="comments-input"><?php echo !empty($product->comments) ? $product->comments : '' ?></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>

    @include('admin.errors')
</form>
    <div id="search-customer"></div>

@endsection