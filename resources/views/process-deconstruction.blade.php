@extends('layouts.default')

@section ("title")
  Deconstruction & Sourcing
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
          <div class="bannerBox" style="background-image:url(images/other_banners/ob_00.jpg)"> <img class="owl-lazy" data-src="images/other_banners/ob_00.jpg" alt="ob_00"/> </div>
        </div>
      </div>
    </div>
    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-9 col-md-8">
            <h2>Deconstruction & Sourcing</h2>
            <p class="text-center">The founders of Berd Vay’e scour the globe, fueled by shared passion to collect antique watches and clocks. The personal quests spills into the professional work, amassing 50-100 year old treasures from the watch industry’s most acclaimed manufactures. Recent treasures include collectible backwind pocket watches, to remnants of 1900s Americana in the form of stop clocks once belonging to railroad conductors. The designers painstakingly disassemble broken collectibles, restoring the integrity of each and every component by immortalizing them in art.  These acquisitions power the combined creativity of Berd Vay’e. </p>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/pocketwatch_img_00.jpg" alt="pocketwatch_img_00"/></div>
              <div class="defaultimg leftImg"><img src="images/process/pocketwatch_img_01.jpg" alt="pocketwatch_img_00"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Our Artisan's Heirloom</h2>
              <h3>The Piece Which Awoke A Passion</h3>
              <p class="pt-4">A passed-down pocket watch led to a life-long quest to immortalize time. Symbolic of a shared heritage, the founders of Berd Vay’e draw continual inspiration from this natively Russian family heirloom.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 order-12">
            <div class="defaultBox-img float-left">
              <div class="defaultimg"><img src="images/process/rolex_img_00.jpg" alt="Rolex"/></div>
              <div class="defaultimg rightImg"><img src="images/process/rolex_img_01.jpg" alt="Rolex"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6 order-1">
            <div class="defaultBox-info float-right">
              <h2>Rolex <span>Pocket Watch</span></h2>
              <h3>A 1930s Field Operative’s Rolex </h3>
              <p class="pt-4">The original military identification numbers are emblazoned on the stately black dial and engraved on the case back. Signed Rolex A 18632, this manual-wind, Swiss Made pocket watch glimmers with gold painted lettering and Arabic numerals. </p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/omega_img_00.jpg" alt="Omega"/></div>
              <div class="defaultimg leftImg"><img src="images/process/omega_img_01.jpg" alt="Omega"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Omega</h2>
              <h3>An Art Deco-inspired Omega Circa The 1930s</h3>
              <p class="pt-4">A collectible Swiss-made pocket watch bearing a frosted gold tone dial. Fully signed for Omega, serial number 5814281, and features a manual wind movement with steel visible winding gears. </p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 order-12">
            <div class="defaultBox-img float-left">
              <div class="defaultimg"><img src="images/process/longines_img_00.jpg" alt="Longines"/></div>
              <div class="defaultimg rightImg"><img src="images/process/longines_img_01.jpg" alt="Longines"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6 order-1">
            <div class="defaultBox-info float-right">
              <h2>Longines</h2>
              <h3>An Antique Longines Chronometer Pocket Watch </h3>
              <p class="pt-4">The original military identification numbers are emblazoned on the stately black dial and engraved on the case back. Signed Rolex A 18632, this manual-wind, Swiss Made pocket watch glimmers with gold painted lettering and Arabic numerals. </p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/lange_img_00.jpg" alt="A. Lange & Söhne"/></div>
              <div class="defaultimg leftImg"><img src="images/process/lange_img_01.jpg" alt="A. Lange & Söhne"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>A. Lange & Söhne</h2>
              <h3>A Coveted A. Lange & Söhne Glashütte i/SA</h3>
              <p class="pt-4">This two-tone golden dial pocket watch heralds from the late 1930s / early 1940s with globe hands, stately Arabic numerals and a chronometer.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 order-12">
            <div class="defaultBox-img float-left">
              <div class="defaultimg"><img src="images/process/rolex_orchild_img_00.jpg" alt="Rolex Orchid"/></div>
              <div class="defaultimg rightImg"><img src="images/process/rolex_orchild_img_01.jpg" alt="Rolex Orchid"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6 order-1">
            <div class="defaultBox-info float-right">
              <h2>Rolex Orchid <span>Movement</span></h2>
              <h3>The Movement Of A Ladies Rolex From The 1920s</h3>
              <p class="pt-4">This movement would have been encapsulated by an 18K art deco-style women’s ‘Orchid’ watch, made in Switzerland by Rolex. </p>
            </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun bg-gray-900 mb-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/process/pocketwatch_img_03.jpg" alt="pocketwatch_img_00"/></div>
              <div class="defaultimg leftImg"><img src="images/process/pocketwatch_img_04.jpg" alt="pocketwatch_img_00"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Vintage <span>pocketwatch<span></h2>
              <h3>Shimmering Vintage Golden Pocket Watch</h3>
              <p class="pt-4">Exquisite etching embellishes the case of this 1800s pocket watch, representing a bygone era and a treasure in itself.</p>
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

    $('#gallerySilder').owlCarousel({
        margin: 10,
        items: 4,
        nav: false,
        autoWidth: false,
        dots: false,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 4,
            },
            1200: {
                items: 4,
            }

        }
    });

    $('#contactForm').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            var url = "assets/php/contact.php";
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function(data) {
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"> <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>' + messageText + '</div>';
                    if (messageAlert && messageText) {
                        $('#contactForm').find('.messages').html(alertBox);
                        $('#contactForm')[0].reset();
                    }
                }
            });
            return false;
        }
    });

    $(document).on('keydown', '.trimSpace', function(e) {
        if (e.which === 32 && e.target.selectionStart === 0) {
            return false;
        }
    });


});
</script>
@endpush