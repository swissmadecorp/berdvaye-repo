@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 

<link href="{{ asset('/public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('/public/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection

@section ('content')
{{  Form::model($order, array('route' => array('invoice.store', $order->id), 'id' => 'orderform')) }} 
@inject('countries','App\Libs\Countries')
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
        <th>Retail</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <tbody>
        @foreach ($order->products as $key => $product)
        
        <tr>
            <td><img style="width: 80px" src="<?=  '/public/images/thumbs/' . $product->image()  ?>"></td>
            <td>
                <input style="width: 40px" type="hidden" name="index[]" value="{{ $product->id }}">
                <input style="width: 40px" type="hidden" name="model[]" value="{{ $product->p_model }}">
                <input style="width: 40px" type="hidden" name="product_name[]" value="{{ $product->pivot->product_name }}">
                {{$product->pivot->product_name }}
            </td>
            <td><input style="width: 50px" class="form-control" type="text" name="qty[]" value="{{ $product->pivot->qty}}"></td>
            <td>
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" type="text" name="price[]" value="{{$product->pivot->price}}"></input>
                </div>
            </td>
            <td><input style="width: 80px" class="form-control" autocomplete="off" type="text" value="<?= old('serial['.$product->id.']') ?>" name="serial[]" ></td>
            <td>{{$product->retailvalue()}}</td>
            <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
            <td style="text-align: center; width: 92px">
                <button type="button" class="btn btn-danger btn-sm deleteitem" aria-label="Left Align">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
        @endforeach 
        <tr>
            <td><input type="hidden" name="product_id[]" /></td>
            <td ><input type="text" class="form-control" name="product_name[]" /> </td>
            <td><input type="text" class="form-control" style="width: 50px" name="qty[]" /></td>
            <td style="text-align: right">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" type="text" name="price[]"></input>
                </div>
            </td>
            <td style="text-align: right"><input style="width:80px" type="text" class="form-control serial-input" placeholder="SPS" name="serial[]" /></td>
            <td></td>
            <td style="text-align: center; width: 92px">
                <button type="button" class="btn btn-danger btn-sm deleteitem" aria-label="Left Align">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </td>
        </tr>
    </tbody>
</tbody>
<tfoot>
            <tr>
                <td style="text-align: right" colspan="6"><b>Discount</b></td>
                <td style="text-align: right"><input name='discount' value="0.00" id="discount-input" style="width: 100px; text-align: right;display:inline" class="form-control" /></td>
            </tr>        
            <tr>
                <td style="text-align: right" colspan="6"><b>Freight</b></td>
                <td style="text-align: right"><input name='freight' value="0.00" id="s_freight-input" style="width: 100px; text-align: right;display:inline" class="form-control" /></td>
            </tr>
        </tfoot>
</table>

<div class="form-group row">
    <div class="col-6">
        <label for="po-input" class="col-form-label">PO Number</label>
        {{Form::text('po', null, $attributes = array('name'=>'po',"id"=>"po-input","class"=>"form-control","autocomplete"=>"off"))}}
    </div>    
    <div class="col-6">
        <label for="payment-input" class="col-form-label">Payment Method</label>
        {{Form::select('payment', Payments(),$order->method,['id'=>'payment-input','class'=>'form-control'])}}
        
        <label for="payment-options_name-input" class="col-form-label">Payment Options</label>
        {{Form::select('payment_options', PaymentsOptions(),$order->payment_options,['id'=>'payment-options_name-input','class'=>'form-control'])}}
        
    </div>    
</div>

