@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
<link href="/js/fancybox/jquery.fancybox.min.css" rel="stylesheet">
@endsection

@section ('content')

<form method="POST" action="{{route('orders.store')}}" id="orderForm">
    @csrf
    <input type="hidden" name="customer_id" id="customer_id">
    <input type="hidden" name="creditamount">
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
            <tr>
                <td><input type="hidden" name="product_id[]" /><input type="hidden" name="model[]" /><input type="hidden" name="img_name[]" /></td>
                <td ><input type="text" class="form-control" name="product_name[]" placeholder="Use Serial field" disabled /> </td>
                <td><input type="text" class="form-control" style="width: 50px" name="qty[]" /></td>
                <td style="text-align: right">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="price[]"></input>
                    </div>
                </td>
                <td style="text-align: right"><input style="width:80px" type="text" class="form-control serial-input" placeholder="SPS" name="serial[]" /></td>
                <td style="text-align: right;">
                    <div class="input-group">
                        <div class="input-group-addon">$</div>
                        <input class="form-control" type="text" name="retail[]"></input>
                    </div>
                </td>
                <td style="width: 30px;text-align: center">
                    <a class="btn btn-danger delete nonsubmit"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </td>
            </tr>

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
    
    <div style="padding: 5px;font-size: 18px;display: none">
        <span style="font-weight: 700;color: green">Available credit: <span id="creditamount"></span></span>
    </div>

    @include('admin.errors')
    <div class="form-group row">
        <div class="col-6">
            <label for="po-input" class="col-form-label">PO Number</label>
            <input class="form-control" autocomplete="off" value="<?php echo !empty(old('po')) ? old('po') : '' ?>" type="text" name="po" id="po-input">
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
                    <input class="form-control" autocomplete="off" value="<?php echo !empty(old('b_company')) ? old('b_company') : '' ?>" type="text" name="b_company" id="b_company-input">
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
                <label for="b_country" class="col-3 col-form-label">Country</label>
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
            <div class="form-group row">
                <label for="b_email-input" class="col-3 col-form-label">Email</label>
                <div class="col-9">
                    <input class="form-control" value="<?php echo !empty(old('email')) ? old('email') : '' ?>" type="text"  name="email" id="b_email-input">
                </div>
            </div>            
        </div>
    </div>

    <div class="order-group shipping">
        <div class="group-title">Shipping Address</div>
        <div class="p-1">
            <div class="form-group row slastname">
                <label for="s_firstname-input" class="col-3 col-form-label">First Name</label>
                <div class="col-9">
                    <input class="typeahead1 form-control" autocomplete="off" value="<?php echo !empty(old('s_firstname')) ? old('s_firstname') : '' ?>" type="text" name="s_firstname" id="s_firstname-input">
                </div>
            </div>
            <div class="form-group row sfirstname">
                <label for="s_lastname-input" class="col-3 col-form-label">Last Name</label>
                <div class="col-9">
                    <input class="form-control" autocomplete="off" value="<?php echo !empty(old('s_lastname')) ? old('s_lastname') : '' ?>" type="text" name="s_lastname" id="s_lastname-input">
                </div>
            </div>
            <div class="form-group row scompany">
                <label for="s_company-input" class="col-3 col-form-label">Company</label>
                <div class="col-9 input-group">
                    <input class="form-control" autocomplete="off" value="<?php echo !empty(old('s_company')) ? old('s_company') : '' ?>" type="text" name="s_company" id="s_company-input">
                </div>
            </div>
            <div class="form-group row">
                <label for="s_address-input" class="col-3 col-form-label">Address 1</label>
                <div class="col-9 input-group">
                    <input class="form-control" value="<?php echo !empty(old('s_address')) ? old('s_address1') : '' ?>" type="text" name="s_address1" id="s_address-input">
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
                <label for="s_country" class="col-3 col-form-label">Country</label>
                <div class="col-9">
                    <?php echo $countries->getAllCountries(0,'s_') ?>
                </div>
            </div>
            <div class="form-group row">
                <label for="s_state-input" class="col-3 col-form-label">State</label>
                <div class="col-9">
                    <?php echo $countries->getAllStates(0,'s_') ?>
                    <input type="hidden" id="h_state">
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

<div id="img_loader" class="absolute border-solid border-gray-color bg-gray-50 h-10 w-25 hidden shadow-lg border overflow-y-auto">
    
    <ul class="w-100 bg-white list-group"></ul>

</div>
<div id="product-container"></div>
<div id="search-customer"></div>
@endsection

