@extends('layouts.admin-default')

@section ('header')
<link href="{{ asset('/css/dropzone.css') }}" rel="stylesheet">
@endsection

@section ('content')

<form method="POST" action="{{route('products.update',[$product->id])}}" accept-charset="UTF-8" id="productform">
    @csrf
    @method('PATCH')
    <input type="hidden" value="{{$product->retail->id}}" id="_id" name="_id">

    @role('superadmin')
    <button type="submit" class="btn btn-primary uploadPhoto" style="float: right">Update</button>
    @endrole
    
    <div class="clearfix mb-4"></div>
    @include('admin.errors')
    <div class="form-group row">
        <label for="createdat-input" class="col-3 col-form-label">Created At</label>
        <div class="col-9">
            <span class="form-control">{{ $product->created_at->format('m-d-Y g:i:s A') }}
        </div>
    </div>
    <div class="form-group row">
        <label for="createdat-input" class="col-3 col-form-label">Image</label>
        <div class="col-9">
            <img class="form-control" id="img" style="width: 150px" src="/images/gallery/thumbnail/{{ $product->image() }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="model-number-input" class="col-3 col-form-label">Model Number</label>
        <div class="col-9">
            <input class="form-control" style="text-transform: uppercase" value="<?php echo !empty($product->retail->p_model) ? strtoupper($product->retail->p_model) : '' ?>" type="text" placeholder="Enter model number" name="model" id="model" required>
        </div>
    </div>        
    <div class="form-group row">
        <label for="serial-name-input" class="col-3 col-form-label">Serial # *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty($product->p_serial) ? $product->p_serial : '' ?>" type="text" placeholder="Enter serial number" name="serial" id="serial-number-input" required>
        </div>
    </div>
    
    @if ($product->retail->heighest_serial)
    <div class="form-group row">
        <label for="heighest_serial-name-input" class="col-3 col-form-label">Heighest Serial #</label>
        <div class="col-9">
            <span class="form-control disabled"><?php echo !empty($product->retail->heighest_serial) ? $product->retail->heighest_serial : '' ?></span>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <label for="price-input" class="col-3 col-form-label">Price</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span id="price" class="form-control disabled">{{ $product->retailvalue() / 2 }}
        </div>
    </div>
    <div class="form-group row">
        <label for="retail-input" class="col-3 col-form-label">Retail Price</label>
        <div class="col-9 input-group">
            <div class="input-group-addon">$</div>
            <span id="retail" class="form-control disabled">{{$product->retailvalue()}}
        </div>
    </div>
    <div class="form-group row">
        <label for="reference-input" class="col-3 col-form-label">Quantity *</label>
        <div class="col-9">
            <input class="form-control" value="<?php echo !empty($product->p_qty) ? $product->p_qty : 0 ?>" type="text" placeholder="Enter amount on hand" name="quantity" id="quantity-input" required>
        </div>
    </div>
    <div class="form-group row">
        <label for="reference-input" class="col-3 col-form-label">Status</label>
        <div class="col-9">
            @role('superadmin')
            <select class="custom-select form-control" name="p_status" required>
                @foreach (Status() as $key => $status)
                <option <?php echo !empty($product->p_status) && $product->p_status==$key ? 'selected' : '' ?> value="{{ $key }}">{{ $status }}</option>
                @endforeach
            </select>

            @else
            @if ($product->p_status == 0)
            <span class="form-control" style="color: green">Available</span>
            @else
            <span class="form-control" style="color: red"><?= $product->p_status<3 ? "Reserved - " : ''?> {{ Status()[$product->p_status] }}
            @endif
            @endrole
        </div>
    </div>    
    
    <div class="form-group row">
        <label for="heighest_serial-name-input" class="col-3 col-form-label">Dimensions</label>
        <div class="col-9">
            <span class="form-control disabled">
            @if ($product->p_model != "MISC")
                
                    @if ($product->p_model=='SPL')
                        {{ "16X16X16" }} - {{ "32.5LBS" }}
                    @elseif ($product->p_model=='FRL')
                        {{ "36X31X3" }} - {{ "13LBS" }}
                    @elseif ($product->p_model=='TDS')
                        {{ "14X14X14" }} - {{ "6LBS" }}
                    @elseif ($product->p_model=='CBS')
                        {{ "14X14X14" }} - {{ "14LBS" }}
                    @elseif ($product->p_model=='SKS')
                        {{ "14X14X14" }} - {{ "15LBS" }}
                    @elseif ($product->p_model=='SPS')
                        {{ "14X14X14" }} - {{ "20LBS" }}
                    @elseif ($product->p_model=='KGL' || $product->p_model=='QNL')
                        {{ "24X18X12" }} - {{ "32LBS" }}
                    @elseif ($product->p_model=='CBL' || $product->p_model=='CBL-HA' || $product->p_model=='CBL-GR' || $product->p_model=='CBL-GA')
                        {{ "16X16X16" }} - {{ "45LBS" }}
                    @elseif ($product->p_model=='SKL')
                        {{ "16X16X16" }} - {{ "33LBS" }}
                    @elseif ($product->p_model=='HGL')
                        {{ "20X14X12" }} - {{ "35LBS" }}
                    @elseif ($product->p_model=='HGS')
                        {{ "16X10X10" }} - {{ "13LBS" }}
                    @else
                        N/A
                    @endif
                @endif
            </span>
        </div>
    </div>

    <div class="form-group row">
        <label for="comments-input" class="col-3 col-form-label">Comments</label>
        <div class="col-9">
            <textarea rows="6" name="comments" id="comments-input" class="form-control">{{ $product->comments }}</textarea>
        </div>    
    </div>

    @if (count($product->orders)>0 && $product->id != 1)
    <hr>
    <h4>Previous Orders</h4>
    <hr/>
    <div class="table-responsive">
    <table id="orders" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Invoice Id</th>
                <th>Customer</th>
                <th>Date Sold</th>
                <th>Method</th>
                <th>Serial #</th>
                <th>Sold Amount</th>
            </tr>
        </thead>
        <tbody>      
        @foreach ($product->orders as $invoice)
        <?php 
            $returned='';$product=$invoice->products->find($product->id);
            if (count($invoice->returns)) {
                $product_returned = $invoice->returns()->where('product_id',$product->id)->count(); 
                if ($product_returned) {
                    $returned = '-';
                }
            }
        ?>
        <tr style="<?= $returned=='-' ? "background: #ffecec" : "" ?>" >
            <td><a href="/admin/orders/{{ $product->pivot->order_id }}">{{ $product->pivot->order_id }}</a></td>
            <td>{{ $invoice->customers->first()->company }}</td>
            <td>{{ $invoice->created_at->format('m/d/Y')}}</td>
            <td>{{ $invoice->method }}</td>
            <td>{{ $product->pivot->serial }}</td>
            <td class="text-right"><?= $returned ?>${{ number_format($product->pivot->price,2) }}</td>   
        </tr>
        @endforeach
    </tbody>
    </table>
    </div>
    @endif
    
    <a href="{{ URL::to('admin/products/'.$product->id.'/print') }}" class="btn btn-primary">Print Barcode</a>
    @role('superadmin')
    <button type="submit" class="btn btn-primary">Update</button>
    @endrole

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
        
        $('#model').blur (function() {
            val = $('.autocomplete-selected').attr('data-index');
            findByModel({value: $('#model').data('autocomplete').suggestions[val].value, data: $('#model').data('autocomplete').suggestions[val].data});
        })

        function findByModel(suggestion) {
            $.ajax({
                type: "GET",
                sync: false,
                url: "{{route('select.found.product')}}",
                data: { 
                    _id: suggestion.data,
                    _model: suggestion.value,
                    _blade: 'product'
                },
                success: function (result) {
                    if (result) {
                        $('#img').attr('src',result['imgname']);
                        $('#heighestserial-input').text(result['heighestserial']);
                        $('#img').removeClass('height38').addClass('form-control');
                        $('#reference').text(result['reference']);
                        $('#model').val(result['model']);
                        $('#quantity-input').val(1);
                        $('#price').text(result['price']);
                        $('#retail').text(result['retail']);
                        $('#_id').val(result['id']);

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

        $('#model').devbridgeAutocomplete({
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