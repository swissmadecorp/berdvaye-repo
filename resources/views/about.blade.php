@extends('layouts.default')

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header class="bg-black">
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
        @livewire('header')
        <div id="cart-component">
          @livewire('cart-component')
        </div>
    </div>
  </header>

  <!-- MAIN -->
  <main class="bg-darkgray">
    <div class="defaultBanner">
      <div id="defaultBanner" class="owl-carousel owl-theme ownCarousel bg-darkgray">
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/other_banners/ob_03.jpg)"> <img class="owl-lazy" data-src="images/other_banners/ob_03.jpg" alt="ob_03"/> </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-8 col-lg-7">
            <h2>Our Story</h2>
            <p class="text-center pb-4">Berd Vay’e is the brainchild of two dynamic individuals with decades of experience in the fine watch and jewelry industry.</p>
            <p class="text-center pb-4">The pair combined a shared fascination which began at a young age with watchmaking and the harmony in which watch components work together to inspire art lovers and watch collectors alike from around the world.</p>
            <p class="text-center">These two artisans have sourced and continue to source thousands of vintage watches and watch components from around the globe with the final goal of the preserving their venerable history in precious Lucite&reg;. They passionately seek out new design inspiration from the world of horology. The name "Berd Vay’e" represents a culmination of syllables from the designer's names, an homage to their each of their personal passions for horology itself.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/about/history_img_00.jpg" alt="Preserving History"/></div>
              <div class="defaultimg leftImg"><img src="images/about/history_img_01.jpg" alt="Preserving History"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Preserving <span>History</span></h2>
              <p>These artisans have sourced some of the best-made vintage watch components from around the globe, preserving their venerable history in precious Lucite&reg;. The name "Berd Vay’e" represents a culmination of syllables from their own names, an homage to their own personal passion for horology itself.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="productBanner pb-5 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="images/about/banners/banner_01.jpg" alt="banner_01"/> </div>
            <small class="font-italic d-block text-dark pt-1 pl-1 pr-1 text-center">"Family heirloom provides inspiration for the first Berd Vay’e designs. It sits nearby on the designer’s desk as a symbol of a long term respect for the history of timekeeping."</small> </div>
        </div>
      </div>
    </div>
    <hr width="96%">
    <div class="defaultIntro pt-4 pb-5 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%" id="authenticity">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9 col-lg-9">
            <h2>Berd Vay’e Authenticity</h2>
            <p class="text-center">Each Berd Vay’e limited edition sculpture is presented in a polished mahogany case, lined in satin. Each piece is numbered and features accompanying gloves and a .925 silver plaque  a designed to depict a jewel from a mechanical watch gear. A Certificate of Authenticity bearing the sculpture's production number is included. </p>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb pt-2 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="images/about/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="images/about/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="images/about/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>

    @include('contactInfo')
    @endsection

@push ('jquery')
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

  $('.aboutmenu').addClass('active');
  $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_02.jpg" alt="menu_banner_02">');

setTimeout( function(){
   $('.animateRun').viewportChecker();
	},1000);

$(document).on('click', '.productSize a', function(e) {
            e.stopPropagation();
												var price = $(this).attr('data-amt');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
												$('.productPrice').html(price);
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
			     navText : ["<img src='images/icons/icon_next.png' alt='next'>","<img src='images/icons/icon_prev.png' alt='prev'>"],
        autoplayHoverPause: true
    });

    $('#gallerySilder').owlCarousel({
        margin: 10,
        items: 1,
        nav: false,
								autoWidth:true,
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
@endpush