<div class="order-group billing" style="margin-right: 8px;margin-bottom: 8px;">
<div class="group-title">Billing Address</div>
<div class="p-1">
    <div class="form-group row">
        <label for="b_firstname-input" class="col-3 col-form-label">First Name</label>
        <div class="col-9 input-group">
            {{Form::text('b_firstname', null, $attributes = array('name'=>'b_firstname',"id"=>"b_firstname-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="b_lastname-input" class="col-3 col-form-label">Last Name</label>
        <div class="col-9 input-group">
            {{Form::text('b_lastname', null, $attributes = array('name'=>'b_lastname',"id"=>"b_lastname-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="b_company-input" class="col-3 col-form-label">Company</label>
        <div class="col-9 input-group">
            {{Form::text('b_company', null, $attributes = array('name'=>'b_company',"id"=>"b_company-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="b_address1-input" class="col-3 col-form-label">Address 1</label>
        <div class="col-9 input-group">
            {{Form::text('b_address1', null, $attributes = array('name'=>'b_address1',"id"=>"b_address1-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="b_address2-input" class="col-3 col-form-label">Address 2</label>
        <div class="col-9 input-group">
            {{Form::text('b_address2', null, $attributes = array('name'=>'b_address2',"id"=>"b_address2-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="b_phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9 input-group">
            {{Form::text('b_phone', null, $attributes = array('name'=>'b_phone',"id"=>"b_phone-input","class"=>"form-control"))}}
        </div>
    </div>        
    <div class="form-group row">
        <label for="b_country-input" class="col-3 col-form-label">Country</label>
        <div class="col-9 input-group">
            <?php echo $countries->getAllCountries($order->b_country) ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="b_state-input" class="col-3 col-form-label">State</label>
        <div class="col-9 input-group">
            <?php echo $countries->getAllStates($order->b_state) ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="b_city-input" class="col-3 col-form-label">City</label>
        <div class="col-9 input-group">
            {{Form::text('b_city', null, $attributes = array('name'=>'b_city',"id"=>"b_city-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="b_zip-input" class="col-3 col-form-label">Zip Code</label>
        <div class="col-9 input-group">
            {{Form::text('b_zip', null, $attributes = array('name'=>'b_zip',"id"=>"b_zip-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_email-input" class="col-3 col-form-label">Email</label>
        <div class="col-9 input-group">
            {{Form::text('email', null, $attributes = array('name'=>'email',"id"=>"email-input","class"=>"form-control"))}}
        </div>
    </div>       
</div>
</div>

<div class="order-group shipping">
<div class="group-title">Shipping Address</div>
<div class="p-1">
    <div class="form-group row">
        <label for="s_firstname-input" class="col-3 col-form-label">First Name</label>
        <div class="col-9 input-group">
            {{Form::text('s_firstname', null, $attributes = array('name'=>'s_firstname',"id"=>"s_firstname-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_lastname-input" class="col-3 col-form-label">Last Name</label>
        <div class="col-9 input-group">
            {{Form::text('s_lastname', null, $attributes = array('name'=>'s_lastname',"id"=>"s_lastname-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_company-input" class="col-3 col-form-label">Company</label>
        <div class="col-9 input-group">
            {{Form::text('s_company', null, $attributes = array('name'=>'s_company',"id"=>"s_company-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_address1-input" class="col-3 col-form-label">Address 1</label>
        <div class="col-9 input-group">
        {{Form::text('s_address1', null, $attributes = array('name'=>'s_address1',"id"=>"s_address1-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_address2-input" class="col-3 col-form-label">Address 2</label>
        <div class="col-9 input-group">
            {{Form::text('s_address2', null, $attributes = array('name'=>'s_address2',"id"=>"s_address2-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_phone-input" class="col-3 col-form-label">Phone</label>
        <div class="col-9 input-group">
            {{Form::text('s_phone', null, $attributes = array('name'=>'s_phone',"id"=>"s_phone-input","class"=>"form-control"))}}
        </div>
    </div>            
    <div class="form-group row"> 
        <label for="b_country-input" class="col-3 col-form-label">Country</label>
        <div class="col-9 input-group">
        <?php echo $countries->getAllCountries($order->s_country,'s_') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="s_state-input" class="col-3 col-form-label">State</label>
        <div class="col-9 input-group">
            <?php echo $countries->getAllStates($order->s_state,'s_') ?>
        </div>
    </div>
    <div class="form-group row">
        <label for="s_city-input" class="col-3 col-form-label">City</label>
        <div class="col-9 input-group">
            {{Form::text('s_city', null, $attributes = array('name'=>'s_city',"id"=>"s_city-input","class"=>"form-control"))}}
        </div>
    </div>
    <div class="form-group row">
        <label for="s_zip-input" class="col-3 col-form-label">Zip Code</label>
        <div class="col-9 input-group">
            {{Form::text('s_zip', null, $attributes = array('name'=>'s_zip',"id"=>"s_zip-input","class"=>"form-control"))}}
        </div>
    </div> 
</div>
</div>

<div class="clearfix"></div>

<div class="form-group row">
<div class="col-12">
    <label for="comments_input" class="col-form-label">Comments</label>
    <textarea rows="5" style="width: 100%" id="comments_input" name="comments"></textarea>
</div>
</div>

<div class="clearfix"></div>
<button type="submit" class="btn btn-primary create">Create Order</button>

@include('admin.errors')
{{ Form::close() }}

<div id="product-container"></div>
<div id="search-customer"></div>
@endsection

@section ('footer')
<script src="{{ asset('/fancybox/jquery.fancybox.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
<script src="{{ asset('/js/jquery.autocomplete.min.js') }}"></script>
@endsection      

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'create';
    var fromShipping=false;

    $(document).ready( function() {
        $( "#datepicker" ).datepicker();
        
        function fillInData(data,exclude) {
            $('#customer_id').val(data.id);
            if (data.credit) {
                amount = data.credit.amount;
                $('#creditamount').text('$'+amount.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#creditamount').attr('data-amount',amount);
                $('#creditamount').parent().parent().show();
            } else {
                $('#creditamount').text('');
                $('#creditamount').attr('data-amount','');
                $('#creditamount').parent().parent().hide();
            }

            for (name in data) {
                if (name != 'id') {
                    if (data[name]) {
                        if (exclude == 'b_firstname-input')
                            $('#b_'+ name +'-input').val(data[name]);
                        if (name == 'country')
                            $('#b_'+ name +'-input').val(data[name]).change();
                        if (name =='state') {
                            $('#h_state').val(data[name]);
                        }
                        
                        $('#s_'+ name +'-input').val(data[name]);
                    } else {
                        if (exclude == 'b_firstname-input')
                            $('#b_'+ name +'-input').val('');
                        
                        $('#s_'+ name +'-input').val('');
                    }
                }
            }
        }

        
        var getPath = "{{action('CustomersController@ajaxgetCustomerL')}}";
        var mainPath = "{{action('CustomersController@ajaxCustomerL')}}";

        $('#s_firstname-input').dropdown({
            // default is fullname so no need to specify
            parent: '.sfirstname',
            getPath: getPath,
            mainPath: mainPath,
            success: function(data) {
                fromShipping=true
                $('#s_firstname-input').val(data['firstname']);
                fillInData(data)
            }
        });
        
        $('#s_company-input').dropdown({
            parent: '.scompany',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'company',
            success: function(data) {
                fromShipping=true
                $('#s_company-input').val(data['company']);
                fillInData(data)
            }
        });

        $('#s_lastname-input').dropdown({
            parent: '.slastname',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'lastname',
            success: function(data) {
                fromShipping=true
                $('#s_lastname-input').val(data['lastname']);
                fillInData(data)
            }
        });

        $('#b_firstname-input').dropdown({
            // default is fullname so no need to specify
            parent: '.firstname',
            getPath: getPath,
            mainPath: mainPath,
            success: function(data) {
                fillInData(data)
            }
        });
        
        $('#b_company-input').dropdown({
            parent: '.company',
            getPath: getPath,
            mainPath: mainPath,
            searchBy: 'company',
            success: function(data) {
                fillInData(data)
            }
        });

        $('#b_lastname-input').dropdown({
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
                url: "{{action('ProductsController@ajaxProducts')}}",
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

        $('.billing input,.billing select').on('input propertychange', function(e) {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
                
        })

        $('#b_country-input,#s_country-input').change( function() {
            _this = $(this);
            $.get("{{ action('CountriesController@getStateFromCountry')}}",{id: $(_this).val()})
            .done (function (data) {
                if ($(_this).attr('id') == 'b_country-input') {
                    $('#b_state-input').html(data);
                    $('#s_state-input').html(data);
                }
            })
        })

        @include ('admin.countrystate',['billExt'=>'b_', 'shipExt'=>'s_'])

        $(document).on('focus', '.serial-input', function () { 
            _this = $(this); status = '';
            if($(this).devbridgeAutocomplete() === undefined ){
                $('.serial-input').devbridgeAutocomplete({
                    serviceUrl: "{{route('find.product')}}",
                    showNoSuggestionNotice : true,
                    minChars: 1,
                    width: 240,
                    zIndex: 900,
                    onSelect: function (suggestion) {
                        $.ajax({
                            type: "GET",
                            url: "{{route('select.found.product')}}",
                            data: { 
                                _id: suggestion.data,
                            },
                            success: function (result) {
                                if (result) {
                                    status = result.status;
                                    if (result.status) {
                                        $(_this).val('')
                                        $.alert(result.statusText)
                                    } else {
                                        var tr = $(_this).parents('tr');

                                        tr.find('td:eq(0)').children('input').val(result["id"])
                                        img = tr.find('td:eq(0)').find('img');

                                        if (img.length > 0)
                                            img.remove('img');
                                        
                                        tr.find('td:eq(0)').append(result['image']);
                                        tr.find('td:eq(1)').children('input').val(result['reference']+' ('+result['model']+')' );
                                        tr.find('td:eq(2)').children('input').val(result['qty']);
                                        tr.find('td:eq(3)').find('input').val(result['price']);
                                        tr.find('td:eq(4)').children('input').val(result['serial']);
                                        tr.find('td:eq(5)').text(result['retail']);
                                        if (tr.find('td:eq(6)').children().length == 0)
                                            tr.find('td:eq(6)').append('<a class="btn btn-danger delete nonsubmit deleteitem"><i class="fa fa-trash" aria-hidden="true"></i>');
                                    }
                                }
                            }
                        })

                        //if (result.status) {
                            if ($(this).parents('tr').index() == $('.order-products tr').length-4 && $(this).val() != '') {
                                $.ajax({
                                    type: "GET",
                                    url: "{{route('new.invoice.row')}}",
                                    data: { _blade: 'invoice_new' },
                                    success: function (result) {
                                        if ($('.order-products tr').eq($('.order-products tr').length-3).find('td:nth-child(2)').find('input').val()!='') {
                                            $('.order-products tr').eq($('.order-products tr').length-3).after(result);
                                            setTimeout(function(){ 
                                                $('.order-products tr').eq($('.order-products tr').length-3).find('td:eq(4)').find('input').focus()
                                            }, 300);
                                        }
                                    }
                                })
                            }
                        //}
                    }
                });
            }
        });
        
        $('.billing input,.billing select').on('input propertychange', function(e) {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
        })

        $(document).on('click','.deleteitem',function() {
            $(this).parents('tr').remove();
        })

        $('.billing input').on('input propertychange', function() {
            id=$(this).attr('id');
            if (!$('#s'+id.substr(1)).val())
                $('#s'+id.substr(1)).val($(this).val());
        })

        $('.create').click (function (e) {
            if (!$('#customer_id').val() && $('#b_company-input').val().length > 0) {
                e.preventDefault();
                if (confirm("Customer doesn't exit. Would you like to create it?")) {
                    $.ajax({
                        type: "GET",
                        url: "{{action('OrdersController@ajaxSaveCustomer')}}",
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