{{-- @if (strpos(session()->get('referrer'),"www.producermichael.com")>0) --}}
@inject('product_retail','App\Libs\RetailPrice')

<?php
    $product = $product_retail->RetailPrice($model); 
    $retail = $product->p_retail;
    
?>


<!-- <span id="retail_price" style="text-decoration: line-through;">${{number_format($retail,2)}}</span>&nbsp; -->
<div class="flex justify-content-between">
    <span id="price" style="font-size: 20px;">${{number_format($retail,2)}}</span>
    <button class="btn btn-sm btn-warning add-to-cart" data-url="{{ $model }}">Add to Cart</button>
</div>
{{--@else --}}
<!-- <button class="btn btn-sm pricingModal">Request Pricing</button>  -->
{{-- @endif --}}

@section ("jquery")

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {
    $(document).on('click', '.productBox-size a', function(e) {
        e.preventDefault();
        if ($('.productBox-size').children().length==2) {
            var partsVal = $(this).attr('data-parts');
            var weightVal = $(this).attr('data-weight');
            var dimensionstVal = $(this).attr('data-dimensions');
            var priceVal = $(this).attr('data-price');

            const formattedAmount = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }).format(Number(priceVal));

            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            $('.callParts').html('Number of Parts: ' + partsVal);
            $('.callWeight span').html(weightVal);
            $('.callSize span').html(dimensionstVal);
            
            // $('.add-to-cart').text('Add '+$(this).text()+' to Cart');
            $('.add-to-cart').attr('data-url',$(this).attr('data-id'))
            $('#price').text(formattedAmount);
            // $.get("{{ route('retail.price') }}", { model: $(this).attr('data-id') }).done(function(data) {
                // $('#retail_price').text(data.retail);
                // $('#percent').text(data.percent);
            // })
        }
    });


        $('.add-to-cart').click( function (e) {
            e.preventDefault();
            _this=$(this);
            $.ajax({
                type: "POST",
                url: "{{route('add.to.cart')}}",
                data: {'model': $(_this).attr('data-url'), 'qty': 1},
                success: function (result) {
                    document.location.href = '/cart';
                    
                }
            })
        })
    })
</script>

@endsection