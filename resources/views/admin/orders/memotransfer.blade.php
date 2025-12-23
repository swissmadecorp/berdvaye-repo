@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 

<link href="{{ asset('/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection

@section ('content')
<form method="POST" action="{{route('memotransfer.update',[$order->id])}}" accept-charset="UTF-8" id="orderFrom">
@csrf
@method('PATCH')
@include('admin.errors')
<input type="hidden" name="customer_id" id="customer_id" value="{{ $order->customers()->first()->id }}">
<input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">

<p>Order Date:  <input type="text" name="created_at" value="<?php echo !empty(old('created_at')) ? old('created_at') : '' ?>" style="width: 40%" placeholder="Leave blank for today's date" id="datepicker"></p>

<table class="table order-products">
<thead>
        <tr>
        <th>Image</th>
        <th>Product Name</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Serial</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php  $returns = array() ?>
        @foreach($order->returns->all() as $return)
            @if ($return->pivot->order_id == $order->id)
                <?php $returns[] = $return->pivot->product_id ?>
            @endif
        @endforeach

        @foreach ($order->products as $product)
        @if (!in_array($product->id, $returns))

        <tr>
            <td><img style="width: 80px" src="<?= $product->image() ? '/images/thumbs/'.$product->image() : '/images/no-image.jpg' ?>" ></td>
            <td>
                <input style="width: 40px" type="hidden" name="model[<?= $product->id ?>]" value="{{ $product->pivot->product_name }}">
                <?=  $product->pivot->product_name ?>
            </td>
            <td><input style="width: 50px" type="text" name="qty[<?= $product->id?>]" value="{{ $product->pivot->qty}}"></td>
            <td>$<input style="width: 80px" type="text" name="price[<?= $product->id?>]" value="<?= $product->pivot->price ?>"></td>
            <td><input style="width: 80px" autocomplete="off" type="text" value="<?= $product->p_serial ?>" name="serial[<?= $product->id?>]" ></td>
            <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
            <td>
                <button type="button" class="btn btn-danger deleteitem" aria-label="Left Align">
                    <i class="fas fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
        @endif
        @endforeach
        <tr>
            <td>
                <div class="form-group row">
                <button style="padding: 3px 5px" class="btn btn-primary additem" data-toggle="tooltip" data-placement="right" title="Please add at least one item to the order."  aria-label="Left Align">
                  <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
                </div>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align: right" colspan="5"><b>Discount</b></td>
            <td style="text-align: right"><input name='discount' value="0.00" id="discount-input" style="width: 100px; text-align: right;display:inline" class="form-control" /></td>
        </tr>        
        <tr>
            <td style="text-align: right" colspan="5"><b>Freight</b></td>
            <td style="text-align: right"><input name='freight' value="{{$order->freight}}" id="s-freight-input" style="width: 100px; text-align: right;display:inline" class="form-control" /></td>
        </tr>
    </tfoot>    
</table>

<div class="form-group row">
    <div class="col-6">
        <label for="po-input" class="col-form-label">PO Number</label>
        <input name="po" id="po-input" class="form-control" autocomplete="off" type="text" value="{{$order->po}}">
    </div>    
    <div class="col-6">
    <label for="payment-input" class="col-form-label">Payment Method</label>
        <input type="text" autocomplete='off' name='method' id="payment-input" readonly class="form-control" value="Invoice">
        
        <label for="payment-options-name-input" class="col-form-label">Payment Options</label>
        @foreach (Payments() as $value => $payment)
            <option value="{{ $payment }}" <?php echo !empty($order->method) && $order->method==$payment ? 'selected' : '' ?>>{{ $payment }}</option>
        @endforeach
    </div>    
</div>

