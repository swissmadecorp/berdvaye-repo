@extends('layouts.default')

@section ("title")
  Time Squared
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
      <img src="images/product/product_02/hero_banner/hb_00.jpg" alt="hb_00" class="w-100"/>
      <div class="bannerBox-info opacity-80">
        <h3>Time Squared</h3>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/product/product_02/product_img_01.jpg" alt="Horosphere"/></div>
              <div class="defaultimg leftImg"><img src="images/product/product_02/product_img_00.jpg" alt="Horosphere"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
              <h2>Time Squared</h2>
              <p class="pt-3 pb-3">Structured integrity; the Time Squared sculpture appears to tumble through time, resting remarkably mid-air a spectacular angular display of the watch components meticulously arranged in abstract formations.</p>
              <div class="productBox">
                <div class="productBox-size"> <a href="#" class="active" data-parts="3,000-3,500" data-other="9.6 lbs. &amp;&nbsp; 6x6x6" data-id='cbs'>Small</a> <a href="#" data-parts="4,000-4,500" data-other="39 lbs. &amp;&nbsp; 9.5x9.5x9.5" data-id='cbl'>Large</a> </div>
                <p class="productBox-qty">Limited edition: 999 pieces</p>
                <p class="productBox-part callParts">Number of Parts: 3,000-3,500</p>
                <p class="productBox-part callSize">Weight & Dimensions: <span>9.6 lbs. &amp;&nbsp; 6x6x6</span> <small>Inches</small></p>
                @include ('addtocart',['data'=>'variation','model'=>'cbs'])</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="images/product/product_02/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="images/product/product_02/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="images/product/product_02/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="productBanner animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_02/banners/banner_01.jpg" alt="banner_01"/>
              <div class="productBanner-info">
                <h2>01</h2>
                <p>This ingeniously balanced cubic sculpture adds dimension and intrigue to any living space and will captivate your visitors.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_02/banners/banner_02.jpg" alt="banner_02"/>
              <div class="productBanner-info">
                <h2>02</h2>
                <p>Artistic revelry; a showstopping collector’s piece designed to fascinate.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_02/banners/banner_03.jpg" alt="banner_03"/>
              <div class="productBanner-info">
                <h2>03</h2>
                <p>An exercise in dynamism; a seemingly suspended feat of design perfect for display in a study.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_02/banners/banner_04.jpg" alt="banner_04"/>
              <div class="productBanner-info">
                <h2>04</h2>
                <p>Natural magnification through crystal clear Lucite creates the illusion that metallic components are floating effortlessly in the space between riveting dinner conversations.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro pb-5 pt-5 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9">
            <h2>Berd Vay’e <span>Benefits</span></h2>
            <p class="text-center">Enjoy our unique and authentic gifting & packaging for a more exciting experience of gift giving.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb pt-2 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="images/product/product_benefits/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="images/product/product_benefits/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="images/product/product_benefits/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
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