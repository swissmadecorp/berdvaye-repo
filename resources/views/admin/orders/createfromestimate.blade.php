@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/>
<link href="{{ asset('/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection

@section ('content')
@inject('countries','App\Libs\Countries')
<form method="POST" action="{{route('orders.store', $estimate->id)}}" id="estimateFrom">
    @csrf

    @include('admin.errors')
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $estimate->customers()->first()->id }}">
    <input type="hidden" name="order_id" id="order_id" value="{{ $estimate->id }}">

    <p>Order Date:  <input type="text" name="created_at" value="<?php echo !empty(old('created_at')) ? old('created_at') : '' ?>" style="width: 40%" placeholder="Leave blank for today's date" id="datepicker"></p>

    <table class="table estimate-products">
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
            @foreach ($estimate->products as $key => $product)
            @for ($i=0; $i < $product->qty; $i++)
            @if ($product->qty > 1)
                <?php $qty = 1 ?>
            @else
                <?php $qty = $product->qty?>
            @endif
            <tr>
                <td><img style="width: 80px" src="<?=  '/images/thumbs/' . $product->retail->image_location  ?>">
                    <input type="hidden" name="img_name[]" value="{{ $product->retail->image_location}}">
                </td>
                <td>
                    <!-- <input style="width: 40px" type="hidden" name="index[]"> -->
                    <input type="hidden" name="model[]" value="{{ $product->p_model}}">
                    <input type="hidden" name="product_id[]" value="{{ $product->id}}">
                    <input type="text" name="product_name[]" class="form-control" value="{{ $product->product_name}}">
                </td>
                <td><input style="width: 50px" type="text" name="qty[]" class="form-control" value="{{ $qty}}"></td>
                <td style="text-align: right">
                    <div class="input-group">
                    <div class="input-group-addon">$</div>
                        <input style="width: 80px" type="text" name="price[]" class="form-control" value="<?= $product->price ?>">
                    </div>
                </td>
                <td><input style="width: 80px" autocomplete="off" type="text" class="form-control serial-input" value="<?= old('serial['.$product->id.']') ?>" name="serial[]" required></td>
                <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
                <td style="text-align: right;">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" value="{{$product->retail_price}}" name="retail[]" />
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm deleteitem" aria-label="Left Align">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button><br>
                    <button type="button" class="btn btn-danger btn-sm backorder" aria-label="Left Align">Backorder</button>
                </td>
            </tr>
            @endfor
            @endforeach
            <tr>
                <td></td>
                <td>
                    <input style="width: 40px" type="hidden" name="product_id[]">
                    <input style="width: 40px" type="hidden" name="model[]">
                    <input class="form-control" name="product_name[]" placeholder="Use Serial field" disabled>
                </td>
                <td><input style="width: 50px" type="text" class="form-control" name="qty[]"></td>
                <td style="text-align: right">
                    <div class="input-group">
                    <div class="input-group-addon">$</div>
                        <input style="width: 80px" type="text" name="price[]" class="form-control">
                    </div>
                </td>
                <td><input style="width: 80px" autocomplete="off" class="form-control serial-input" type="text" name="serial[]"></td>
                <!-- oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Please Enter valid a serial number')" -->
                <td style="text-align: right;">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="retail[]"></input>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm deleteitem" aria-label="Left Align">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button><br>
                    <button type="button" class="btn btn-danger btn-sm backorder" aria-label="Left Align">Backorder</button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="form-group row">
        <div class="col-6">
            <label for="po-input" class="col-form-label">PO Number</label>
            <input class="form-control" autocomplete="off" value="<?= !empty(old('po')) ?? old('po') ?>" type="text" name="po" id="po-input">
        </div>
        <div class="col-6">
            <label for="payment-input" class="col-form-label">Payment Method</label>
            <select class="form-control" name="method" id="payment-input">
            @foreach (Payments() as $value => $payment)
                <option value="{{ $payment }}" <?php echo (!empty(old('method')) && old('method')==$value ? 'selected' : $payment=='Check') ?'selected' : '' ?>>{{ $payment }}</option>
            @endforeach
            </select>

            <label for="payment-options-name-input" class="col-form-label">Payment Options</label>
            <select class="form-control" id="payment-options-name-input" name="payment_options">
            @foreach (PaymentsOptions() as $value => $payment_option)
                <option value="{{ $value }}" <?php echo (!empty(old('payment_options')) && old('payment_options')==$value ? 'selected' : $payment_option=='Net 60') ? 'selected' : '' ?>>{{ $payment_option }}</option>
            @endforeach
            </select>
        </div>
    </div>

    <div class="order-group billing" style="margin-right: 8px;margin-bottom: 8px;">
    <div class="group-title">Billing Address</div>
    <div class="p-1">
        <div class="form-group row firstname">
            <label for="b_firstname-input" class="col-3 col-form-label">First Name</label>
            <div class="col-9 input-group">
                <input autocomplete="off" id="b_firstname-input" class="typeahead form-control" name="b_firstname" type="text" value="{{$estimate->b_firstname}}">
            </div>
        </div>
        <div class="form-group row lastname">
            <label for="b_lastname-input" class="col-3 col-form-label">Last Name</label>
            <div class="col-9 input-group">
                <input autocomplete="off" id="b_lastname-input" class="typeahead form-control" name="b_lastname" type="text" value="{{$estimate->b_lastname}}">
            </div>
        </div>
        <div class="form-group row company">
            <label for="b_company-input" class="col-3 col-form-label">Company</label>
            <div class="col-9 input-group">
            <input autocomplete="off" id="b_company-input" class="form-control" name="b_company" type="text" value="{{$estimate->b_company}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_address1-input" class="col-3 col-form-label">Address 1</label>
            <div class="col-9 input-group">
                <input id="b_address1-input" class="form-control" name="b_address1" type="text" value="{{$estimate->b_address1}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_address2-input" class="col-3 col-form-label">Address 2</label>
            <div class="col-9 input-group">
                <input id="b_address2-input" class="form-control" name="b_address2" type="text" value="{{$estimate->b_address2}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_phone-input" class="col-3 col-form-label">Phone</label>
            <div class="col-9 input-group">
                <input id="b_phone-input" class="form-control" name="b_phone" type="text" value="{{$estimate->b_phone}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_country-input" class="col-3 col-form-label">Country</label>
            <div class="col-9 input-group">
                <?php echo $countries->getAllCountries($estimate->b_country) ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="b_state-input" class="col-3 col-form-label">State</label>
            <div class="col-9 input-group">
                <?php echo $countries->getAllStates($estimate->b_state,'b_',$estimate->b_country) ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="b_city-input" class="col-3 col-form-label">City</label>
            <div class="col-9 input-group">
                <input id="b_city-input" class="form-control" name="b_city" type="text" value="{{$estimate->b_city}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_zip-input" class="col-3 col-form-label">Zip Code</label>
            <div class="col-9 input-group">
                <input id="b_zip-input" class="form-control" name="b_zip" type="text" value="{{$estimate->b_zip}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_email-input" class="col-3 col-form-label">Email</label>
            <div class="col-9 input-group">
                <input id="b_email-input" class="form-control" name="email" type="text" value="{{$estimate->b_email}}">
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
                <input id="s_firstname-input" class="typeahead1 form-control" name="s_firstname" type="text" value="{{$estimate->s_firstname}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_lastname-input" class="col-3 col-form-label">Last Name</label>
            <div class="col-9 input-group">
                <input id="s_lastname-input" class="form-control" name="s_lastname" type="text" value="{{$estimate->s_lastname}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_company-input" class="col-3 col-form-label">Company</label>
            <div class="col-9 input-group">
                <input id="s_company-input" class="form-control" name="s_company" type="text" value="{{$estimate->s_company}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_address1-input" class="col-3 col-form-label">Address 1</label>
            <div class="col-9 input-group">
            <input id="s_address1-input" class="form-control" name="s_address1" type="text" value="{{$estimate->s_address1}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_address2-input" class="col-3 col-form-label">Address 2</label>
            <div class="col-9 input-group">
                <input id="s_address2-input" class="form-control" name="s_address2" type="text" value="{{$estimate->s_address2}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_phone-input" class="col-3 col-form-label">Phone</label>
            <div class="col-9 input-group">
                <input id="s_phone-input" class="form-control" name="s_phone" type="text" value="{{$estimate->s_phone}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_country-input" class="col-3 col-form-label">Country</label>
            <div class="col-9 input-group">
            <?php echo $countries->getAllCountries(0,'s_') ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="s_state-input" class="col-3 col-form-label">State</label>
            <div class="col-9 input-group">
                <?php echo $countries->getAllStates($estimate->s_state,'s_',$estimate->s_country) ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="s_city-input" class="col-3 col-form-label">City</label>
            <div class="col-9 input-group">
                <input id="s_city-input" class="form-control" name="s_city" type="text" value="{{$estimate->s_city}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_zip-input" class="col-3 col-form-label">Zip Code</label>
            <div class="col-9 input-group">
                <input id="s_zip-input" class="form-control" name="s_zip" type="text" value="{{$estimate->s_zip}}">
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

