@extends('layouts.admin-default')
@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 
<link href="font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection
@section ('content')
@inject('countries','App\Libs\Countries')
<?php $payments_options = ['None' =>'None','Net-30'=>'Net 30','Net-60'=>'Net 60','Net-120'=>'Net 120']; ?>
<p><b>Purchase Date: {{ $estimate->created_at->format('m/d/Y') }}</b></p>
<form method="POST" action="{{route('estimates.update',[$estimate->id])}}" id="estimateform">
    @csrf
    @method('PATCH')
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $estimate->customers()->first()->id }}"> 
    <div class="form-group row">
        <div class="col-6">
            <label for="po-input" class="col-form-label">PO Number</label>
            <input name="po" id="po-input" class="form-control" type="text" value="{{$estimate->po}}">
        </div>    
        <div class="col-6">
            <label for="payment-input" class="col-form-label">Payment Method</label>
            <select class="form-control" name="method" id="payment-input">
                @foreach (Payments() as $value => $payment)
                    <option value="{{ $payment }}" <?php echo !empty($estimate->method) && $estimate->method==$payment ? 'selected' : '' ?>>{{ $payment }}</option>
                @endforeach
            </select>
            <label for="payment-options-name-input" class="col-form-label">Payment Options</label>
            <select class="form-control" id="payment-options-name-input" name="payment_options">
                @foreach (PaymentsOptions() as $value => $payment_option)
                <option value="{{ $value }}" <?php echo !empty($estimate->payment_options) && $estimate->payment_options==$value ? 'selected' : '' ?>>{{ $payment_option }}</option>
            @endforeach
        </select>
        </div>    
    </div>
<div class="order-group billing" style="margin-right: 8px;margin-bottom: 8px;">
    <div class="group-title">Billing Address</div>
    <div class="p-1">
        <div class="form-group row">
            <label for="b_firstname-input" class="col-3 col-form-label">First Name</label>
            <div class="col-9 input-group">
                <input autocomplete="off" id="b_firstname-input" class="typeahead form-control" name="b_firstname" type="text" value="{{$estimate->b_firstname}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_lastname-input" class="col-3 col-form-label">Last Name</label>
            <div class="col-9 input-group">
                <input autocomplete="off" id="b_lastname-input" class="typeahead form-control" name="b_lastname" type="text" value="{{$estimate->b_lastname}}">
            </div>
        </div>
        <div class="form-group row">
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
            <label for="b_city-input" class="col-3 col-form-label">City</label>
            <div class="col-9 input-group">
            <input id="b_city-input" class="form-control" name="b_city" type="text" value="{{$estimate->b_city}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="b_state-input" class="col-3 col-form-label">State</label>
            <div class="col-9 input-group">
            <?php echo $countries->getAllStates($estimate->b_state,'b_',$estimate->b_country) ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="b_zip-input" class="col-3 col-form-label">Zip Code</label>
            <div class="col-9 input-group">
            <input id="b_zip-input" class="form-control" name="b_zip" type="text" value="{{$estimate->b_zip}}">
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
                <input autocomplete="off" id="s_firstname-input" class="typeahead form-control" name="s_firstname" type="text" value="{{$estimate->s_firstname}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_lastname-input" class="col-3 col-form-label">Last Name</label>
            <div class="col-9 input-group">
                <input autocomplete="off" id="s_lastname-input" class="typeahead form-control" name="s_lastname" type="text" value="{{$estimate->s_lastname}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="s_company-input" class="col-3 col-form-label">Company</label>
            <div class="col-9 input-group">
                <input autocomplete="off" id="s_company-input" class="form-control" name="s_company" type="text" value="{{$estimate->s_company}}"> 
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
            <label for="s_city-input" class="col-3 col-form-label">City</label>
            <div class="col-9 input-group">
            <input id="s_city-input" class="form-control" name="s_city" type="text" value="{{$estimate->s_city}}">
            </div>
        </div>                    
        <div class="form-group row">
            <label for="s_state-input" class="col-3 col-form-label">State</label>
            <div class="col-9 input-group">
                <?php echo $countries->getAllStates($estimate->s_state,'s_',$estimate->s_country) ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="s_zip-input" class="col-3 col-form-label">Zip Code</label>
            <div class="col-9 input-group">
            <input id="s_zip-input" class="form-control" name="s_zip" type="text" value="{{$estimate->s_zip}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="email-input" class="col-3 col-form-label">Email</label>
            <div class="col-9 input-group">
            <input id="email-input" class="form-control" name="email" type="text" value="{{$estimate->email}}">
            </div>
        </div>
    </div>
