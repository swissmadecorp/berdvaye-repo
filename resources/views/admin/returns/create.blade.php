@extends('layouts.admin-default')

@section ('content')

@inject('countries','App\Libs\Countries')
<div class="customer_address pb-2">
<?php 
    $address2 = '';
    
    $address2 = '';
    
    $state_b = $countries->getStateCodeFromCountry($order->b_state);
    $country = $countries->getCountry($order->b_country);

    echo $order->b_company.'<br>';
    echo !empty($order->b_address1) ? $order->b_address1 .'<br>' : '';
    echo !empty($order->b_address2) ? $order->b_address2 .'<br>' : '';
    echo !empty($order->b_city) ? $order->b_city .', '. $state_b . ' ' . $order->b_zip.'<br>': '';
    
    echo !empty($order->b_phone) ? $order->b_phone . '<br>' : '';
    echo !empty($order->po) ? 'PO #: '.$order->po . '<br>' : '';

?>
</div>

<div class="pb-2">Order Date: {{ $order->created_at->toFormattedDateString() }}</div>
<div>


<form method="POST" action="{{route('returns.store')}}" id="returnsForm">
<input type="hidden" id="order_id" name="order_id" value="{{ $order->id }}">
<table id="returns" class="table table-striped table-bordered hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Serial #</th>
            <th>Qty Returned</th>
            <th>Qty/Total Ordered</th>
            <th>Amount</th>
            <th>Return</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php $returns = array(); $comment = "";
            $product_ids = array();
            if (!$order->returns->isEmpty())
                $comment = $order->returns->first()->comment 
        ?>

                
        @for ($i=0; $i < $order->returns->count(); $i++)
        <?php $product = $order->returns[$i]->products[$i]; ?>
        <tr>
            <td style="text-align: center;"><img style="width: 70px" src="/images/thumbs/{{ $product->image() }}" /></td>
            <td><a href="/admin/products/{{ $product->id }}/edit">{{ $product->title() }}</a></td>
            <td style="text-align: center">{{ $product->p_serial }}</td>
            <td style="text-align: center">{{ $product->pivot->qty}}</td>
            <td>@if ($order->products->where('id',$product->pivot->product_id))
                    @if ($order->products->where('id',$product->pivot->product_id)->first()->pivot->qty > $product->pivot->qty)<input type="text" style="width: 70px">@endif
                @endif</td>
            <td style="text-align: right">{{ number_format($product->pivot->amount,2) }} </td>
            <td style="text-align: center"></td>
            <td>{{ $product->pivot->created_at->format('m-d-Y') }}</td>
        </tr>
        <?php $product_ids[] = $product->id ?>
        @endfor
    

        @foreach ($order->products as $product)
        @if (!in_array($product->id,$product_ids))
        <?php 
            $qty = ''; $product_id='';$date='';
            
            $qty = $product->p_qty;
            $product_id = $product->id;
            $orderQty = $product->pivot->qty;
            
            // $p_image = $product->images->toArray();
            // if (!empty($p_image)) {
            //     $image=$p_image[0]['location'];
            // } else $image = '../no-image.jpg';

        ?>
        <tr>
            <td><img style="width: 70px" src="/images/thumbs/{{$product->image() }}" /></td>
            <td><a href="/admin/products/{{ $product->id }}/edit">{{ $product->pivot->product_name }}</a></td>
            <td style="text-align: center">{{ $product->p_serial }}</td>
            <td style="text-align: center"></td>
            <td>@if ($orderQty>0)<input type="text" style="width: 70px"> / @endif<?= $orderQty ?> </td>
            <td style="text-align: right">{{ number_format($product->pivot->price,2) }} </td>
            <td style="text-align: center">
                @if ($orderQty>0)
                <input type="hidden" value="{{ $product->id }}" name="product_id[]" />
                <input type="hidden" value="{{ $product->pivot->id }}" name="op_id[]" />
                <input type="hidden" value="{{ $product->pivot->model }}" name="model[]" />
                
                @role('superadmin')
                <button style="padding: 3px 5px" data-opid="{{$product->pivot->id}}" data-productid="{{ $product->id }}" class="btn btn-primary returnItem" aria-label="Left Align">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                </button>
                @endrole
                
                @endif
            </td>
            <td>{{ $date }}</td>
        </tr>
        @endif
        @endforeach
        </tr>
    </tbody>
