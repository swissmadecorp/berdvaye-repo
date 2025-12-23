@extends('layouts.admin-default')

@section ('content')

    <form method="POST" action="{{route('products.store')}}" id="productform">
    @csrf
    <p>Start by typing in the model number. If product doesn't exist in the system, create it through <a href="/admin/productretail/create">Product Prices.</a></p>
    <input type="hidden" name="product_retail_id" id="product_retail_id">
    <div class="clearfix mb-4"></div>
    @include('admin.errors')
    <div class="form-group row">
        <label class="col-3 col-form-label">Image</label>
        <div class="col-9">
            <img class="height38" id="img" style="width: 150px">
        </div>
    </div>
    <div class="form-group row">
        <label for="reference" class="col-3 col-form-label">Reference Name *</label>
        <div class="col-9">
            <input type="hidden" name="p_model" id="p_model">
            <input class="form-control" disabled  type="text" id="reference">
        </div>
    </div> 
    <div class="form-group row">
        <label for="model-name-input" class="col-3 col-form-label">Model Name *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('model')) ? old('model') : '' ?>" type="text" placeholder="Enter model name or reference" name="model_name" id="model-name-input" required>
        </div>
    </div>     
    <div class="form-group row">
        <label for="serial-input" class="col-3 col-form-label">Serial # *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('p_serial')) ? old('p_serial') : '' ?>" type="text" name="p_serial" id="serial-input" required>
        </div>
    </div> 
    <div class="form-group row">
        <label class="col-3 col-form-label">Heighest Serial</label>
        <div class="col-9">
            <span class="form-control disabled height38" id="heighestserial-input"></span>
        </div>
    </div> 
    <div class="form-group row">
        <label class="col-3 col-form-label">Price *</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span class="form-control disabled" id="price"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Retail Price*</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span class="form-control disabled" id="retail"></span>
        </div>
    </div>
    <div class="form-group row">
        <label for="quantity-input" class="col-3 col-form-label">Quantity *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty(old('qty')) ? old('qty') : '' ?>" type="text" name="p_qty" id="quantity-input" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>

    @include('admin.errors')
</form>
    <div id="search-customer"></div>

@endsection

@section ('footer')
<script src="{{ asset('/js/jquery.autocomplete.min.js') }}"></script>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        
        var selVal
        $('#model-name-input').blur (function() {
            selVal = $('.autocomplete-selected').attr('data-index');
            if (selVal)
                findByModel({value: $('#model-name-input').data('autocomplete').suggestions[selVal].value, data: $('#model-name-input').data('autocomplete').suggestions[selVal].data});
        })

        function findByModel(suggestion) {
            $.ajax({
                type: "GET",
                sync: false,
                url: "{{route('select.found.product')}}",
                data: { 
                    _id: suggestion.data,
                    _blade: 'product'

                },
                success: function (result) {
                    if (result) {
                        $('#img').attr('src',result['imgname']);
                        $('#heighestserial-input').text(result['heighestserial']);
                        $('#img').removeClass('height38').addClass('form-control');
                        $('#reference').val(result['model']);
                        $('#product_retail_id').val(result['id']);
                        $('#p_model').val(result['model']);
                        $('#model-name-input').val(result['reference']);

                        $('#quantity-input').val(1);
                        $('#price').text(result['price']);
                        $('#retail').text(result['retail']);
                        $('#serial-input').focus();
                        return 1
                    } else {
                        $.alert('Model not found in the system. Please select from available items in the drop-down list');
                        $('#productform').find("input[type=text], textarea, span").text("")
                        return 0
                    }
                }
            })
            return 0
        }

        $('#model-name-input').devbridgeAutocomplete({
            serviceUrl: "{{route('get.retailproducts')}}",
            showNoSuggestionNotice : true,
            minChars: 1,
            width: 240,
            zIndex: 900,
            onSelect: function (suggestion) {
                findByModel(suggestion)
            }
        });
    })

</script>
@endsection