<!-- Order - Create -->
@section ('footer')
<script src="/js/fancybox/jquery.fancybox.min.js"></script>
<script src="/js/jquery.autocomplete.min.js"></script>
<script src="/js/general.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'create';
    var fromShipping=false;
    var img_selection;

    $(document).ready( function() {
        $( "#datepicker" ).datepicker();
        
        function fillInData(data,exclude) {
            $('#customer_id').val(data.id);
            if (data.credit > 0) {
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
                                _model: suggestion.value,
                                _blade: 'order'
                            },
                            success: function (result) {
                                if (result) {
                                    status = result.status;
                                    if (result.status) {
                                        $(_this).val('')
                                        $.alert(result.statusText)
                                        return false;
                                    } else {
                                        var dupl = 0;
                                        $('.order-products tr').each(function(i,r) {
                                            if (i>0) {
                                                prevres = $(this).children(0).find('input[name="product_id[]"]').val()
                                                if (result["id"] == prevres && prevres != 491) {
                                                    $(_this).val('')
                                                    $.alert("You have entered a duplicate item. ")
                                                    dupl = 1;
                                                    return false;
                                                }
                                            }
                                        })
                                        if (dupl==1) {
                                            return false;
                                        }
                                        var tr = $(_this).parents('tr');

                                        tr.find('td:eq(0)').children('input').val(result["id"])
                                        tr.find('td:eq(0)').children('input').next().val(result["model"])
                                        img = tr.find('td:eq(0)').find('img');

                                        if (img.length > 0)
                                            img.remove('img');
                                        
                                        tr.find('td:eq(0)').append(result['image']);
                                        tr.find('td:eq(1)').children('input').val(result['reference']+' ('+result['model']+')' );
                                        tr.find('td:eq(1)').children('input').attr('disabled',false)
                                        tr.find('td:eq(2)').children('input').val(result['qty']);
                                        tr.find('td:eq(3)').find('input').val(result['price']);
                                        tr.find('td:eq(4)').children('input').val(result['serial']);
                                        tr.find('td:eq(5)').find('input').val(result['retail']);
                                        if (tr.find('td:eq(6)').children().length == 0)
                                            tr.find('td:eq(6)').append('<a class="btn btn-danger delete nonsubmit deleteitem"><i class="fa fa-trash" aria-hidden="true"></i>');

                                        if (tr.index() == $('.order-products tr').length-4 && $(_this).val() != '') {
                                            $.ajax({
                                                type: "GET",
                                                url: "{{route('new.invoice.row')}}",
                                                data: { _blade: 'invoice_new' },
                                                success: function (result) {
                                                    $('.order-products tr').eq($('.order-products tr').length-3).after(result);
                                                    setTimeout(function(){ 
                                                        $('.order-products tr').eq($('.order-products tr').length-3).find('td:eq(4)').find('input').focus()
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
        
        $('#apply_credit').click(function() {
            if ($('#apply_credit').val()=='Remove credit') {
                $('#apply_credit').val('Apply credit - $'+$('#apply_credit').attr('data-amount'));
                $('#credit').val('');
            } else {
                $('#credit').val($('#apply_credit').attr('data-amount'));
                $('#apply_credit').val('Remove credit')
            }
        })

        var getPath = "{{route('ajax.get.customer')}}";
        var mainPath = "{{route('ajax.customer')}}";

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

        $('.fancybox-close-small').click( function () {
            $.fn.fancybox.close()
        })

        $('.billing input,.billing select').on('input propertychange', function(e) {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
                
        })

        $('#b_country,#s_country').change( function() {
            _this = $(this);
            $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
            .done (function (data) {
                if ($(_this).attr('id') == 'b_country') {
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


        paymentOptions('Invoice');
        $('.create').click (function (e) {
            $(this).prop("disabled",true)

            if ($('#creditamount').text()) {
                e.preventDefault();
                $.confirm({
                    content: "There is an available credit of " + $('#creditamount').text() + ". " + "Would you like to apply it? Check if credit was applied after this.",
                    buttons: {
                        apply: function() {
                            $('input[name="creditamount"]').val($('#creditamount').attr('data-amount'));
                            $('form').submit();
                        },
                        no: function() {
                            return true
                        },
                        cancel: function() {
                            $(this).prop("disabled",false)
                        }
                    }
                })
            }

            if ($('.order-products tr').length == 2) {
                $('html, body').animate({scrollTop : 0},600);
                $('.additem').tooltip('show');
                e.preventDefault();
            }

            $("#orderForm").submit()
        })

        // Hide menu when clicking anywhere outside
        $(document).on("click", function () {
            $("#img_loader").addClass("hidden").removeData("active-button");
        });

        $(document).on("click", "#img_loader ul li", function () {
            img_selection.attr('src', $(this).find('img').attr('src'));
            img_selection.parent().find('input').eq(2).val(img_selection.attr('src'));
            debugger
        });

        $(document).on('click', '.tblimg', function(e) {
            e.stopPropagation(); // Prevent closing when clicking the button

            let menu = $("#img_loader");
            let button = $(this);

            img_selection = button;
            let buttonOffset = button.position();
            let buttonHeight = button.outerHeight();
            let menuHeight = menu.outerHeight();
            let windowHeight = $(window).height();
            let scrollTop = $(window).scrollTop(); // Get scroll position

            let spaceBelow = windowHeight + scrollTop - (buttonOffset.top + buttonHeight);
            let spaceAbove = buttonOffset.top - scrollTop;

            let topPosition;

            // Show below if there's space, otherwise show above
            if (spaceBelow >= menuHeight || spaceBelow > spaceAbove) {
                topPosition = buttonOffset.top + buttonHeight + 5;
            } else {
                topPosition = buttonOffset.top - menuHeight - 5;
            }
            
            // Check if the menu is already open for the same button
            if (menu.is(":visible") && menu.data("active-button") === button[0]) {
                menu.addClass("hidden").removeData("active-button"); // Hide menu
            } else {
                if (!menu.find('li').length) {
                    request = $.ajax({
                        url: '{{ route("get.product.images") }}',
                        // data: {},
                        success: function (result) {
                            menu.find('ul').append(result);
                        }
                    })
                    request.fail( function (jqXHR, textStatus) {
                    
                    })
                }

                menu.css({
                    top: topPosition + "px",
                    left: buttonOffset.left + "px",
                }).removeClass("hidden").data("active-button", button[0]); // Show menu and store active button
            }
            // $('#img_loader').show();

        })
    })

</script>
@endsection