</table>

    <div class="clearfix"></div>

    <div class="form-group row">
        <div class="col-12">
            <label for="comments-input" class="col-form-label">Comments</label>
            <textarea rows="5" style="width: 100%" id="comments-input" name="comments">{!! e($comment) !!}</textarea>
        </div>
    </div>

    @role('superadmin')
    <button type="submit" <?php echo count($returns) ? 'disabled' : '' ?> class="btn btn-primary returnAll">Return All</button>
    @endrole

@include('admin.errors')   
</div>

</form>
@endsection

@section ('jquery')
<script>
    $(document).ready( function() {
        $('.returnItem').click( function(e) {
            e.preventDefault();
            var _this = $(this);
            var order_id = $('#order_id').val();
            var p = $(this).parents('tr');
            var qty = p.find('td:nth-child(5)').children();
            var qty_returned = p.find('td:nth-child(4)').text();

            if (!qty_returned) qty_returned = 0;
            // if (qty.val() > 1) {
            //     alert ('You are trying to return more than was originally purchased. You can only return ' + qty_purchased + ' back.');
            //     return false;
            // }

            if (!qty.val()) {
                alert ('Please specify the amount you would like to return.');
                return false;
            }
            
            if (confirm('Are you sure you want to return a selected product?')) {
                $.ajax({
                    type: "GET",
                    url: "{{route('ajax.return.item')}}",
                    data: { 
                        _id:    $(_this).attr('data-opid'),
                        _orderid: order_id,
                        _qty:   qty.val(),
                        _model: p.find('td:nth-child(7)').children()[2].value,
                        _productid: $(_this).attr('data-productid'),
                        _comment: $('#comments-input').val()
                    },
                    success: function (result) {
                        if ((parseInt(qty.val()) + parseInt(qty_returned)) == result.qty) {
                            $(_this).remove();
                            p.find('td:nth-child(4)').text(result.qty);
                        } else {
                            p.find('td:nth-child(4)').text((parseInt(qty.val()) + parseInt(qty_returned)));
                        }
                        p.find('td:nth-child(8)').text(result.date);
                        $(qty).remove();
                        p.find('td:nth-child(5)').text('')
                        p.find('td:nth-child(7)').children().remove();
                        p.find('td:nth-child(5)').children().remove();
                        orderReminder(result.order_reminder);
                    }
                })
            }

        })

        function orderReminder(order) {
            $.confirm({
                title: 'Backorder Item',
                content: 'A backordered item is waiting to be shipped for order # '+order.order_id+'. Would you like me to create that order for you for the returned item '+order.item+'?',
                buttons: {
                    formSubmit: {
                        text: 'Create order',
                        btnClass: 'btn-blue',
                        action: function () {
                            var request = $.ajax({
                                url: "{{ route('create.backorder.product') }}",
                                method: 'POST',
                                data: {order: order},
                                success: function (result) {
                                    $.alert(result);
                                }
                            })
                            request.fail( function (jqXHR, textStatus) {
                                $.alert (textStatus)
                            })      
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                }
            });
        }

        $('.returnAll').click( function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to return an entire order?')) {
                $.ajax({
                    type: "GET",
                    url: "{{route('ajax.return.all')}}",
                    data: { 
                        _token: "{{csrf_token()}}",
                        _form: $('#returnsForm').serialize()
                    },
                    success: function (result) {
                        $('#returns tr').each( function () {
                            if ($(this).find('td:nth-child(7)').children().length>0) {
                                $(this).find('td:nth-child(4)').text(1);
                                $(this).find('td:nth-child(5)').children().remove();
                                $(this).find('td:nth-child(7)').children().remove();
                                $(this).find('td:nth-child(8)').text(result);
                            }
                        })
                        
                       $('.returnAll').attr('disabled','disabled') ;
                    }
                })
            }

        })
    })    
</script>
@endsection