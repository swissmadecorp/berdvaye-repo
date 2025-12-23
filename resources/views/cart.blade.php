@extends('layouts.default')

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
  <main class="bg-white">
    <div class="defaultBanner">
      <div id="defaultBanner" class="owl-carousel owl-theme ownCarousel">
        <div class="item galleryLink">
          <div class="bannerBox" style="background-image:url(images/product/product_07/hero_banner/hb_00.jpg)"> <img class="owl-lazy" data-src="images/product/product_07/hero_banner/hb_00.jpg" alt="hb_00"/> </div>
        </div>
      </div>
      <div class="bannerBox-info">
        <h3>Shopping Cart</h3>
      </div>
    </div>

    <div class="productThumb " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="images/product/hgs/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="images/product/product_05/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="images/product/cbl-ha/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="productBanner " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          <div class="item galleryLink">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_07/banners/banner_01.jpg" alt="banner_01"/>
              <div class="productBanner-info">
                <h2>01</h2>
                <p>Within "Galaxy" our imaginative artisans designed an abstract depiction of the watchmaker’s universe: a cluster of watch parts from treasured timepieces, now ‘vintage’ and having lost functionality. Sought out by our experts, these pieces of history are disassembled then restored to start life anew as modern art.</p>
              </div>
            </div>
          </div>
          <div class="item galleryLink">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_07/banners/banner_02.jpg" alt="banner_02"/>
              <div class="productBanner-info">
                <h2>02</h2>
                <p>Our sculptures are the perfect compliment to your collection of art, or as a centerpiece atop any table. Each crystal clear Lucite&reg; design piece holds within it the varied stories and histories of the passage of time - and is a sure conversation piece among all who view it, from every angle.</p>
              </div>
            </div>
          </div>
          <div class="item galleryLink">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_07/banners/banner_03.jpg" alt="banner_03"/>
              <div class="productBanner-info">
                <h2>03</h2>
                <p>The production process for "Galaxy" expands upon our signature artistic methodology, developing the brand’s repertoire through the creation of this new "2-in-1" sculpture. Each watch component encrusted orb seems to move, yet stands still within the artistry that is Berd Vay’e. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro pb-5 pt-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container">
        <div class="clearfix justify-content-center">
          <div class="">
            <h2>Shopping Cart</h2>

            @if($products)

                @include ('carttemplate',['step' => 1] )
                <form method="POST" action="cart/checkout" accept-charset="UTF-8" autocomplete="off">
                    @csrf
                    <button type="submit"  class="btn btn-secondary">Checkout
                        <i class="fas fa-angle-double-right"></i>
                    </button><br><br>
                </form>

            @else
            @if ($ret['error'] == 'qty')
              <div><span style="color: red">{{$ret['product_name'] }}</span> {{$ret['description']}}</div><br>
            @elseif ($ret['error'] == 'none')
            <div>{{$ret['description']}}</div><br>
            @endif
            <h4 class="flex justify-content-center pb-5 pt-5">Your cart is empty.</h4>
            @endif

            <div style="background: #fbfbfb; border: 1px solid #dee2e6"><h5 style="padding: 3px">YOU MAY ALSO LIKE</h5></div>
            <div class="defaultSlider">
              <div class="gallerySilder">
                <div id="gallerySilder" class="owl-carousel owl-theme ownCarousel cart-bottom">
                  @foreach ($r_products as $product)
                  <?php $location = str_replace(' ','-',$product->model_name); $img_location = "/images/gallery/thumbnail/".strtolower($product->p_model) .'.jpg' ?>
                  <div class="item"><a class="galleryLink" href="/product/{{$location}}"><img src="{{$img_location}}" alt="thumbnail"> <span>{{$product->model_name}}</span></a><br>
                      <a href="/product/{{$location}}" title="{{$product->model_name}}" style="display: block" class="btn btn-secondary btn-sm">View</a>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')

    @include('modalform')

@endsection

@push ('jquery')
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function($) {

      $('#gallerySilder').owlCarousel({
        margin: 10,
        items: 4,
        nav: true,
        autoWidth: false,
        dots: false,
        responsive: {
            0: {
                items: 1,
								nav: true,
            },
            768: {
                items: 3,
            },
            1200: {
                items: 5,
            }

        }
    });

    $('.homemenu').addClass('active');
        // $('.menuBar div.rightMenu').append('<img class="imgCenter" src="images/menu/menu_banner_01.jpg" alt="menu_banner_01">');

    $(document).on('click', '.remove', function(e) {
        e.preventDefault();
        _this = $(this);

        $.ajax({
            type: "POST",
            data: {id: $(_this).attr('data-id')},
            url: "{{route('cart.remove')}}",
            success: function (result) {
                document.location.href = '/cart';
            }
        })
    })
})

</script>

@endpush