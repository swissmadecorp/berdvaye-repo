@extends('layouts.default')

<?php

  ini_set('session.cache_limiter','public');
  session_cache_limiter(false);
?>
@section ("title")
  Shopping Cart
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header class="bg-black">
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
      @include ('nav')
    </div>
  </header>

  <!-- MAIN -->
  <main class="bg-white">
    <div class="defaultBanner">
      <div id="defaultBanner" class="owl-carousel owl-theme ownCarousel">
        <div class="item">
          <div class="bannerBox" style="background-image:url(/images/product/product_07/hero_banner/hb_00.jpg)"> <img class="owl-lazy" data-src="/images/product/product_07/hero_banner/hb_00.jpg" alt="hb_00"/> </div>
        </div>
      </div>
      <div class="bannerBox-info">
        <h3>Shopping Cart</h3>
      </div>
    </div>

    <div class="productThumb " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="/images/product/hgs/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="/images/product/product_05/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="/images/product/cbl-ha/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="productBanner " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="/images/product/product_07/banners/banner_01.jpg" alt="banner_01"/>
              <div class="productBanner-info">
                <h2>01</h2>
                <p>Within "Galaxy" our imaginative artisans designed an abstract depiction of the watchmaker’s universe: a cluster of watch parts from treasured timepieces, now ‘vintage’ and having lost functionality. Sought out by our experts, these pieces of history are disassembled then restored to start life anew as modern art.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="/images/product/product_07/banners/banner_02.jpg" alt="banner_02"/>
              <div class="productBanner-info">
                <h2>02</h2>
                <p>Our sculptures are the perfect compliment to your collection of art, or as a centerpiece atop any table. Each crystal clear Lucite&reg; design piece holds within it the varied stories and histories of the passage of time - and is a sure conversation piece among all who view it, from every angle.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="/images/product/product_07/banners/banner_03.jpg" alt="banner_03"/>
              <div class="productBanner-info">
                <h2>03</h2>
                <p>The production process for "Galaxy" expands upon our signature artistic methodology, developing the brand’s repertoire through the creation of this new "2-in-1" sculpture. Each watch component encrusted orb seems to move, yet stands still within the artistry that is Berd Vay’e. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="defaultIntro pb-5 pt-1 " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container">
        <div class="clearfix justify-content-center">
          <div class="">
            @if (!empty($products))
              <h2>Shopping Cart - Billing Information</h2>

              <p><span style="color: red">Note:</span><br><span class="text-gray-800">We only ship to billing address. If you need us to ship it to an address other than the billing address, please contact us first.</span></p><br>
              @inject('countries','App\Libs\Countries')

              <form method="post" id="payment" action="/payment/order">
                  @csrf
                  <div class="md:flex gap-15">
                    <div class="w-100">
                        <div class="form-group">
                            <input id="email" type="text" placeholder="Email Address" value="<?php echo !empty(session('customer')) ? session('customer')['email'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="email" autofocus required>
                            <p id="email-error" class="hidden alert-danger text-black-50">Email is required.</p>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <input id="b_firstname" type="text" placeholder="First Name" value="<?php echo session('customer') ? session('customer')['b_firstname'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="b_firstname" required>
                                <p id="b_firstname-error" class="hidden alert-danger text-black-50">First name is required.</p>
                            </div>
                            <div class="form-group col-md-6">
                                <input id="b_lastname" placeholder="Last Name" type="text" value="<?php echo session('customer') ? session('customer')['b_lastname'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="b_lastname" required>
                                <p id="b_lastname-error" class="hidden alert-danger text-black-50">Last name is required.</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <input id="b_company" type="text" placeholder="Company" value="<?php echo session('customer') ? session('customer')['b_company'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="b_company">
                        </div>
                        <div class="form-group">
                            <input id="card-billing-address-line-1" placeholder="Address 1" type="text" class="border p-lg-2 w-100 text-muted" value="<?php echo session('customer') ? session('customer')['b_address1'] : '' ?>" name="b_address1" required>
                            <p id="card-billing-address-line-1-error" class="hidden alert-danger text-black-50">Address is required.</p>
                        </div>
                        <div class="form-group">
                            <input id="card-billing-address-line-2" placeholder="Address 2" type="text" value="<?php echo session('customer') ? session('customer')['b_address2'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="b_address2">
                        </div>
                        <div class="form-group">
                            <input id="phone" type="text" placeholder="Phone number" value="<?php echo session('customer') ? session('customer')['b_phone'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="b_phone" required>
                            <p id="phone-error" class="hidden alert-danger text-black-50">Phone # is required.</p>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <input for="city" id="city" type="text" placeholder="City" value="<?php echo session('customer') ? session('customer')['b_city'] : '' ?>" class="border p-lg-2 w-100 text-muted" name="b_city" required>
                                <p id="city-error" class="hidden alert-danger text-black-50">City is required.</p>
                            </div>
                            <div class="form-group col-md-4">
                                @if (session('customer'))
                                <?php echo $countries->getAllStates(session('customer')['b_state'],'b_',class:'border p-lg-2 w-100 text-muted') ?>
                                @else
                                <?php echo $countries->getAllStates(class:'border p-lg-2 w-100 text-muted') ?>
                                @endif
                                <p id="b_state-input-error" class="hidden alert-danger text-black-50">State is required.</p>
                            </div>
                            <div class="form-group col-md-3">
                                <input for="zip" placeholder="Zip Code" type="text" id="card-billing-address-postal-code" class="border p-lg-2  w-100" value="<?php echo session('customer') ? session('customer')['b_zip'] : '' ?>" name="b_zip" required>
                                <p id="card-billing-address-postal-code-error" class="hidden alert-danger text-black-50">Zip is required.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            @if (!empty(session('customer')) && session('customer')['email'])
                            <?php echo $countries->getCountriesWithSortname(session('customer')['card-billing-address-country-code'],'card-billing-address-country-code',class:'border p-lg-2 w-100 text-muted') ?>
                            @else
                            <?php echo $countries->getCountriesWithSortname(name: 'card-billing-address-country-code',class:'border p-lg-2 w-100 text-muted') ?>
                            @endif
                        </div>
                    </div>

                    <div class="w-100  rounded-xs relative">
                        <div id="overlay" style="inset: 0" class="rounded-xs flex absolute z-101 bg-gray-300/30 justify-content-center align-items-center hidden">
                          <svg width="100" height="100" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.72,19.9a8,8,0,0,1-6.5-9.79A7.77,7.77,0,0,1,10.4,4.16a8,8,0,0,1,9.49,6.52A1.54,1.54,0,0,0,21.38,12h.13a1.37,1.37,0,0,0,1.38-1.54,11,11,0,1,0-12.7,12.39A1.54,1.54,0,0,0,12,21.34h0A1.47,1.47,0,0,0,10.72,19.9Z"><animateTransform attributeName="transform" type="rotate" dur="0.75s" values="0 12 12;360 12 12" repeatCount="indefinite"/></path></svg>
                        </div>
                        <!-- <h4 class="text-muted">YOUR ORDER</h4> -->
                          @if($products)
                              @include ('carttemplate',['step' => 1] )
                          @endif
                        <!-- <table class="w-100 text-muted">
                          <tr class="border-bottom">
                            <th>PRODUCT</th>
                            <th class="text-right">SUBTOTAL</th>
                          </tr>
                          <?php $subtotal = 0 ?>
                          @foreach ($products as $product)
                            <?php $subtotal += $product['price']; ?>
                            <tr class="border-bottom">
                              <td class="pt-1 pb-1">{{$product['model_name']}} x 1</td>
                              <td class="text-right">${{ number_format($product['price'],2) }}</td>
                            </tr>
                          @endforeach
                            <tr>
                              <td class="pt-1 pb-1">Subtotal</td>
                              <td class="text-right">${{ number_format($subtotal,2) }}</td>
                            </tr>
                            <tr>
                              <td class="pt-1 pb-1">Shipping</td>
                              <td class="text-right">Free FedEx Ground</td>
                            </tr>
                            <tr class="tax-amount hidden">
                              <td class="pt-1 pb-1">Tax (NY)</td>
                              <td class="text-right"></td>
                            </tr>
                            <tr class="tax-amount hidden">
                              <td  class="pt-1 pb-1">Total</td>
                              <td class="text-right"></td>
                            </tr>
                        </table> -->
                        <div id="paypal-button-container" class="paypal-button-container"></div>
                        <!-- Containers for Card Fields hosted by PayPal -->
                        <div id="card-form" class="card_container">
                          <!-- <div id="card-name-field-container"></div> -->
                          <div id="card-number-field-container"></div>
                          <div id="card-expiry-field-container"></div>
                          <div id="card-cvv-field-container"></div>


                        </div>

                        <div class="flex justify-content-end pr-2">
                            <button type="submit" id="card-field-submit-button" type="button" class="btn btn-success">Pay now
                            <i class="fas fa-angle-double-right"></i>
                            </button>
                        </div>

                        <p id="result-message"></p>
                    </div>
                  </div>
              </form>
            @else
            <h4>Your cart is empty.</h4>
            @endif
          </div>
        </div>
      </div>
    </div>




    @include('contactInfo')

    @include('modalform')

