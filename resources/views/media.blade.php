@extends('layouts.default')

@section ("title")
  Media
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header class="bg-black">
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
      @include ('nav')
    </div>
  </header>

  <!-- MAIN -->
  <main class="bg-darkgray">
    <main class="bg-darkgray">
      <div id="defaultBanner" class="owl-carousel owl-theme ownCarousel bg-darkgray">
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/other_banners/ob_04.jpg)"> <img class="owl-lazy" data-src="images/other_banners/ob_04.jpg" alt="ob_03"/> </div>
        </div>
      </div>
    </div>

    <hr width="96%">
        <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%" id="press">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9 col-lg-8">
            <h2 class="mb-1">Press</h2>
            <p class="text-center">See what media around the world is saying about Berd Vay’e </p>
            <hr width="96%">
            <p class="text-center">The celebration and restoration of vintage timepieces has captured the attention and hearts of watch lovers all over the world. With artistic integrity, Berd Vay’e sculptures encapsulate horological history and have amassed a cultural following across the globe.</p>
          </div>
        </div>
        <div class="defaultSlider pt-4 pb-0">
          <div class="gallerySilder">
            <div id="pressSilder" class="owl-carousel owl-theme ownCarousel parent-container">
              <div class="item"><a href="images/media/press/press_11_lg.jpg" class="mediaLink" title="Featured in Net Jets" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_11.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">Featured in Net Jets</small></a></div>
              <div class="item"><a href="images/media/press/press_08_lg.jpg" class="mediaLink" title="Singapore Tatler" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_08.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">Singapore Tatler</small></a></div>
              <div class="item"><a href="images/media/press/press_09_lg.jpg" class="mediaLink" title="Essential homme" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_09.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">Essential homme</small></a></div>
              <div class="item"><a href="images/media/press/press_05_lg.jpg" class="mediaLink" title="Robb Report" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_05.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">Robb Report</small></a></div>
              <div class="item"><a href="images/media/press/press_10_lg.jpg" class="mediaLink" title="Adon" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_10.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">Adon</small></a></div>
              <div class="item"><a href="images/media/press/press_12_lg.jpg" class="mediaLink" title="International Watch Magazine" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_12.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2"> International Watch Magazine</small></a></div>

              <div class="item"><a href="images/media/press/press_02_lg.jpg" class="mediaLink" title="BECAUSE MASS-PRODUCED SIMPLY WON'T DO" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_02.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">BECAUSE MASS-PRODUCED SIMPLY WON'T DO. </small></a></div>
              <div class="item"><a href="images/media/press/press_03_lg.jpg" class="mediaLink" title="RITZ CARLTON PUBLICATION" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_03.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">RITZ CARLTON PUBLICATION </small></a></div>
              <div class="item"><a href="images/media/press/press_04_lg.jpg" class="mediaLink" title="EPRO2 PUBLICATION" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_04.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">EPRO2 PUBLICATION </small></a></div>
              <div class="item"><a href="images/media/press/press_06_lg.jpg" class="mediaLink" title="THE HIGH-IMPACT HOROLOGICAL SCULPTURES OF BERD VAYE" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_06.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">THE HIGH-IMPACT HOROLOGICAL SCULPTURES OF BERD VAYE </small></a></div>
              <div class="item"><a href="images/media/press/press_07_lg.jpg" class="mediaLink" title="LOVE WATCHES AND ART? MEET BERD VAYE" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/press/press_07.jpg" alt="thumbnail"> <small class="text-uppercase  font-weight-bold d-block text-center pt-2 pb-2">LOVE WATCHES AND ART? MEET BERD VAYE </small></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr width="96%">
    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%" id="events">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9 col-lg-8">
            <h2 class="mb-1">Events</h2>
            <p class="text-center">Where will Berd Vay’e pop up next? Join the international journey of our modern art</p>
          </div>
        </div>
        <div class="defaultSlider pt-4 pb-0">
          <div class="gallerySilder">
            <div id="eventSilder" class="owl-carousel owl-theme ownCarousel parent-container">
              <div class="item"><a href="images/media/events/event_01_lg.jpg" class="mediaLink text-gray-500" title="Annie Charity Show" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/events/event_01.jpg" alt="thumbnail"> <small class="text-uppercase font-weight-bold d-block text-center pt-2 pb-2">Annie Charity Show</small></a></div>
              <div class="item"><a href="images/media/events/event_03_lg.jpg" class="mediaLink text-gray-500" title="Russia Launch Event" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/events/event_03.jpg" alt="thumbnail"> <small class="text-uppercase font-weight-bold d-block text-center pt-2 pb-2">Russia Launch Event</small></a></div>
              <div class="item"><a href="images/media/events/event_02_lg.jpg" class="mediaLink text-gray-500" title="Picasso Inspiration" data-rel="Lorem Ipsum is simply dummy text of the printing and typesetting industry."><img class="owl-lazy" data-src="images/media/events/event_02.jpg" alt="thumbnail"> <small class="text-uppercase font-weight-bold d-block text-center pt-2 pb-2">Picasso Inspiration</small></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr width="96%">
    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%" id="social_media">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9 col-lg-8">
            <h2 class="mb-1">Social media</h2>
            <p class="text-center">Gain behind-the-scenes access to our artistic journey to immortalize time</p>
          </div>
        </div>
        <div class="defaultSlider pt-4 pb-4">
          <div class="gallerySilder">
            <div id="instaSilder" class="owl-carousel owl-theme ownCarousel instaCarousel"></div>
          </div>
        </div>
      </div>
    </div>
    @include('contactInfo')