<div class="order-group billing" style="margin-right: 8px;margin-bottom: 8px;">
<div class="group-title">Billing Address</div>
<div class="p-1">
<div class="form-group row">
        <label for="b-firstname-input" class="col-3 col-form-label">First Name</label>
        <div class="col-9 input-group">
            <input name="b_firstname" id="b-firstname-input" class="form-control" type="text" value="{{$order->b_firstname}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-lastname-input" class="col-3 col-form-label">Last Name</label>
        <div class="col-9 input-group">
            <input name="b_lastname" id="b-lastname-input" class="form-control" type="text" value="{{$order->b_lastname}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-company-input" class="col-3 col-form-label">Company</label>
        <div class="col-9 input-group">
            <input name="b_company" id="b-company-input" class="form-control" type="text" value="{{$order->b_company}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-address1-input" class="col-3 col-form-label">Address 1</label>
        <div class="col-9 input-group">
            <input name="b_address1" id="b-address1-input" class="form-control" type="text" value="{{$order->b_address1}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-address2-input" class="col-3 col-form-label">Address 2</label>
        <div class="col-9 input-group">
            <input name="b_address2" id="b-address2-input" class="form-control" type="text" value="{{$order->b_address2}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9 input-group">
            <input name="b_phone" id="b-phone-input" class="form-control" type="text" value="{{$order->b_phone}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-country-input" class="col-3 col-form-label">Country</label>
        <div class="col-9 input-group">
            @inject('countries','App\Libs\Countries')
            <?php echo $countries->getAllCountries() ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="b-state-input" class="col-3 col-form-label">State</label>
        <div class="col-9 input-group">
            <?php echo $countries->getAllStates($order->b_state) ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="b-city-input" class="col-3 col-form-label">City</label>
        <div class="col-9 input-group">
            <input name="b_city" id="b-city-input" class="form-control" type="text" value="{{$order->s_city}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-zip-input" class="col-3 col-form-label">Zip Code</label>
        <div class="col-9 input-group">
            <input name="b_zip" id="b-zip-input" class="form-control" type="text" value="{{$order->s_zip}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-email-input" class="col-3 col-form-label">Email</label>
        <div class="col-9 input-group">
            <input name="email" id="email-input" class="form-control" type="text" value="{{$order->email}}">
        </div>
    </div>
</div>
</div>

<div class="order-group shipping">
<div class="group-title">Shipping Address</div>
<div class="p-1">
<div class="form-group row">
        <label for="s-firstname-input" class="col-3 col-form-label">First Name</label>
        <div class="col-9 input-group">
            <input name="s_firstname" id="s-firstname-input" class="form-control" type="text" value="{{$order->s_firstname}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="s-lastname-input" class="col-3 col-form-label">Last Name</label>
        <div class="col-9 input-group">
            <input name="s_lastname" id="s-lastname-input" class="form-control" type="text" value="{{$order->s_lastname}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="s-company-input" class="col-3 col-form-label">Company</label>
        <div class="col-9 input-group">
            <input name="s_company" id="s-company-input" class="form-control" type="text" value="{{$order->s_company}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="s-address1-input" class="col-3 col-form-label">Address 1</label>
        <div class="col-9 input-group">
        <input name="s_address1" id="s-address1-input" class="form-control" type="text" value="{{$order->s_address1}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="s-address2-input" class="col-3 col-form-label">Address 2</label>
        <div class="col-9 input-group">
            <input name="s_address2" id="s-address2-input" class="form-control" type="text" value="{{$order->s_address2}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="s-phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9 input-group">
            <input name="s_phone" id="s-phone-input" class="form-control" type="text" value="{{$order->s_phone}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="b-country-input" class="col-3 col-form-label">Country</label>
        <div class="col-9 input-group">
        <?php echo $countries->getAllCountries(0,'s_') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="s-state-input" class="col-3 col-form-label">State</label>
        <div class="col-9 input-group">
            <?php echo $countries->getAllStates($order->s_state,'s_') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="s-city-input" class="col-3 col-form-label">City</label>
        <div class="col-9 input-group">
            <input name="s_city" id="s-city-input" class="form-control" type="text" value="{{$order->s_city}}">
        </div>
    </div>
    <div class="form-group row">
        <label for="s-zip-input" class="col-3 col-form-label">Zip Code</label>
        <div class="col-9 input-group">
            <input name="s_zip" id="s-zip-input" class="form-control" type="text" value="{{$order->s_zip}}">
        </div>
    </div>
</div>
</div>

<div class="clearfix"></div>

<div class="form-group row">
<div class="col-12">
    <label for="comments-input" class="col-form-label">Comments</label>
    <textarea rows="5" style="width: 100%" id="comments-input" name="comments">{{ $order->comments }}</textarea>