<div class="form-group row float-right">
    <div class="col-12">
        <label for="s_freight-input" class="col-form-label"><b>Shipping Cost:</b></label>
        &nbsp;<input name='freight' id="s-freight-input" style="width: 100px; text-align: right;display:inline" class="form-control" value="{{ number_format($estimate->freight,2) }}" />
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
<script src="{{ asset('/js/jquery.autocomplete.min.js') }}"></script>
<script src="{{ asset('/fancybox/jquery.fancybox.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'create-order-estimator';
    var selection = '';

    $(document).ready( function() {
        $( "#datepicker" ).datepicker();

        function fillInData(data) {
            $('#customer_id').val(data.id);
            for (name in data) {
                $('#estimateForm input').each ( function () {
                    $('#b_'+ name +'-input').val(data[name]);
                    $('#s_'+ name +'-input').val(data[name]);
                })
            }
        }

        var getPath = "{{route('get.customer.info')}}";
        var mainPath = "{{route('get.customer.byID')}}";

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
                url: "{{route('ajax.estimated.products')}}",
                data: {
                    _token: "{{csrf_token()}}",
                    _blade: blade
                },
                success: function (result) {
                    $('#product-container').html(result.content.content+result.content.jquery);
                    selection = result.selection;

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


        @include ('admin.countrystate',['billExt'=>'b_', 'shipExt'=>'s_'])

        $(document).on('click','.deleteitem',function() {
            $(this).parents('tr').remove();
        })

        $('.billing input').on('input propertychange', function() {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
        })

        $(document).on('click','.backorder',function() {
            $(this).parents('tr').find('td').each(function(i) {
                if (i>1) {
                    var value = $(this).parents('tr').find('td:eq('+i+')').find('input').val();
                    if (i!=4)
                        $(this).parents('tr').find('td:eq('+i+')').find('input').val('-'+value)
                    else {
                        $(this).parents('tr').find('td:eq('+i+')').find('input').val('Backorder')
                        return false;
                    }
                }
            })
        })

        $('.create').click (function (e) {
            if (!$('#customer_id').val() && $('#b_company-input').val().length > 0) {
                e.preventDefault();
                if (confirm("Customer doesn't exit. Would you like to create it?")) {
                    $.ajax({
                        type: "GET",
                        url: "{{route('ajax.save.customer')}}",
                        data: {
                            _token: csrf_token,
                            _form: $('#estimateForm').serialize()
                        },
                        success: function (result) {
                            $('#customer_id').val(result);
                            $('#estimateForm').submit();
                        }
                    })
                }
            }

            if ($('.estimate-products tr').length == 2) {
                $('html, body').animate({scrollTop : 0},600);
                $('.additem').tooltip('show');
                e.preventDefault();
            }

        })

        paymentOptions($('#payment-input option:selected').text());
        $('#payment-input').change( function () {
            paymentOptions(this.value);
        })

        function paymentOptions(method) {
            $('#payment-options-name-input option').each (function () {
                if (method=='Invoice') {
                    if (this.value=='None')
                        $(this).hide()
                    else $(this).show()
                } else {
                    if (this.value!='None') {
                        $(this).hide()
                        $('#payment-options-name-input option').eq(7).prop('selected','')
                    } else {
                        $(this).show()
                        $('#payment-options-name-input option').eq(7).prop('selected','selected')
                    }
                }
            })
            if (method=='Invoice')
                $('#payment-options-name-input option').eq(0).prop('selected','selected')

        }

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
                                        return false;
                                    } else {
                                        var tr = $(_this).parents('tr');

                                        tr.find('td:eq(0)').children('input').val(result["id"])
                                        img = tr.find('td:eq(0)').find('img');

                                        if (img.length > 0)
                                            img.remove('img');

                                        tr.find('td:eq(0)').append(result['image']);
                                        // tr.find('td:eq(1)').children('input:nth(0)').val(result['id'])
                                        tr.find('td:eq(1)').children('input:nth(0)').val(result['model'])
                                        tr.find('td:eq(1)').children('input:nth(1)').val(result['id'])
                                        tr.find('td:eq(1)').children('input:nth(2)').val(result['reference']+' ('+result['model']+')' )
                                        tr.find('td:eq(1)').children('input').attr('disabled',false)
                                        tr.find('td:eq(1)').children('input').val();
                                        //tr.find('td:eq(2)').children('input').val(result['qty']);
                                        //tr.find('td:eq(3)').find('input').val(result['price']);
                                        tr.find('td:eq(4)').children('input').val(result['serial']);
                                        if (!tr.find('td:eq(5)').find('input').val())
                                            tr.find('td:eq(5)').find('input').val(result['retail']);

                                        if (tr.find('td:eq(6)').children().length == 0)
                                            tr.find('td:eq(6)').append('<a class="btn btn-danger delete nonsubmit deleteitem"><i class="fa fa-trash" aria-hidden="true"></i>');

                                        if (tr.index() == $('.estimate-products tr').length-2 && $(_this).val() != '') {
                                            $.ajax({
                                                type: "GET",
                                                url: "{{route('new.invoice.row')}}",
                                                data: { _blade: blade },
                                                success: function (result) {
                                                    $('.estimate-products tr').eq($('.estimate-products tr').length-1).after(result);
                                                    setTimeout(function(){
                                                        $('.estimate-products tr').eq($('.estimate-products tr').length-1).find('td:eq(4)').find('input').focus()
                                                    }, 300);

                                                }
                                            })
                                        }
                                    }
                                }
                            }
                        })

                        //if (result.status) {

                        //}
                    }
                });
            }
        });

    })

</script>
@endsection