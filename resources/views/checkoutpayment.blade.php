@extends('layouts.default')

@section ('header')
<link rel="stylesheet" type="text/css" href="https://www.paypalobjects.com/webstatic/en_US/developer/docs/css/cardfields.css"/>
@endsection

@section ("title")
  Shopping Cart
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header class="bg-darkgray">
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
      @include ('nav')
    </div>
  </header>

  <!-- MAIN -->
  <main>
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
          <li><img src="/images/product/product_04/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="/images/product/product_05/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="/images/product/product_06/thumb/thumb_03.jpg" alt="thumb_03"></li>
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
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-10">
            <h2>Shopping Cart - Payment Information</h2>

            <div class="col-md-7 float-left">@include ('carttemplate')</div>



                @if (app('request')->input('b_country') == 'United States')

                <div class="col-md-5 float-left border pb-4" style="background: #fbfbfb">
                  <div id="paypal-button-container" class="paypal-button-container"></div>
                  <!-- Containers for Card Fields hosted by PayPal -->
                  <div id="card-form" class="card_container">
                    <div id="card-name-field-container"></div>
                    <div id="card-number-field-container"></div>
                    <div id="card-expiry-field-container"></div>
                    <div id="card-cvv-field-container"></div>

                    <div>
                      <label for="card-billing-address-line-1">Billing Address</label>
                      <input
                        type="text"
                        id="card-billing-address-line-1"
                        name="card-billing-address-line-1"
                        autocomplete="off"
                        placeholder="Address line 1"
                      />
                    </div>
                    <div>
                      <input
                        type="text"
                        id="card-billing-address-line-2"
                        name="card-billing-address-line-2"
                        autocomplete="off"
                        placeholder="Address line 2"
                      />
                    </div>
                    <div>
                      <input
                        type="text"
                        id="card-billing-address-country-code"
                        name="card-billing-address-country-code"
                        autocomplete="off"
                        placeholder="Country code"
                      />
                    </div>
                    <div>
                      <input
                        type="text"
                        id="card-billing-address-postal-code"
                        name="card-billing-address-postal-code"
                        autocomplete="off"
                        placeholder="Postal/zip code"
                      />
                    </div>
                    <br /><br />
                    <button id="card-field-submit-button" type="button">
                      Pay now with Card
                    </button>
                  </div>
                  <p id="result-message"></p>
                </div>
                @else

                <p><span style="color: red">Note:</span> since you are not located in the US, your shipping charge will need to be calculated internally.
                Your updated invoice will be emailed to you with the correct shipping amount shortly. You may proceed to finalize your order.</p><br>
                @endif

                <div class="float-right mt-4">
                    <button type="submit" class="btn btn-secondary">Finalize your order
                    <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>


          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')

    @include('modalform')

@endsection

@section ('jquery')
<script src="https://paypal.com/sdk/js?client-id=ARnB3rvgGJkULtn2bTWbyeGY--4mz-uQeoweFv_tELkpkjiDRXq5o4ZFDfPUbMFD2kK4o-XFlibGN-z2&buyer-country=US&components=buttons,card-fields&disable-funding=paylater"></script>

<script src="/js/paypal.js"></script>

<script type="text/javascript">

    jQuery.noConflict();
    jQuery(document).ready(function($) {


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

@endsection

@section ('footer')
    <script src="{{ asset('/js/card.js') }}"></script>
@endsection
