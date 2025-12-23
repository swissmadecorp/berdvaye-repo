@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
@endsection

@section ('content')

<form method="POST" action="{{route('estimates.store')}}" id="estimateForm">
@csrf
    <p>Order Date:  <input type="text" name="created_at" value="<?php echo !empty(old('created_at')) ? old('created_at') : '' ?>" style="width: 40%" placeholder="Leave blank for today's date" id="datepicker"></p>

    <table class="table estimate-products">
        <thead>
            <tr>
            <th>Image</th>
                <th>Product Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Reference</th>
                <th>Retail</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td ><input type="text" class="form-control" name="product_name[]" placeholder="Use Serial field" disabled/> </td>
                <td><input type="text" class="form-control" style="width: 50px" name="qty[]" /></td>
                <td style="text-align: right">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="price[]"></input>
                    </div>
                </td>
                <td style="text-align: right"><input style="width:80px" type="text" placeholder="SPS" class="form-control serial-input" name="serial[]" /></td>
                <td style="text-align: right; width: 170px">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="retail[]"></input>
                    </div>
                </td>
                <td style="width: 30px;text-align: center">
                    <a class="btn btn-danger deleteitem nonsubmit"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
    
    @include('admin.errors')
    <div class="form-group row">
        <div class="col-6">
            <label for="po-input" class="col-form-label">PO Number</label>
            <input class="form-control" autocomplete="off" value="<?php echo !empty(old('po')) ? old('po') : '' ?>" type="text" name="po" id="po-input">
        </div>    
        <div class="col-6">
            <label for="payment-input" class="col-form-label">Payment Method</label>
            <select class="form-control" name="method">
                @foreach (Payments() as $value => $payment)
                    <option value="{{ $payment }}" <?php echo !empty(old('method')) && old('method')==$value ? 'selected' : '' ?>>{{ $payment }}</option>
                @endforeach
            </select>

            <label for="payment-options-name-input" class="col-form-label">Payment Options</label>
            <select class="form-control" id="payment-options-name-input" name="payment_options">
                @foreach (PaymentsOptions() as $value => $payment_option)
                    <option value="{{ $value }}" <?php echo !empty(old('payment_options')) && old('payment_options')==$value ? 'selected' : '' ?>>{{ $payment_option }}</option>
                @endforeach
            </select>
        </div>    
    </div>
    
    <div class="order-group billing" style="margin-right: 8px;margin-bottom: 8px;">
        <div class="group-title">Billing Address</div>
        <div class="p-1">
            <div class="form-group row firstname">
                <label for="b_firstname-input" class="col-3 col-form-label">First Name</label>
                <div class="col-9">
                    <input class="typeahead form-control" autocomplete="off" value="<?php echo !empty(old('b_firstname')) ? old('b_firstname') : '' ?>" type="text" name="b_firstname" id="b_firstname-input">
                </div>
            </div>
            <div class="form-group row lastname">
                <label for="b_lastname-input" class="col-3 col-form-label">Last Name</label>
                <div class="col-9">
                    <input class="form-control" autocomplete="off" value="<?php echo !empty(old('b_lastname')) ? old('b_lastname') : '' ?>" type="text" name="b_lastname" id="b_lastname-input">
                </div>
            </div>
            <div class="form-group row company">
                <label for="b_company-input" class="col-3 col-form-label">Company</label>
                <div class="col-9 input-group">
                    <input class="form-control" autocomplete="off" value="<?php echo !empty(old('b_company')) ? old('b_company') : '' ?>" type="text" name="b_company" id="b_company-input" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="b_address1-input" class="col-3 col-form-label">Address 1</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('b_address1')) ? old('b_address1') : '' ?>" type="text" name="b_address1" id="b_address1-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="b_address2-input" class="col-3 col-form-label">Address 2</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('b_address2')) ? old('b_address2') : '' ?>" type="text"  name="b_address2" id="b_address2-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="b_phone-input" class="col-3 col-form-label">Phone</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('b_phone')) ? old('b_phone') : '' ?>" type="text"  name="b_phone" id="b_phone-input">
                </div>
            </div>                
            <div class="form-group row">
                <label for="b_country-input" class="col-3 col-form-label">Country</label>
                <div class="col-9">
                    @inject('countries','App\Libs\Countries')
                    <?php echo $countries->getAllCountries() ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="b_state-input" class="col-3 col-form-label">State</label>
                <div class="col-9">
                    <?php echo $countries->getAllStates() ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="b_city-input" class="col-3 col-form-label">City</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('b_city')) ? old('b_city') : '' ?>" type="text" name="b_city" id="b_city-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="b_zip-input" class="col-3 col-form-label">Zip Code</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('b_zip')) ? old('b_zip') : '' ?>" type="text"  name="b_zip" id="b_zip-input">
                </div>
            </div>
        </div>
    </div>

    <div class="order-group shipping">
        <div class="group-title">Shipping Address</div>
        <div class="p-1">
            <div class="form-group row">
                <label for="s_firstname-input" class="col-3 col-form-label">First Name</label>
                <div class="col-9">
                    <input class="typeahead form-control" value="<?php echo !empty(old('s_firstname')) ? old('s_firstname') : '' ?>" type="text" name="s_firstname" id="s_firstname-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_lastname-input" class="col-3 col-form-label">Last Name</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('s_lastname')) ? old('s_lastname') : '' ?>" type="text" name="s_lastname" id="s_lastname-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_company-input" class="col-3 col-form-label">Company</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('s_company')) ? old('s_company') : '' ?>" type="text" name="s_company" id="s_company-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_address-input" class="col-3 col-form-label">Address 1</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('s_address')) ? old('s_address1') : '' ?>" type="text" name="s_address1" id="s_address1-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_address2-input" class="col-3 col-form-label">Address 2</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('s_address2')) ? old('s_address2') : '' ?>" type="text"  name="s_address2" id="s_address2-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_phone-input" class="col-3 col-form-label">Phone</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('s_phone')) ? old('s_phone') : '' ?>" type="text"  name="s_phone" id="s_phone-input">
                </div>
            </div>                
            <div class="form-group row">
                <label for="s_country-input" class="col-3 col-form-label">Country</label>
                <div class="col-9">
                    <?php echo $countries->getAllCountries(0,'s_') ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="s_state-input" class="col-3 col-form-label">State</label>
                <div class="col-9">
                    <?php echo $countries->getAllStates(0,'s_') ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="s_city-input" class="col-3 col-form-label">City</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('s_city')) ? old('s_city') : '' ?>" type="text" name="s_city" id="s_city-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_zip-input" class="col-3 col-form-label">Zip Code</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('s_zip')) ? old('s_zip') : '' ?>" type="text"  name="s_zip" id="s_zip-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="email-input" class="col-3 col-form-label">Email</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('email')) ? old('email') : '' ?>" type="text"  name="email" id="email-input">
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="form-group row">
        <div class="col-12">
            <label for="comments-input" class="col-form-label">Comments</label>
            <textarea rows="5" style="width: 100%" id="comments-input" name="comments"></textarea>
        </div>
    </div>

    <button type="submit" class="btn btn-primary create">Create Order</button>