</div>
<table class="table estimate-products">
    <thead>
        <tr>
        <th>Image</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Reference</th>
        <th>Retail</th>
        <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($estimate->products as $product)
        <tr>
            <td>
                <img style="width: 80px" src="/images/thumbs/{{ $product->retail->image_location }}" />
                <input type="hidden" value="{{$product->retail->p_model}}" name="p_model[]" />
                <input type="hidden" value="{{$product->id}}" name="op_id[]" />
            </td>
            <td><input type="text" class="form-control" value="{{ $product->product_name }}" name="product_name[]" /> </td>
            <td><input type="text" class="form-control" style="width: 50px" value="{{ $product->qty }}" name="qty[]" /></td>
            <td style="text-align: right">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" type="text" value="{{ number_format($product->price,0,'','') }}" name="price[]" />
                </div>
            </td>
            <td>{{ $product->p_model }}</td>
            <td style="text-align: right; width: 130px">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" value="{{ number_format($product->retail_price,0,'','') }}" type="text" name="retail[]" />
                </div>
            </td>
            <td style="width: 30px;text-align: center">
                <a class="btn btn-danger delete nonsubmit" data-id="{{$product->id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
        </tr>
        @endforeach
        <tr>
            <td><input type="hidden" name="p_model[]" /></td>
            <td ><input type="text" class="form-control" name="product_name[]" placeholder="Use Serial field" disabled/> </td>
            <td><input type="text" class="form-control" style="width: 50px" name="qty[]" /></td>
            <td style="text-align: right">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" type="text" name="price[]" />
                </div>
            </td>
            <td style="text-align: right"><input style="width:80px" type="text" placeholder="SPS" class="form-control serial-input" name="serial[]" /></td>
            <td style="text-align: right; width: 130px">
                <div class="input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" type="text" name="retail[]"></input>
                </div>
            </td>
            <td style="width: 30px;text-align: center">
                <a class="btn btn-danger nonsubmit deleteitem"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align: right" colspan="6"><b>Sub Total</b></td>
            <td style="text-align: right">{{ number_format($estimate->subtotal,2) }}</td>
        </tr>
        @if ($estimate->customers->first()->cgroup==0)
        <tr>
            <td style="text-align: right" colspan="6"><b>Tax</b></td>
            <td style="text-align: right">{{ number_format($estimate->taxable,3) }}</td>
        </tr>
        @endif
        <tr>
            <td style="text-align: right" colspan="6"><b>Freight</b></td>
            <td style="text-align: right"><input name='freight' id="s-freight-input" style="width: 100px; text-align: right;display:inline" class="form-control" value="{{ number_format($estimate->freight,2) }}" /></td>
        </tr>  
        <tr>
            <td style="text-align: right" colspan="6"><b>Method</b></td>
            <td style="text-align: right">
                <select name="ship_method" class="form-control" id="">
                    <option value="Ground" {{ $estimate->ship_method=='Ground' ? "selected" : ""}}>Ground</option>
                    <option value="2nd Day" {{ $estimate->ship_method=='2nd Day' ? "selected" : ""}}>2nd Day</option>
                    <option value="Overnight" {{ $estimate->ship_method=='Overnight' ? "selected" : ""}}>Overnight</option>
                    <option value="International" {{ $estimate->ship_method=='International' ? "selected" : ""}}>International</option>
                </select>
            </td>
        </tr>      
        <tr>
            <td style="text-align: right" colspan="6"><b>Grand Total</b></td>
            <td style="text-align: right">${{number_format($estimate->total,2)}}</td>
        </tr>
    </tfoot>
</table>
<div class="form-group row">
    <div class="col-12">
        <label for="comments-input" class="col-form-label">Comments</label>
        <textarea type="text" name="comments" rows="5" id="comments-input" class="form-control">{{ $estimate->comments }}</textarea>
    </div>    
</div>
<button type="submit" class="btn btn-danger update">Update Order</button>
@include('admin.errors')

</form>
<div id="product-container"></div>
@endsection
@section ('footer')
<script src="/js/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection      
@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'edit-estimator';
    $(document).ready( function() {
        @include ('admin.countrystate',['billExt'=>'b_', 'shipExt'=>'s_'])
        $('.billing input').on('input propertychange', function() {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
        })

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

                                    tr.find('td:eq(0)').children('input').val(result["id"])
                                    img = tr.find('td:eq(0)').find('img');

                                    if (img.length > 0)
                                        img.remove('img');
                                    
                                    tr.find('td:eq(0)').append(result['image']);
                                    tr.find('td:eq(0)').children('input').val(result['model']);
                                    tr.find('td:eq(1)').children('input').val(result['reference']+' ('+result['model']+')');
                                    tr.find('td:eq(1)').children('input').attr('disabled',false)
                                    tr.find('td:eq(2)').children('input').val(result['qty']);
                                    tr.find('td:eq(3)').find('input').val(result['price']);
                                    tr.find('td:eq(4)').children('input').val(result['model']);
                                    tr.find('td:eq(5)').find('input').val(result['retail']);
                                    if (tr.find('td:eq(6)').children().length == 0)
                                        tr.find('td:eq(6)').append('<a class="btn btn-danger delete nonsubmit deleteitem"><i class="fa fa-trash" aria-hidden="true"></i>');

                                    if (tr.index() == $('.estimate-products tr').length-6 && $(_this).val() != '') {
                                        $.ajax({
                                            type: "GET",
                                            url: "{{route('new.invoice.row')}}",
                                            data: { _blade: 'invoice_new' },
                                            success: function (result) {
                                                $('.estimate-products tr').eq($('.estimate-products tr').length-5).after(result);
                                                setTimeout(function(){ 
                                                    $('.estimate-products tr').eq($('.estimate-products tr').length-5).find('td:eq(4)').find('input').focus()
                                                }, 300);
                                                
                                            }
                                        })
                                    }                                        
                                }
                            }
                        })

                    }
                });
            }
        });

        $('.fancybox-close-small').click( function () {
            $.fn.fancybox.close()
        })
        
        $(document).on('click','.deleteitem',function() {
            $(this).parents('tr').remove();
        })

        $(".delete").on('click', function(e) {
            _this = $(this);
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this item from the order?')) return
            $.ajax({
                type: "GET",
                url: "{{route('destroy.estimated.product')}}",
                data: { 
                    _token: "{{csrf_token()}}",
                    estimateid: "{{$estimate->id}}",
                    productid: _this.attr('data-id')
                },
                success: function (result) {
                    if (result=='success') {
                        location.reload(true);
                    } else 
                        alert('There were some errors while deleting this product.')
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
        
        
        paymentOptions('{{$estimate->method}}');
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
        $(document).on('click','.deletenew', function(e) {
            e.preventDefault();
            $(this).parents('tr').remove();
        })
    })
</script>
@endsection