@endsection

@push ('jquery')
<script src="https://www.paypal.com/sdk/js?client-id={{config('paypal.live.client_id')}}&components=buttons,messages,card-fields&disable-funding=paylater"></script>

<script src="/js/paypal.js"></script>

<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {

        // $('.menuBar div.rightMenu').append('<img class="imgCenter" src="/images/menu/menu_banner_01.jpg" alt="menu_banner_01">');
        $.QueryString = (function(paramsArray) {
            let params = {};

            for (let i = 0; i < paramsArray.length; ++i)
            {
                let param = paramsArray[i]
                    .split('=', 2);

                if (param.length !== 2)
                    continue;

                params[param[0]] = decodeURIComponent(param[1].replace(/\+/g, " "));
            }

            return params;
    })(window.location.search.substr(1).split('&'))

    var qs = $.QueryString["a"];

    if (qs) {
        $.ajax({
            type: "post",
            data: {id: qs,_token: '{{csrf_token()}}'},
            url: "{{route('add.to.cart')}}",
            success: function (result) {
                // if (isMobile())
                //     document.location.href = '/cart';
                // else {
                    //if ($('.cart-anim').length>0) {
                      $('#cart').html(result);
                      $('html,body').animate({ scrollTop: 0 }, 'slow');
                        //$('.cart-anim').addClass('move-to-cart')

                       // setTimeout(function(){ window.location.reload(); }, 500);
                    //}
                //}
            }
        })
    }

    checkState();

    function checkState() {
      const selectedValue = $('#b_state-input').val(); // Get the selected value

        // Check if the selected state is NY
        if (selectedValue === '3956') {
          _this = $(this);
            $.get("{{ route('get.tax.value')}}")
            .done (function (data) {
                $('.tax-amount').removeClass('hidden')
                let f = $('.tax-amount').first();
                f.children().eq(1).text(data[0]+'%');
                // f = $('.tax-amount').eq(1);
                // f.children().eq(1).text(data[1]);
                $('.newtotal').text(data[1])
            })
        } else {
            $('.tax-amount').addClass('hidden')
        }
    }

    $('#b_state-input').change(function() {
      checkState();
    });

        $('#b_country-input,#s_country-input').change( function() {
            _this = $(this);
            $.get("{{ route('get.state.from.country')}}",{id: $(_this).val()})
            .done (function (data) {
                if ($(_this).attr('id') == 'b_country-input')
                    $('#b_state-input').html(data);
                else $('#s_state-input').html(data);
            })
        })

        $(document).on('click', '.remove', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                data: {id: $(this).attr('data-id')},
                url: "{{route('cart.remove')}}",
                success: function (result) {
                    if (result>0)
                        location.reload();
                    else document.location.href = '/cart';
                }
            })
        })
    })

</script>

@endpush