</form>

<div id="product-container"></div>
<div id="search-customer"></div>
@endsection

@section ('footer')
<script src="/js/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'create-estimator';
    
    $(document).ready( function() {
        $( "#datepicker" ).datepicker();
        
        function fillInData(data,exclude) {
            $('#customer_id').val(data.id);
            
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

        $('#b_zip-input').blur(function() {
            getAddressFromZip(this);
        })

        $('#s_zip-input').blur(function() {
            getAddressFromZip(this,'s');
        })

        function getAddressFromZip(zip,location) {
            $.get("{{route('address.from.zip')}}",{zip: $(zip).val()},function(data) {
                if (data.city) {
                    if (location == 's') {
                        $('#s_city-input').val(data.city)
                        $('#s_state-input').val(data.state);    
                    } else {
                        $('#b_city-input').val(data.city)
                        $('#s_city-input').val(data.city)
                        $('#b_state-input').val(data.state);
                        $('#s_state-input').val(data.state);
                        if ($('#b_zip-input').val()=='') {
                            $('#b_zip-input').val($(zip).val())
                        };
                    }
                }
            })
        }


        var getPath = "{{route('ajax.get.customer')}}";
        var mainPath = "{{route('ajax.customer')}}";

        $(document).on('focus', '.serial-input', function () { 
            _this = $(this);
            if($(this).devbridgeAutocomplete() === undefined ){
                $('.serial-input').devbridgeAutocomplete({
                    serviceUrl: "{{route('get.retailproducts')}}",
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
                                    var tr = $(_this).parents('tr');

                                    //tr.find('td:eq(0)').children('input').val(result["id"])
                                    img = tr.find('td:eq(0)').find('img');

                                    if (img.length > 0)
                                        img.remove('img');
                                    
                                    tr.find('td:eq(0)').append(result['image']);
                                    tr.find('td:eq(1)').children('input').val(result['reference']+' ('+result['model']+')' );
                                    tr.find('td:eq(1)').children('input').attr('disabled',false)
                                    tr.find('td:eq(2)').children('input').val(result['qty']);
                                    tr.find('td:eq(3)').find('input').val(result['price']);
                                    tr.find('td:eq(4)').children('input').val(result['model']);
                                    tr.find('td:eq(5)').find('input').val(result['retail']);
                                    if (tr.find('td:eq(6)').children().length == 0)
                                        tr.find('td:eq(6)').append('<a class="btn btn-danger delete nonsubmit deleteitem"><i class="fa fa-trash" aria-hidden="true"></i>');
                                }
                            }
                        })

                        if ($(this).parents('tr').index() == $('.estimate-products tr').length-2 && $(this).val() != '') {
                            $.ajax({
                                type: "GET",
                                url: "{{route('new.invoice.row')}}",
                                data: { _blade: blade },
                                success: function (result) {
                                    $('.estimate-products tr').eq($('.estimate-products tr').length-1).after(result);
                                    $('.estimate-products tr').eq($('.estimate-products tr').length-1).find('td:eq(0)').find('input').focus()
                                }
                            })
                        }
                    }
                });
            }
        });

        $('input.typeahead,input.typeahead1').devbridgeAutocomplete({
            serviceUrl: mainPath,
            showNoSuggestionNotice : true,
            minChars: 3,
            zIndex: 900,
            orientation: 'auto',

            onSelect: function (suggestion) {
                //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
                var el = this.id;
                $.ajax({
                    type: "GET",
                    url: getPath,
                    data: { 
                        _token: csrf_token,
                        _id: suggestion.data,
                        _searchBy: $(this).attr('id')
                    },
                    success: function (result) {
                        if (result) {
                            fillInData(result,el);
                        }
                    }
                })
            }
        });

        $('#b_zip-input').blur(function() {
            getAddressFromZip(this);
        })

        $('#s_zip-input').blur(function() {
            getAddressFromZip(this,'s');
        })

        function getAddressFromZip(zip,location) {
            $.get("{{route('address.from.zip')}}",{zip: $(zip).val()},function(data) {
                if (data.city) {
                    if (location == 's') {
                        $('#s_city-input').val(data.city)
                        $('#s_state-input').val(data.state);    
                    } else {
                        $('#b_city-input').val(data.city)
                        $('#s_city-input').val(data.city)
                        $('#b_state-input').val(data.state);
                        $('#s_state-input').val(data.state);
                        if ($('#b_zip-input').val()=='') {
                            $('#b_zip-input').val($(zip).val())
                        };
                    }
                }
            })
        }

        $('#b_country-input,#s_country-input').change( function() {
            _this = $(this);
            $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
            .done (function (data) {
                if ($(_this).attr('id') == 'b_country-input') {
                    $('#b_state-input').html(data);
                    $('#s_state-input').html(data);
                }
            })
        })

        @include ('admin.countrystate',['billExt'=>'b_', 'shipExt'=>'s_'])

        $(document).on('click','.deleteitem',function() {
            $(this).parents('tr').remove();
        })

        $('.billing input,.billing select').on('input propertychange', function(e) {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
        })

    })

</script>
@endsection