@extends('layouts.default')

@section ("title")
  Formation and Finishing
@endsection

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
          <div class="bannerBox" style="background-image:url(images/other_banners/ob_02.jpg)"> <img class="owl-lazy" data-src="images/other_banners/ob_02.jpg" alt="ob_02"/> </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9">
            <h2>Forming & Finishing</h2>
            <p class="text-center">Innovatively preserving the history of watchmaking through a meticulous reconditioning and reinvention process.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/handcrafted_img_00.jpg" alt="Handcrafted"/></div>
              <div class="defaultimg leftImg"><img src="images/process/handcrafted_img_01.jpg" alt="Handcrafted"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Handcrafted <span>with perfection</span></h2>
              <p>Each Berd Vay’e piece is casted with 8-10 layers over a 24 hour period. The sculpture is then baked, carefully protecting it from the formation of air bubbles or imperfections thus resulting in a seamless, crystal clear sculpture. The cooled Lucite&reg; is shatter resistant, having heralded from its early use on submarines and helicopters. Each sculpture is then shaped and polished by hand, requiring relentless attention from multiple artisans to complete every one-of-a-kind piece.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 order-12">
            <div class="defaultBox-img float-left">
              <div class="defaultimg"><img src="images/process/contemporary_img_00.jpg" alt="contemporary art"/></div>
              <div class="defaultimg rightImg"><img src="images/process/contemporary_img_01.jpg" alt="contemporary art"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6 order-1">
            <div class="defaultBox-info float-right">
              <h2>Bespoke <span>contemporary art</span></h2>
              <p>This process forever preserves the history in watchmaking through reinvention. The result is a visual effect of gears floating in the air, allowing collectors to have a 360 degree appreciation for details that are normally hidden behind watch dials. Berd Vay’e breathes new life into the appreciation of horology with distinctive, Bespoke conversation pieces like no contemporary art you’ve ever seen before. </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')
@endsection

@push ('jquery')
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

$('#creationProcessLinks').addClass('active');
$('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_01.jpg" alt="menu_banner_01">');


setTimeout( function(){
   $('.animateRun').viewportChecker();
	},1000);

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

});
</script>
@endpush