</div>
</div>

<div class="clearfix"></div>
<button type="submit" class="btn btn-primary create">Create Order</button>

@include('admin.errors')
</form>

<div id="product-container"></div>
<div id="search-customer"></div>
@endsection

@section ('footer')
<script src="{{ asset('/fancybox/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('/js/general.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection      

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'memotransfer';
    var fromShipping=false;

    $(document).ready( function() {
        $( "#datepicker" ).datepicker();
        
        function fillInData(data) {
            $('#customer_id').val(data.id);
            for (name in data) {
                if (name != 'id') {
                    $('#orderForm input').each ( function () {
                        
                            $('#b-'+ name +'-input').val(data[name]);
                        
                        
                            $('#s-'+ name +'-input').val(data[name]);
                        
                        return false;
                    })   
                }
            }
        }

        var getPath = "{{route('ajax.get.customer')}}";
        var mainPath = "{{route('ajax.customer')}}";

        $('#s-firstname-input').dropdown({
            // default is fullname so no need to specify
            parent: '.sfirstname',
            getPath: getPath,
            mainPath: mainPath,
            success: function(data) {
                fromShipping=true
                $('#s-firstname-input').val(data['firstname']);
                fillInData(data)
            }
        });
        
        $('#s-company-input').dropdown({
            parent: '.scompany',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'company',
            success: function(data) {
                fromShipping=true
                $('#s-company-input').val(data['company']);
                fillInData(data)
            }
        });

        $('#s-lastname-input').dropdown({
            parent: '.slastname',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'lastname',
            success: function(data) {
                fromShipping=true
                $('#s-lastname-input').val(data['lastname']);
                fillInData(data)
            }
        });

        $('#b-firstname-input').dropdown({
            // default is fullname so no need to specify
            parent: '.firstname',
            getPath: getPath,
            mainPath: mainPath,
            success: function(data) {
                fillInData(data)
            }
        });
        
        $('#b-company-input').dropdown({
            parent: '.company',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'company',
            success: function(data) {
                fillInData(data)
            }
        });

        $('#b-lastname-input').dropdown({
            parent: '.lastname',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'lastname',
            success: function(data) {
                fillInData(data)
            }
        });

        $(".additem").on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{route('ajax.products')}}",
                data: { 
                    _token: "{{csrf_token()}}",
                },
                success: function (result) {
                    $('#product-container').html(result.content+result.jquery);

                    $.fancybox.open({
                        src: "#product-container",
                        type: 'inline',
                        width: 980,
                    });
                }
            })
        });

        $('.fancybox-close-small').click( function () {
            $.fn.fancybox.close()
        })

        $('#b_country-input,#s_country-input').change( function() {
            _this = $(this);
            $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
            .done (function (data) {
                if ($(_this).attr('id') == 'b_country-input')
                    $('#b_state-input').html(data);
                else $('#s_state-input').html(data);
            })
        })

  
        @include ('admin.countrystate',['billExt'=>'b_', 'shipExt'=>'b_'])

        $(document).on('click','.deleteitem',function() {
            $(this).parents('tr').remove();
        })

        $('.billing input').on('input propertychange', function() {
            id=$(this).attr('id');
            if (!$('#s'+id.substr(1)).val())
                $('#s'+id.substr(1)).val($(this).val());
        })

        paymentOptions('Invoice');

        $('.create').click (function (e) {
            if (!$('#customer_id').val() && $('#b-company-input').val().length > 0) {
                e.preventDefault();
                if (confirm("Customer doesn't exit. Would you like to create it?")) {
                    $.ajax({
                        type: "GET",
                        url: "{{route('ajax.save.customer')}}",
                        data: { 
                            _token: csrf_token,
                            _form: $('#orderForm').serialize()
                        },
                        success: function (result) {
                            $('#customer_id').val(result);
                            $('#orderForm').submit();
                        }
                    })
                }
            } 

            if ($('.order-products tr').length == 2) {
                $('html, body').animate({scrollTop : 0},600);
                $('.additem').tooltip('show');
                e.preventDefault();
            }

        })
    })

</script>
@endsection