@extends('layouts.default')

@section ("title")
  Shopping Cart
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header>
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
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-10">
            <h2>Shopping Cart</h2>

            <!-- Beginnig  -->

            @if (!isset($order) || !$products)
                <h3>Multiple page refresh ditected</h3>
                <div style="background: #fff; padding: 25px;border-radius: 4px;">
                    <div class="container">
                        <div class="col-md-12">
                            <br>
                            <h4>Your order has already been processed.</h4><br>
                            If you would like to modify your order, please call us at 212-840-8463.<br><br>
                            Please do not click the back button. Going back will make us charge you twice.

                        </div>
                    </div>
                </div>

                @else

                <h2>Thank you for your Order!</h2>
                <h3>Your order# is {{ $order->id }}</h3>
                <hr>

                @inject('countries','App\Libs\Countries')
                <?php $state_s = $countries->getStateCodeFromCountry($order->s_state); ?>
                <div style="background: #fff; padding: 25px;border-radius: 4px;">
                    <div class="container">
                      <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless table-condensed thankyoucart">
                                <tr>
                                    <td>
                                    <b>Ship To Address</b>
                                    </td>

                                </tr>
                                @isset($order->s_company)
                                <tr><td colspan="2">{{ $order->s_company }}</td></tr>
                                @endisset
                                <tr>
                                    <td>{{ $order->s_firstname }} {{ $order->s_lastname}}</td>

                                </tr>
                                <tr><td colspan="2">{{ $order->s_address1 }}</td></tr>
                                @if ($order->s_address2)
                                  <tr><td colspan="2">{{ $order->s_address2 }}</td></tr>
                                @endif
                                <tr><td colspan="2">{{ $order->s_city }}, {{ $state_s }} {{ $order->s_zip }}</td></tr>
                                <tr><td colspan="2">{{ $order->s_phone }}</td></tr>
                                <tr><td colspan="2">{{ $countries->getCountry($order->s_country) }}</td></tr>

                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-condensed thankyoucart">
                              <tr>
                                <td><b>Payment Options</b></td>
                              </tr>
                              <tr>
                                <td>{{ $order->payment_options }} </td>
                              </tr>
                            </table>
                        </div>

                        <br><hr>
                        <div class="col-md-12">
                            @include ('carttemplate')

                        </div>

                    </div>
                    </div>
                </div>
                @endif
            <!-- End -->
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')

    @include('modalform')

@endsection