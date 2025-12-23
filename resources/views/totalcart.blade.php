<?php $totals=0; ?>

@if (isset($totalcart) && count($totalcart))
<div class="total-cart animated bounceInDown">
    <ul class="">
        <li>
            <div class="top-cart-inner your-cart">
                <h5 class="text-capitalize">Your Cart</h5>
            </div>
        </li>
        <li>
            <div class="total-cart-pro">
                @foreach ($totalcart as $product)
                <!-- single-cart -->
                <div class="single-cart clearfix">
                    <div class="cart-img float-left">
                        <a href="/{{$product['slug']}}"><img src="/{{$product['image']}}" style="width: 80px"></a>
                        <div class="del-icon" data-id="{{ $product['id'] }}">
                            <a href="#">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="cart-info float-left">
                        <h6 class="text-capitalize">
                            <a href="/{{$product['slug']}}">{{ $product['product_name'] }} </a>
                        </h6>
                        <p>
                            <span>Condition <strong>:</strong></span> {{ $product['condition'] }}
                        </p>
                        <p>
                            <span>Price <strong>:</strong></span> $ {{ number_format($product['webprice'],2) }}
                        </p>
                    </div>
                </div>
                <?php $totals +=$product['webprice'] ?>
                @endforeach
            </div>
        </li>
        <li>
            <div class="top-cart-inner subtotal">
                <h4 class="text-uppercase g-font-2">
                    Total =
                    <span>$ {{ number_format($totals,2) }}</span>
                </h4>
            </div>
        </li>
        <li>
            <div class="top-cart-inner view-cart">
                <h4 class="text-uppercase">
                    <a href="/cart">View cart</a>
                </h4>
            </div>
        </li>
    </ul>
</div>
@endif