@extends('layouts.default')

@section ("title")
  Knight
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
  <main>
    <div class="defaultBanner">
      <div id="defaultBanner" class="">
          <img src="images/product/product_13/hero_banner/hb_00.jpg" alt="hb_00"/>
      </div>
      <div class="bannerBox-info" style="opacity: 0.8">
        <h3>Knight</h3>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/product/product_13/product_img_01.jpg" alt="Knight"/></div>
              <div class="defaultimg leftImg"><img src="images/product/product_13/product_img_00.jpg" alt="Knight"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
              <h2>Knight</h2>
              <p class="pt-3 pb-3"> The third ranking piece protects the higher-ranking royal court. “Knight” comes from the Persian word rukh meaning chariot.&reg;.</p>
              <div class="productBox">
                <p class="productBox-qty">Limited edition: 999 pieces</p>
                <p class="productBox-part callParts">Number of Parts: 2,000-2,500</p>
                <p class="productBox-part callSize">Weight & Dimensions: <span>16 lbs. &amp;&nbsp; 14 x 7 </span> <small>Inches</small></p>
                @include ('addtocart',['data'=>'','model'=>'KNIGHT'])</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="images/product/product_13/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="images/product/product_13/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="images/product/product_13/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="productBanner animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_13/banners/banner_01.jpg" alt="banner_01"/>
              <div class="productBanner-info">
                <h2>01</h2>
                <p>Within "Knight" our imaginative artisans designed an abstract depiction of the watchmaker’s universe: a cluster of watch parts from treasured timepieces, now ‘vintage’ and having lost functionality. Sought out by our experts, these pieces of history are disassembled then restored to start life anew as modern art.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_13/banners/banner_02.jpg" alt="banner_02"/>
              <div class="productBanner-info">
                <h2>02</h2>
                <p>Our sculptures are the perfect compliment to your collection of art, or as a centerpiece atop any table. Each crystal clear Lucite&reg; design piece holds within it the varied stories and histories of the passage of time - and is a sure conversation piece among all who view it, from every angle.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_13/banners/banner_03.jpg" alt="banner_03"/>
              <div class="productBanner-info">
                <h2>03</h2>
                <p>The production process for "Knight" expands upon our signature artistic methodology, developing the brand’s repertoire through the creation of this new Chess Knight sculpture. Each watch component encrusted orb seems to move, yet stands still within the artistry that is Berd Vay’e. </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')

    @include('modalform')

@endsection

@section ('jquery')
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

  $('.collectionmenu').addClass('active');

    setTimeout(function() {
        $('.animateRun').viewportChecker();
    }, 1000);

    $(document).on('keydown', '.trimSpace', function(e) {
        if (e.which === 32 && e.target.selectionStart === 0) {
            return false;
        }
    });

    var owl = $('#defaultBanner');
    owl.owlCarousel({
        margin: 0,
        mouseDrag: false,
        nav: false,
        navSpeed: 50,
        items: 1,
        loop: false,
        dots: true,
        lazyLoad: true,
        autoplay: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        autoplayHoverPause: true
    });

    function setAnimation(_elem, _InOut) {
        var animationEndEvent = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        _elem.each(function() {
            var $elem = $(this);
            var $animationType = 'animated ' + $elem.data('animation-' + _InOut);
            $elem.addClass($animationType).one(animationEndEvent, function() {
                $elem.removeClass($animationType);
            });
        });
    }

    owl.on('change.owl.carousel', function(event) {
        var $currentItem = $('.owl-item', owl).eq(event.item.index);
        var $elemsToanim = $currentItem.find("[data-animation-out]");
        setAnimation($elemsToanim, 'out');
    });

    var round = 0;
    owl.on('changed.owl.carousel', function(event) {
        var $currentItem = $('.owl-item', owl).eq(event.item.index);
        var $elemsToanim = $currentItem.find("[data-animation-in]");

        setAnimation($elemsToanim, 'in');
    })

    owl.on('translated.owl.carousel', function(event) {
        //console.log(event.item.index, event.page.count);
        if (event.item.index == (event.page.count - 1)) {
            if (round < 1) {
                round++
                console.log(round);
            } else {
                owl.trigger('stop.owl.autoplay');
                var owlData = owl.data('owl.carousel');
                owlData.settings.autoplay = false;
                owlData.options.autoplay = false;
                owl.trigger('refresh.owl.carousel');
            }
        }
    });



    var owl = $('#productBanner');
    owl.owlCarousel({
        margin: 0,
        mouseDrag: false,
        nav: true,
        navSpeed: 800,
        items: 1,
        loop: false,
        dots: true,
        lazyLoad: true,
        navText: ["<img src='images/icons/icon_next.png' alt='next'>", "<img src='images/icons/icon_prev.png' alt='prev'>"],
        autoplayHoverPause: true
    });

    $('#gallerySilder').owlCarousel({
        margin: 10,
        items: 1,
        nav: false,
        autoWidth: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1200: {
                items: 2,
            },
            1369: {
                items: 4,
            }
        }
    });




});
</script>
@endsection