@extends('layouts.admin-default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css"/> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.2/css/select.dataTables.min.css"/> 

<link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection

@section ('content')

<p><b>Purchase Date: {{ $order->created_at->format('m/d/Y') }}</b></p>
{{  Form::model($order, array('route' => array('orders.update', $order->id), 'method' => 'PATCH', 'id' => 'orderForm')) }} 
<input type="hidden" name="customer_id" id="customer_id" value="{{ $order->customers()->first()->id }}">
<p>Purchased from:  
    <select class="form-control" name="purchased_from">
        <option value="1" {{ !empty($order->purchased_from) && $order->purchased_from==1 ? 'selected' : ''  }}>Swiss Made</option>
        <option value="2" {{ !empty($order->purchased_from) && $order->purchased_from==2 ? 'selected' : ''  }}>Signature Time</option>
    </select>
</p>
<div class="form-group row">
    <div class="col-6">
        <label for="po-input" class="col-form-label">PO Number</label>
        {{Form::text('po', null, $attributes = array('name'=>'po',"id"=>"po-input","class"=>"form-control"))}}
    </div>    
    <div class="col-6">
        <label for="payment-input" class="col-form-label">Payment Method</label>
        <select class="form-control" name="method" id="payment-input">
            @foreach (Payments() as $value => $payment)
                <option value="{{ $payment }}" <?php echo !empty($order->method) && $order->method==$payment ? 'selected' : '' ?>>{{ $payment }}</option>
            @endforeach
        </select>

        <label for="payment-options-name-input" class="col-form-label">Payment Options</label>
        <select class="form-control" id="payment-options-name-input" name="payment_options">
            @foreach (PaymentsOptions() as $value => $payment_option)
            <option value="{{ $value }}" <?php echo !empty($order->payment_options) && $order->payment_options==$value ? 'selected' : '' ?>>{{ $payment_option }}</option>
        @endforeach
    </select>
    </div>    
</div>

<div class="row">
    <div class="col-md-6 order-group billing" >
        <div class="group-title">Billing Address</div>
        <div class="p-1">
            <div class="form-group row firstname">
                <label for="b_firstname-input" class="col-3 col-form-label">First Name</label>
                <div class="col-9 input-group">
                    {{Form::text('b_firstname', null, $attributes = array('autocomplete'=>'off',"id"=>"b_firstname-input","class"=>"typeahead form-control"))}}
                </div>
            </div>
            <div class="form-group row lastname">
                <label for="b_lastname-input" class="col-3 col-form-label">Last Name</label>
                <div class="col-9 input-group">
                    {{Form::text('b_lastname', null, $attributes = array('autocomplete'=>'off',"id"=>"b_lastname-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row company">
                <label for="b_company-input" class="col-3 col-form-label">Company</label>
                <div class="col-9 input-group">
                    {{Form::text('b_company', null, $attributes = array('autocomplete'=>'off',"id"=>"b_company-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="b_address1-input" class="col-3 col-form-label">Address 1</label>
                <div class="col-9 input-group">
                    {{Form::text('b_address1', null, $attributes = array("id"=>"b_address1-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="b_address2-input" class="col-3 col-form-label">Address 2</label>
                <div class="col-9 input-group">
                    {{Form::text('b_address2', null, $attributes = array("id"=>"b_address2-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="b_phone-input" class="col-3 col-form-label">Phone</label>
                <div class="col-9 input-group">
                    {{Form::text('b_phone', null, $attributes = array("id"=>"b_phone-input","class"=>"form-control"))}}
                </div>
            </div>        
            <div class="form-group row">
                <label for="b_country-input" class="col-3 col-form-label">Country</label>
                <div class="col-9 input-group">
                    @inject('countries','App\Libs\Countries')
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
                    {{Form::text('b_city', null, $attributes = array("id"=>"b_city-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="b_zip-input" class="col-3 col-form-label">Zip Code</label>
                <div class="col-9 input-group">
                    {{Form::text('b_zip', null, $attributes = array("id"=>"b_zip-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="b_email-input" class="col-3 col-form-label">Email</label>
                <div class="col-9 input-group">
                    {{Form::text('email', null, $attributes = array("id"=>"b_email-input","class"=>"form-control"))}}
                </div>
            </div>            
        </div>
    </div>

    <div class="order-group col-md-6 shipping">
        <div class="group-title">Shipping Address</div>
        <div class="p-1">
            <div class="form-group row">
                <label for="s_firstname-input" class="col-3 col-form-label">First Name</label>
                <div class="col-9 input-group">
                    {{Form::text('s_firstname', null, $attributes = array("id"=>"s_firstname-input","class"=>"typeahead1 form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_lastname-input" class="col-3 col-form-label">Last Name</label>
                <div class="col-9 input-group">
                    {{Form::text('s_lastname', null, $attributes = array("id"=>"s_lastname-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_company-input" class="col-3 col-form-label">Company</label>
                <div class="col-9 input-group">
                    {{Form::text('s_company', null, $attributes = array("id"=>"s_company-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_address1-input" class="col-3 col-form-label">Address 1</label>
                <div class="col-9 input-group">
                {{Form::text('s_address1', null, $attributes = array("id"=>"s_address1-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_address2-input" class="col-3 col-form-label">Address 2</label>
                <div class="col-9 input-group">
                    {{Form::text('s_address2', null, $attributes = array("id"=>"s_address2-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_phone-input" class="col-3 col-form-label">Phone</label>
                <div class="col-9 input-group">
                    {{Form::text('s_phone', null, $attributes = array("id"=>"s_phone-input","class"=>"form-control"))}}
                </div>
            </div>            
            <div class="form-group row">
                <label for="s_country-input" class="col-3 col-form-label">Country</label>
                <div class="col-9 input-group">
                {!! $countries->getAllCountries($order->s_country,'s_') !!}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_state-input" class="col-3 col-form-label">State</label>
                <div class="col-9 input-group">
                    {!! $countries->getAllStates($order->s_state,'s_') !!}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_city-input" class="col-3 col-form-label">City</label>
                <div class="col-9 input-group">
                    {{Form::text('s_city', null, $attributes = array("id"=>"s_city-input","class"=>"form-control"))}}
                </div>
            </div>
            <div class="form-group row">
                <label for="s_zip-input" class="col-3 col-form-label">Zip Code</label>
                <div class="col-9 input-group">
                    {{Form::text('s_zip', null, $attributes = array("id"=>"s_zip-input","class"=>"form-control"))}}
                </div>
            </div>
        </div>
    </div>
    </div>
<table class="table order-products table-striped table-bordered">
    <thead>
        <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Product Name</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Retail</th>
        <th>Serial#</th>
        <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->products as $product)
        
        <?php 
            $foundProduct = 0; $productQty = $product->pivot->qty; $returnQty = 0;
            if ($product->returns) {
                foreach ($product->returns as $return) {
                    if ($order->id == $return->order_id) {
                        $returnQty = $return->qty;
                    }
                }
            }
        
            if (!empty($product->image())) {
                $image=$product->image();
            } else $image = '../no-image.jpg';
        ?>
        @if (($productQty-$returnQty)!=0)
        <tr>
            <td>{{$product->id}}</td>
            <td>
                <img style="width: 70px" src="{{ '/images/'.$image }}" />
                <input type="hidden" value="{{$product->pivot->id}}" name="op_id[]" />
                <input type="hidden" value="{{$product->id}}" name="product_id[]" />
            </td>
            <td  style="width:28%"><input type="text" class="form-control" name="product_name[]" value="{{ !$product->pivot->product_name ?  $product->p_size. ' ' .$product->p_title . ' ' . $product->p_reference .' ('.$product->p_model.')' : $product->pivot->product_name }}" /> </td>
            <td><input type="text" class="form-control" style="width: 50px" value="<?= $returnQty ? $product->pivot->qty-$return->qty : $product->pivot->qty ?>" name="qty[]" /></td>
            <td style="text-align: right">
                <div class="col-2 input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" style="width:70px" type="text" value="{{ $product->pivot->price }}" name="price[]"></input>
                </div>
            </td>
            <td style="text-align: right; width: 92px">
                <div class="col-2 input-group">
                    <div class="input-group-addon">$</div>
                    <input class="form-control" style="width:70px" type="text" value="{{ $product->pivot->retail ? $product->pivot->retail : number_format($product->retailvalue(),2) }}" name="retail[]"></input>
                </div>
            </td>
            @if ($product->pivot->serial =='Backorder')
            <td>
                <input style="width: 100px" class="form-control serial-input" name="serial[]" previous-data="{{ $product->pivot->serial }}" placeholder="SPS" type="text">
                <input type="hidden" name="previous_serial[]" value="{{ $product->pivot->serial }}">
            </td>
            @else
            <td style="text-align: right"><input type="hidden" name="serial[]" value="{{ $product->pivot->serial }}" />{{ $product->pivot->serial }}</td>
            <input type="hidden" name="previous_serial[]">
            @endif
            <td style="width: 30px;text-align: center">
                <a class="btn btn-danger delete nonsubmit" data-id="{{$product->pivot->id}}" data-pid="{{$product->pivot->product_id}}"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
        </tr>
        @endif
        @endforeach
        <tr>
            <td></td>
            <td>
                <input class="form-control product_id" type="hidden" name="product_id[]">
                <input type="hidden" name="op_id[]" />
            </td>
            <td><input class="form-control" class="product_name" name="product_name[]" type="text"></td>
            <td><input class="form-control qtycalc" name="qty[]" pattern="\d*" type="number"></td>
            <td>
                <div class="col-2 input-group">
                    <div class="input-group-addon">$</div>
                    <input style="width: 80px" class="form-control" name="price[]" pattern="^\d*(\.\d{0,2})?$" type="text" value="0">
                </div>
            </td>
            <td>
                <div class="col-2 input-group">
                    <div class="input-group-addon">$</div>
                    <input style="width: 80px" class="form-control" name="retail[]" pattern="^\d*(\.\d{0,2})?$" type="text" value="0">
                </div>
            </td>
            <td><input style="width: 100px" class="form-control serial-input" name="serial[]" placeholder="SPS" type="text"></td>
            <td style="text-align: center">
            <button type="button" style="text-align:center" class="btn btn-primary deleteitem" aria-label="Left Align">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
            </button>
        </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align: right" colspan="7"><b>Sub Total</b></td>
            <td style="text-align: right">{{ number_format($order->subtotal,2) }}</td>
        </tr>
        <!-- @if ($order->customers->first()->cgroup==1)
        <tr>
            <td style="text-align: left;" colspan="5">
                <b>Tax Exemption</b>
                <input type="checkbox" name="taxexempt" <?= $order->taxexempt ? 'checked' : '' ?> class="checkbox" style="width: 30px">
            </td>
            <td style="text-align: right" colspan="2"><b>Tax</b></td>
            <td style="text-align: right"><input type="text" value="{{ number_format($order->taxable,2) }}" style="width: 100px; text-align: right;display:inline" class="form-control" name="taxable" /></td>
        </tr>
        @endif -->
        <tr>
            <td style="text-align: right" colspan="7"><b>Freight</b></td>
            <td style="text-align: right"><input name='freight' id="s_freight-input" style="width: 100px; text-align: right;display:inline" class="form-control" value="{{ number_format($order->freight,2) }}" /></td>
        </tr>
        <tr>
            <td style="text-align: right" colspan="7"><b>Discount</b></td>
            <td style="text-align: right"><input name='discount' id="s_discount-input" style="width: 100px; text-align: right;display:inline" class="form-control" value="{{ number_format($order->discount,2) }}" /></td>
        </tr>
        <tr>
            <td style="text-align: right" colspan="7"><b>Method</b></td>
            <td style="text-align: right">
                <select name="ship_method" class="form-control" id="">
                    <option value="Ground" {{ $order->ship_method=='Ground' ? "selected" : ""}}>Ground</option>
                    <option value="2nd Day" {{ $order->ship_method=='2nd Day' ? "selected" : ""}}>2nd Day</option>
                    <option value="Overnight" {{ $order->ship_method=='Overnight' ? "selected" : ""}}>Overnight</option>
                    <option value="International" {{ $order->ship_method=='International' ? "selected" : ""}}>International</option>
                </select>
            </td>
        </tr>
        <tr>
            <td style="text-align: right" colspan="7"><b>Grand Total</b></td>
            <td style="text-align: right">${{number_format($order->total,2)}}</td>
        </tr>
                  
    </tfoot>
</table>

<div class="form-group row">
    <div class="col-12">
        <label for="tracking-input" class="col-form-label">Tracking No.</label>
        {{Form::text('tracking', null, $attributes = array('name'=>'tracking',"id"=>"tracking-input","class"=>"form-control"))}}
    </div>
</div>

<div class="form-group row">
    <div class="col-12">
        <label for="comments-input" class="col-form-label">Comments</label>
        <textarea type="text" name="comments" rows="5" id="comments-input" class="form-control">{{ $order->comments }}</textarea>
    </div>    
</div>

@include('admin.errors')

<div class="float-right mr-3">
    <button type="submit" class="btn btn-danger update">Update Order</button>
</div>
{{ Form::close() }}
<div id="product-container"></div>
<div id="search-customer"></div>
@endsection

@section ('footer')
<script src="{{ asset('js/jquery.autocomplete.min.js') }}"></script>
<script src="{{ asset('fancybox/jquery.fancybox.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/fh-3.1.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.2/js/dataTables.select.min.js"></script>
@endsection      

@section ('jquery')
<script>
    var csrf_token = "{{csrf_token()}}";
    var blade = 'edit';
    
    $(document).ready( function() {
        @include ('admin.countrystate',['billExt'=>'b_', 'shipExt'=>'s_'])

        function fillInData(data,exclude) {
            if (exclude == 'b_firstname-input')
                $('#customer_id').val(data.id);
            
            for (name in data) {
                if (name != 'id') {
                    if (data[name]) {
                        if (exclude == 'b_firstname-input')
                            $('#b_'+ name +'-input').val(data[name]);
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
        
        $('input.typeahead').devbridgeAutocomplete({
            serviceUrl: mainPath,
            showNoSuggestionNotice : true,
            minChars: 3,
            zIndex: 900,
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

        $('input.typeahead1').devbridgeAutocomplete({
            serviceUrl: mainPath,
            showNoSuggestionNotice : true,
            minChars: 3,
            zIndex: 900,
            onSelect: function (suggestion) {
                //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);

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
                            fillInData(result,1);
                        }
                    }
                })
            }
        });

        stateFromCountry('#b_country-input',"{{$order->b_state}}");
        stateFromCountry('#s_country-input',"{{$order->s_state}}");

        function stateFromCountry(_this,stateId) {
            $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
            .done (function (data) {
                if ($(_this).attr('id') == 'b_country-input') {
                    $('#b_state-input').html(data);
                    $('#b_state-input').val(stateId)
                    $('#s_state-input').html(data);
                    $('#s_state-input').val(stateId)
                } else {
                    $('#s_state-input').html(data);
                    $('#s_state-input').val(stateId)
                }   

                
            })
        }

        $('#b_country-input,#s_country-input').change( function() {
            stateFromCountry($(this),$('#s_state-input').val());
        })

        $('.billing input').on('input propertychange', function() {
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
        })

        $(".addnew").on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{route('new.invoice.row')}}",
                data: { 
                    _token: csrf_token,
                    _blade: 'invoice_edit', 
                },
                success: function (result) {
                    $('.order-products tr').eq($('.order-products tr').length-5).after(result);
                    $('.order-products tr').eq($('.order-products tr').length-5).find('td:eq(0)').find('input').focus()

                    // $.fancybox.open({
                    //     src: "#product-container",
                    //     type: 'inline',
                    //     width: 980,
                    // });
                }
            })
        });

        $(document).on('blur-xs', '.product_id', function(e) {
            e.preventDefault();
            
            if ($(this).val() != '') {
                _this = $(this);
                $.ajax({
                    type: "GET",
                    async: false,
                    url: "{{route('find.product')}}",
                    data: { _token: csrf_token,id: $(this).val() },
                    success: function (result) {
                        if (result.error==1){
                            $(_this).parents('tr').find('td:eq(2)').find('input').focus()
                            alert ('Product not found');
                        } else {
                            var pr = $(_this).parents('tr');
                            td = $('td',pr)
                            loadNew = true;
                            if (result.onhand==0) {
                                if ( !confirm('Item is out of stock. Do you still want to add it?') ) {
                                    loadNew = false;
                                    return false;
                                }
                            } else if (result.status!=0) {
                                if ( !confirm('This item is Reserved or On Hold. Do you still want to add it?') ) {
                                    loadNew = false;
                                    return false;
                                }
                            }
                            
                            
                            $(td).eq(1).children().remove();
                            $(td).eq(1).append(result.image)
                            
                            $(td).eq(2).find('input').val(result.product_name)
                            $(td).eq(3).find('input:eq(1)').val(result.product_id)
                            $(td).eq(3).find('input:eq(2)').val(1)
                            if (result.price==0)
                                $(td).eq(5).html('<input type="text" value="'+result.price+'" class="form-control" style="width: 60px" name="newcost[]">')
                            else    
                                $(td).eq(5).find('span').text(result.price)

                            $(td).eq(6).find('input').val(result.serial)
                            $(td).eq(4).find('input').attr({
                                'oninput': "setCustomValidity('')", 
                                'oninvalid': "this.setCustomValidity('Please enter a price amount')",
                                'required': 'required'
                            })
                            

                            //calculateProfits();
                            //$('.order-products tfoot').find('td:eq(0)').html('<b>Qty:</b> '+($('.order-products tr').length-2))
                        }
                    }
                })

            }
        })

        $('.fancybox-close-small').click( function () {
            $.fn.fancybox.close()
        })

        $(document).on('focus', '.serial-input', function () { 
            _this = $(this);
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

                                    tr.find('td:eq(0)').text(result['id']);
                                    tr.find('td:eq(1)').children('.product_id').val(result['id']);
                                    if (tr.find('td:eq(1)').children('img').attr('src'))
                                        tr.find('td:eq(1)').children('img').remove()

                                    tr.find('td:eq(1)').append(result['image']);
                                    tr.find('td:eq(2)').children('input').val(result['title']);
                                    tr.find('td:eq(3)').children('input').val(result['qty']);
                                    tr.find('td:eq(4)').find('input').val(result['price']);
                                    tr.find('td:eq(5)').find('input').val(result['retail']);
                                    //tr.find('td:eq(6)').children('input').val(result['serial']);
                                    tr.find('td:eq(6)').children('input:nth-child(1)').val(result['serial']);

                                    if (tr.index() == $('.order-products tr').length-7 && $(_this).val() != '') {
                                        $.ajax({
                                            type: "GET",
                                            url: "{{route('new.invoice.row')}}",
                                            data: { _blade: 'invoice_edit' },
                                            success: function (result) {
                                                $('.order-products tr').eq($('.order-products tr').length-6).after(result);
                                                setTimeout(function(){ 
                                                    $('.order-products tr').eq($('.order-products tr').length-6).find('td:eq(6)').find('input').focus()
                                                }, 300);
                                                
                                            }
                                        })
                                    }
                                    }
                                }
                            }
                        })

                        
                    }
                });
                
            }
        });
        
        $('.billing input, .billing select').on('input propertychange', function(e) { 
            id=$(this).attr('id');
            $('#s'+id.substr(1)).val($(this).val());
            $('#s_country-input').change()
            //if (!$('#s'+id.substr(1)).val() || e.currentTarget.tagName=="SELECT")
                
        })
        
        paymentOptions('{{$order->method}}');
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

        $(".delete").on('click', function(e) {
            _this = $(this);
            e.preventDefault();
            if (!confirm('Are you sure you want to delete this item from the order?')) return

            $.ajax({
                type: "GET",
                url: "{{route('destroy.product')}}",
                data: { 
                    orderid: "{{$order->id}}",
                    productid: _this.attr('data-pid'),
                    opid: _this.attr('data-id')
                },
                success: function (result) {
                    if (result=='success') {
                        location.reload(true);
                    } else 
                        alert('There were some errors while deleting this product.')
                }
            })
        });
        
        $(document).on('click','.deleteitem', function(e) {
            e.preventDefault();
            $(this).parents('tr').remove();
        })

    })

</script>
@endsection