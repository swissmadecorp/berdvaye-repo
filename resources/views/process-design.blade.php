@extends('layouts.default')

@section ("title")
  Design and Preporation
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
          <div class="bannerBox" style="background-image:url(images/other_banners/ob_01.jpg)"> <img class="owl-lazy" data-src="images/other_banners/ob_01.jpg" alt="ob_01"/> </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-8">
            <h2>Design & preparation</h2>
            <p class="text-center">Once deconstructed, the timepieces and parts are then painstakingly reimagined over one month of design work to expose the tremendous intricacies and human ingenuity involved in fine watchmaking.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/grading_img_00.jpg" alt="Selection Grading"/></div>
              <div class="defaultimg leftImg"><img src="images/process/grading_img_01.jpg" alt="Selection Grading"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Selection & <span>Grading</span></h2>
              <p>Each and every gear, barrel, wheel, spring, escapement wheel, pinion, bridge, and hands is storied. The vast majority of the components are of Swiss origin. Each part is individually and carefully reviewed similar to the process of selecting and grading the most precious diamonds. </p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 order-12">
            <div class="defaultBox-img float-left">
              <div class="defaultimg"><img src="images/process/positioning_img_00.jpg" alt="Positioning in Lucite&reg;"/></div>
              <div class="defaultimg rightImg"><img src="images/process/positioning_img_01.jpg" alt="Positioning in Lucite&reg;"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6 order-1">
            <div class="defaultBox-info float-right">
              <h2>Positioning <span>in Lucite&reg;</span></h2>
              <p>Components are restored, polished and meticulously positioned, balanced in precious, crystal clear shatter-resistant Lucite&reg;.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/baking_img_00.jpg" alt="Placement & Baking"/></div>
              <div class="defaultimg leftImg"><img src="images/process/baking_img_01.jpg" alt="Placement & Baking"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Placement <span>& Baking</span></h2>
              <p>The artisans place the components with a highly controlled method, ensuring total balance of size and color within the piece. Each sculpture then begins an arduous process of repeatedly placing components in layers of Lucite&reg;, and then baking in highly pressurized 1,000° Oracle ovens.</p>
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

    // $('.creationprocessmenu').addClass('active');
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