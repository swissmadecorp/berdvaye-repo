@extends('layouts.default')

@section ("title",'Gravity')


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
      <img src="images/product/product_08/hero_banner/hb_00.jpg" alt="hb_00" class="w-100"/>
      <div class="bannerBox-info opacity-80">
        <h3>Gravity</h3>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/product/product_08/product_img_01.jpg" alt="Horosphere"/></div>
              <div class="defaultimg leftImg"><img src="images/product/product_08/product_img_00.jpg" alt="Horosphere"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
              <h2>Gravity</h2>
              <p class="pt-3 pb-3"> A marvel in suspension. A transcendant horosphere encrusted with vintage watch parts seemingly oats within an encompassing angular cube. The crystal clear Lucite structure rests curiously on its corner, presenting a captivating feat of balance - both of the cubic outer layer and for the orb suspended within</p>
              <div class="productBox">
                <div class="productBox-size"> <a href="#" class="active" data-parts="2,000-2,500" data-other="39 lbs. &nbsp;&amp;&nbsp; 9.5x9.5x9.5">Large</a> </div>
                <p class="productBox-qty">Limited edition: 999 pieces</p>
                <p class="productBox-part callParts">Weight: 39 lbs</p>
                <p class="productBox-part callSize">Dimensions: <span>9.5x9.5x9.5</span> <small>Inches</small></p>
                @include ('addtocart',['data'=>'','model'=>'cbl-gr'])</div>
          </div>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="images/product/product_08/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="images/product/product_08/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="images/product/product_08/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="productBanner animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_08/banners/banner_01.jpg" alt="banner_01"/>
              <div class="productBanner-info">
                <h2>01</h2>
                <p>A marvel of 3D geometry, “Gravity” rounds out our collection of 2-in-1 sculptures, boasting our watch parts-encrusted horosphere within a stately cube of pure Lucite.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_08/banners/banner_02.jpg" alt="banner_02"/>
              <div class="productBanner-info">
                <h2>02</h2>
                <p>Our “Gravity" sculpture illustrates the literal force of suspension, of movement halted in time to showcase the naturally magnified intricacies of the components within.</p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/product/product_08/banners/banner_03.jpg" alt="banner_03"/>
              <div class="productBanner-info">
                <h2>03</h2>
                <p>Gravity” is a showstopper within any room - your guests will be awed by the feat of balance of the inner orb that rests within our precariously perched cube - as if the entire sculpture has been caught tumbling through time and space.</p>
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

    @section('og_title', "Modern Art Gravity Sculpture")
    @section('og_desc', "A marvel in suspension. A transcendant horosphere encrusted with vintage watch parts seemingly oats within an encompassing angular cube. The crystal clear Lucite structure rests curiously on its corner, presenting a captivating feat of balance - both of the cubic outer layer and for the orb suspended within.")
    @section('og_image', "https://berdvaye.com/images/product/product_08/product_img_00.jpg")

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