@endsection

@section ('jquery')
<script type="text/javascript" src="js/jquery-spectragram-master/spectragram.min.js"></script>
<script type="text/javascript" src="js/Magnific-Popup-master/dist/jquery.magnific-popup.min.js"></script>

<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

    $('.mediamenu').addClass('active');
    $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_03.jpg" alt="menu_banner_05">');

    jQuery.fn.spectragram.accessData = {
        accessToken: '2136707.12e2743.9576ae17af4e4ad4aebf6b72433c01fd'
    };

    $('#instaSilder').spectragram('getUserFeed', {
        complete: myCallbackFunc(),
        max: 10,
        size: "normal",
        wrapEachWith: '<div class="item">'
    });


    var loc = window.location.href + '';
    if (loc.indexOf('http://') == 0) {
        window.location.href = loc.replace('http://', 'https://');
    }

    function myCallbackFunc() {
        setTimeout(function() {
            console.log('LOAD');
            $('#instaSilder').owlCarousel({
             margin: 15,
        nav: true,
        autoWidth: true,
        dots: false,
			     navText : ["<img src='images/icons/icon_next.png' alt='next'>","<img src='images/icons/icon_prev.png' alt='prev'>"],
        responsive: {
                    0: {
                        items: 1,
                    },
                    768: {
                        items: 3,
                    },
                    1200: {
                        items: 3,
                    },
                    1369: {
                        items: 4,
                    }
                }
            });

        }, 2200);
    }

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

    $('#eventSilder').owlCarousel({
        margin: 15,
        nav: true,
						//		loop: true,
        autoWidth: true,
        dots: false,
			     navText : ["<img src='images/icons/icon_next.png' alt='next'>","<img src='images/icons/icon_prev.png' alt='prev'>"],
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 3,
            },
            1200: {
                items: 3,
            },
            1369: {
                items: 4,
            }
        }
    });

    $('#pressSilder').owlCarousel({
        margin: 15,
        nav: true,
        autoWidth: true,
        dots: false,
			     navText : ["<img src='images/icons/icon_next.png' alt='next'>","<img src='images/icons/icon_prev.png' alt='prev'>"],
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 3,
            },
            1200: {
                items: 3,
            },
            1369: {
                items: 4,
            }
        }
    });

				$('.parent-container').magnificPopup({
								delegate: 'a',
								type: 'image',
								image: {
												titleSrc: function(item) {
																return '<h2>'+item.el.attr('title') +'</h2> <p>'+ item.el.attr('data-rel')+ '</p>';
												}
								}
				});


			// HEADER MENU FUNCTION
			if ($(window).width() <= 768) {
								$(document).on('click', '.mobileMenu', function(e) {
											e.preventDefault();
											var data = $(this).next('.rightShow').slideToggle()
							});
			} else {
							$('.linkMenu').hover(function(e) {
											e.preventDefault();
											var data = $(this).next('.rightShow').html();
											$('.rightMenu').html(data);
							});
			}
});
</script>
@endsection