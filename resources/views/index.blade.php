@extends('layouts.default')

@section ("title")
  Berdvaye Inc.: Modern Art with Vintage Parts
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
  <!-- https://www.producermichael.com/ -->
  <main class="bg-darkgray">
    <div id="mainbanners" class="defaultBanner hidden">
      <h2>Collection 2019</h2>
      <div id="defaultBanner" class="bg-darkgray owl-carousel owl-theme ownCarousel">
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/hero_banner/hb_00.jpg)"> <img class="owl-lazy" data-src="images/hero_banner/hb_00.jpg" alt="hb_00"/> </div>
          <div class="bannerBox-info bg-gray-200/88">
            <h3>Modern<small>Art Vintage Parts</small></h3>
          </div>
        </div>
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/hero_banner/hb_01.jpg)"> <img class="owl-lazy" data-src="images/hero_banner/hb_01.jpg" alt="hb_01"/> </div>
          <div class="bannerBox-info bg-gray-200/88">
            <h3>ETERNALIZE<small>the art of timekeeping</small></h3>
          </div>
        </div>
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/hero_banner/hb_02.jpg)"> <img class="owl-lazy" data-src="images/hero_banner/hb_02.jpg" alt="hb_02"/> </div>
          <div class="bannerBox-info bg-gray-200/88">
            <h3>IMMORTALIZE<small>time through artistic mastery</small></h3>
          </div>
        </div>
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/hero_banner/hb_03.jpg)"> <img class="owl-lazy" data-src="images/hero_banner/hb_03.jpg" alt="hb_03"/> </div>
          <div class="bannerBox-info bg-gray-200/88">
            <h3>LOOK INSIDE<small>the secrets of watchmaking</small></h3>
          </div>
        </div>
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/hero_banner/hb_04.jpg)"> <img class="owl-lazy" data-src="images/hero_banner/hb_04.jpg" alt="hb_04"/> </div>
          <div class="bannerBox-info bg-gray-200/88">
            <h3>DECONSTRUCT<small>time with exceptional discipline</small></h3>
          </div>
        </div>
      </div>
    </div>

    <video width="100%" id="video" poster="video/video_01.jpg" autoplay muted playsinline >
        <source src="video/BERDVAYE_1_insta.mp4" type="video/mp4">
        Your browser does not support HTML5 video. </video>
        <!-- <div class="playpause"></div> -->
    </div>

    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
      <span class="flex justify-center mb-2 text-2xl text-gray-300 uppercase">Featured In</span>
      <div class="bg-cream-300 container-fluid flex justify-center p-1.5 rounded-[78px] overflow-hidden">
        <div class="flex gap-8 justify-center flex-1 max-w-full">
          <a target="_blank" href="https://www.nytimes.com/2021/09/26/fashion/watches-lucite-sculptures-bard-vaye.html">
            <img src="assets/nyt.jpg" class="h-auto max-w-[20%]">
          </a>
          <a target="_blank" href="https://www.forbes.com/sites/nancyolson/2021/06/11/berd-vaye-artfully-melds-the-past-and-the-future/">
            <img src="assets/forbes.jpg" class="h-auto max-w-[20%]">
          </a>
          <a target="_blank" href="https://www.hodinkee.com/articles/horological-sculptures-berd-vaye">
            <img src="assets/hdkee.jpg" class="h-auto max-w-[20%]">
          </a>
          <a target="_blank" href="https://usa.watchpro.com/tag/berd-vaye/">
            <img src="assets/watchpro.jpg" class="h-auto max-w-[20%]">
          </a>
        </div>
      </div>
    </div>


    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-8 col-md-6">
            <p class="text-center text-gray-300">Dive into the secret inner-workings of the history of watchmaking through show-stopping, limited edition sculptures.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="defaultSlider animateRun text-cream-300 " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
      <div class="container-fluid bg-black">
        <div class="gallerySilder">
          <div id="gallerySilder" class="owl-carousel owl-theme ownCarousel">
            <div class="item"><a href="/sculptures/gravity" class="galleryLink"><img src="assets/products/cbl-gr.jpg" alt="thumbnail"> <span>Gravity</span></a></div>
            <div class="item"><a href="/sculptures/galaxy" class="galleryLink"><img src="assets/products/cbl-ga.jpg" alt="thumbnail"> <span>Galaxy</span></a></div>
            <div class="item"><a href="/sculptures/hallucination" class="galleryLink"><img src="assets/products/cbl-ha.jpg" alt="thumbnail"> <span>Hallucination</span></a></div>
            <div class="item"><a href="/sculptures/lost-in-time" class="galleryLink"><img src="assets/products/sks.jpg" alt="thumbnail"> <span>Lost in Time</span></a></div>
            <div class="item"><a href="/sculptures/horosphere" class="galleryLink"><img src="assets/products/spl.jpg" alt="thumbnail"> <span>Horosphere</span></a></div>
            <div class="item"><a class="galleryLink" href="/sculptures/time-squared"><img src="assets/products/cbs.jpg" alt="Time Square"> <span>Time Squared</span></a></div>
            <!-- <div class="item"><a class="galleryLink" href="/sculptures/time-framed"><img src="assets/products/cbl-ha.jpg" alt="Time Framed"> <span>Time Framed</span></a></div> -->
            <div class="item"><a href="/sculptures/grand-master-king" class="galleryLink"><img src="assets/products/king.jpg" alt="Grand Master King"> <span>Grand Master King</span></a></div>
            <div class="item"><a href="/sculptures/queen-of-parts" class="galleryLink"><img src="assets/products/queen.jpg" alt="Queen of Parts"> <span>Queen of Parts</span></a></div>
            <div class="item"><a href="/sculptures/rook" class="galleryLink"><img src="assets/products/rook.jpg" alt="Rook"> <span>Rook</span></a></div>
            <div class="item"><a href="/sculptures/bishop" class="galleryLink"><img src="assets/products/bishop.jpg" alt="Bishop"> <span>Bishop</span></a></div>
            <div class="item"><a href="/sculptures/knight" class="galleryLink"><img src="assets/products/knight.jpg" alt="Knight"> <span>Knight</span></a></div>
            <div class="item"><a class="galleryLink" href="/sculptures/passage-through-time"><img src="assets/products/hgs.jpg" alt="Passage Through Time"> <span>Passage Through Time</span></a></div>
            <div class="item"><a href="/sculptures/pawn" class="galleryLink"><img src="assets/products/pawn.jpg" alt="Pawn"> <span>Pawn</span></a></div>
            <!-- <div class="item"><a href="/sculptures/grenade" class="galleryLink"><img src="assets/products/grd.jpg" alt="grenade"> <span>Grenade</span></a></div> -->
          </div>
        </div>
      </div>
    </div>
    <div class="defaultSlider-text animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-6 col-lg-6">
            <p class="text-center">Berd Vay’e breathes new life into the appreciation of horology with distinctive, one-of-a-kind conversation pieces like no contemporary art you’ve ever seen before.</p>
          </div>
          <!--     <div class="col-12 col-sm-3 col-md-2 text-center text-gray-300"> <a href="lost_in_time" class="btn mt-3">View Collection</a> </div>-->
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/homepage/deconstructing_img_00.jpg" alt="deconstructing_img_00"/></div>
              <div class="defaultimg leftImg"><img src="images/homepage/deconstructing_img_01.jpg" alt="deconstructing_img_01"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Deconstructing<span>Time into Art</span></h2>
              <p>Expert curators fastidiously source 50-100 year-old timepieces and parts with a true passion for horology – from collectible 19th century backwind pocket watches, to remnants of 1900s Americana in the form of stop clocks once belonging to railroad conductors.</p>
              <a href="process-deconstruction" class="defaultLink text-red-400">Explore the Deconstruction</a> </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 order-12">
            <div class="defaultBox-img float-left">
              <div class="defaultimg"><img src="images/homepage/design_img_00.jpg" alt="design_img_00"/></div>
              <div class="defaultimg rightImg"><img src="images/homepage/design_img_01.jpg" alt="design_img_01"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6 order-1">
            <div class="defaultBox-info float-right">
              <h2>Design & <span>Preparation</span></h2>
              <p>Each and every gear, barrel, wheel, spring, escapement wheel, pinion, bridge, and hands is storied. Components are restored, polished and meticulously positioned, balanced in precious, crystal clear shatter-resistant Lucite&reg;</p>
              <a href="process-design" class="defaultLink text-red-400">View the process</a> </div>
          </div>
        </div>
      </div>
      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6">
            <div class="defaultBox-img float-right">
              <div class="defaultimg"><img src="images/homepage/forming_img_00.jpg" alt="forming_img_00"/></div>
              <div class="defaultimg leftImg"><img src="images/homepage/forming_img_01.jpg" alt="forming_img_01"/></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info">
              <h2>Forming <span>& Finishing</span></h2>
              <p>Each Berd Vay’e piece is casted with 8-10 layers over a 24 hour period. The sculpture is then baked, carefully protecting it from the formation of air bubbles or imperfections thus resulting in a seamless, crystal clear sculpture. </p>
              <a href="process-finishing" class="defaultLink text-red-400">View the Final Stages</a> </div>
          </div>
        </div>
      </div>

      <div class="defaultBox animateRun mb-2 bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="p-3 rounded-lg">
            <video width="100%" height="100%" preload="auto" autoplay muted playsinline>
              <source src="video/BERDVAYE_2_insta.mp4" type="video/mp4">
              Your browser does not support HTML5 video. </video>
          </div>
        </div>
      </div>
    </div>

    <div class="defaultContact animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="defaultContact-bg" style="background-image:url(images/contact/bg_00.jpg)">
        <div class="container">
          <div class="row align-items-end">
            <div class="col-12 col-sm-6 col-md-5 col-lg-4">
              <div class="defaultForm bg-black/80 rounded-lg">
                <h2 class="text-uppercase text-gray-300">Get in Touch</h2>
                <small class="text-gray-300">We’d love to hear from you!</small>
                <!-- <form role="form" id="contactForm" method="post" action="php/contact.php"> -->
                <form method="POST" action="{{route('ajax.inquiry')}}" accept-charset="UTF-8" id="contactForm">
                  @csrf
                  <div class="messages"></div>
                  <ul class="defaultForm-list">
                    <li>
                      <div class="form-group">
                        <input type="text" class="form-control trimSpace" id="exampleInputFullName" name="fullname" placeholder="Name" pattern="^[a-zA-Z0-9\-\/!@#$%^&*(),.?:{}_|<>+][\sa-zA-Z0-9\-\/!@#$%^&*(),.?:{}_|<>+]*" data-pattern-error="Use only letters or numbers as a first character" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </li>
                    <li>
                      <div class="form-group">
                        <input type="email" class="form-control" id="exampleInputEmail" name="email" placeholder="Email" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </li>
                    <li>
                      <div class="form-group">
                        <input type="number" class="form-control" id="exampleInputMobile" name="mobile" placeholder="Phone Number" required>
                        <div class="help-block with-errors"></div>
                      </div>
                    </li>
                    <li>
                      <div class="form-group">
                        <textarea class="form-control" id="exampleInputComment" name="message" placeholder="Your Message" required></textarea>
                        <div class="help-block with-errors"></div>
                      </div>
                    </li>
                    <li class="text-left">
                      <button type="submit" class="btn">Submit</button>
                    </li>
                  </ul>
                <!-- </form> -->
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-5 col-lg-4"> </div>
          <div class="col-12 col-sm-6 col-md-7 col-lg-8">
            <div class="defaultContact-text animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
              <p>Masterminds in freezing time, Berd Vay’e offers an exclusive look into the secret inner-workings of watchmaking through its show-stopping, limited edition sculptures. Each and every gear, barrel, wheel, spring, escapement wheel, pinion, bridge, and hands is storied. Components are restored, polished and meticulously positioned, balanced in precious, crystal clear shatter-resistant Lucite&reg;.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="defaultFooter-links animateRun bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
    <div class="grid grid-col-2 md:grid-col-3 sm:grid-col-5 justify-content-center">
          <div class="">
            <div class="defaultContact-links">
              <h3>Collections</h3>
              <ul>
                <li><a href="/sculptures/gravity" class="text-gray-300 hover:text-red-300">Gravity</a></li>
                <li><a href="/sculptures/galaxy" class="text-gray-300 hover:text-red-300">Galaxy</a></li>
                <li><a href="/sculptures/hallucination" class="text-gray-300 hover:text-red-300">Hallucination</a></li>
                <li><a href="/sculptures/horosphere" class="text-gray-300 hover:text-red-300">Horosphere</a></li>
                <li><a href="/sculptures/time-squared" class="text-gray-300 hover:text-red-300">Time Squared</a></li>
                <li><a href="/sculptures/lost-in-time" class="text-gray-300 hover:text-red-300">Lost in Time</a></li>
                <li><a href="/sculptures/passage-through-time" class="text-gray-300 hover:text-red-300">Passage Through Time</a></li>
                <!-- <li><a href="/sculptures/time-framed" class="text-gray-300 hover:text-red-300">Time Framed</a></li> -->
                <!-- <li><a href="/sculptures/grenade" class="text-gray-300 hover:text-red-300">Grenade</a></li> -->
              </ul>
            </div>
          </div>

          <div class="">
            <div class="defaultContact-links">
              <h3>Chess Collection</h3>
              <ul>
                <li><a href="/sculptures/grand-master-king" class="text-gray-300 hover:text-red-300">Grand Master King</a></li>
                <li><a href="/sculptures/queen-of-parts" class="text-gray-300 hover:text-red-300">Queen Of Parts</a></li>
                <li><a href="/sculptures/rook" class="text-gray-300 hover:text-red-300">Rook</a></li>
                <li><a href="/sculptures/bishop" class="text-gray-300 hover:text-red-300">Bishop</a></li>
                <li><a href="/sculptures/knight" class="text-gray-300 hover:text-red-300">Knight</a></li>
                <li><a href="/sculptures/pawn" class="text-gray-300 hover:text-red-300">Pawn</a></li>
              </ul>
            </div>
          </div>
          <div class="">
            <div class="defaultContact-links">
              <h3>Creation Process</h3>
              <ul>
                <li><a href="/process-deconstruction" class="text-gray-300 hover:text-red-300">Deconstructing Time</a></li>
                <li><a href="/process-design" class="text-gray-300 hover:text-red-300">Design & Preparation</a></li>
                <li><a href="/process-finishing" class="text-gray-300 hover:text-red-300">Forming & Finishing</a></li>
              </ul>
              <h3 class="mt-2"><a href="/dealers" class="text-gray-300 hover:text-red-300">Authorized dealers</a></h3>
            </div>
          </div>
          <div class="">
            <div class="defaultContact-links">
              <h3>About Berdvaye</h3>
              <ul>
                <li><a href="/about" class="text-gray-300 hover:text-red-300">Our Story</a></li>
                <li><a href="/about#authenticity" class="text-gray-300 hover:text-red-300">Authenticity</a></li>
                <li><a href="/terms-and-conditions" class="text-gray-300 hover:text-red-600">Terms & Conditions</a></li>
              </ul>
              <h3 class="mt-2"><a href="/contact" class="text-gray-300 hover:text-red-300">Contact Us</a></h3>
            </div>
          </div>
          <div>
            <div>
              <img src="/assets/berdvaye-logo.svg" class="mx-auto h-auto" />

            </div>
          </div>
        </div>
        <div class="defaultAddress bg-gray-900 py-4">
            <div class="flex flex-col sm:flex-row items-center sm:items-start justify-between max-w-6xl mx-auto px-4 gap-4 sm:gap-0">

              <!-- Left: Social links together -->
              <div class="flex items-center gap-4">
                <a href="https://www.facebook.com/berdvaye/" target="_blank">
                  <img src="/images/contact/facebook-2870.svg" class="w-5 h-5" alt="Facebook">
                </a>
                <a href="https://www.instagram.com/berdvaye/" target="_blank" class="flex items-center gap-1 text-gray-300">
                  <img src="/images/contact/instagram-logo-8869.svg" class="w-5 h-5" alt="Instagram">berdvaye
                </a>
              </div>

              <!-- Center: Phone -->
              <div class="flex items-center gap-2 justify-center">
                <img src="/images/contact/phone.svg" class="w-5 h-5" alt="Call">
                <span class="text-gray-300">(833) 237-3829</span>
              </div>

              <!-- Right: Email -->
              <div class="flex items-center gap-2 justify-center sm:justify-end">
                <a href="mailto:info@berdvaye.com" class="text-gray-300">info@berdvaye.com</a>
              </div>

            </div>
        </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-12"><small>&copy;copyright Berd Vay’e 2019 - {{ date('Y') }}</small></div>
        <div class="col-md-4 col-12">
          <div class="text-uppercase text-center"><a href="/terms-and-conditions">Terms & Conditions</a> | <a href="privacy-policy">Privacy Policy</a></div>
        </div>
        <div class="col-md-4 col-12">
          <div class="socialLinks">
              <!-- <a href="https://www.instagram.com/berdvaye/" target="_blank"><img src="images/contact/icon_instagram.png" alt="icon_in"></a> -->
              <!-- <a href="https://www.facebook.com/berdvaye/" target="_blank"><img src="images/contact/icon_facebook.png" alt="icon_facebook"></a> -->
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>
@endsection

@push ('jquery')
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

    $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_01.jpg" alt="menu_banner_04">');
    setTimeout( function(){
      $('.animateRun').viewportChecker();
      },1000);


    var owl = $('#defaultBanner');
    owl.owlCarousel({
        margin: 0,
        mouseDrag: false,
        nav: false,
        navSpeed: 100,
        items: 1,
        loop: true,
        dots: true,
        lazyLoad: true,
        autoplay: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        autoplayHoverPause: false
    });

$('.owl-dots > .owl-dot').on('click', function () {
	setTimeout(function(){
     $('#defaultBanner').trigger('play.owl.autoplay');

		 }, 1200);

});

    // $('.homemenu').addClass('active');
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

    // owl.on('translated.owl.carousel', function(event) {
    //     //console.log(event.item.index, event.page.count);
    //     if (event.item.index == (event.page.count - 1)) {
    //         if (round < 1) {
    //             round++
    //             console.log(round);
    //         } else {
    //             owl.trigger('stop.owl.autoplay');
    //             var owlData = owl.data('owl.carousel');
    //             owlData.settings.autoplay = false;
    //             owlData.options.autoplay = false;
    //             owl.trigger('refresh.owl.carousel');
    //         }
    //     }
    // });

    $('#gallerySilder').owlCarousel({
        margin: 10,
        items: 3,
        nav: true,
        autoWidth: false,
        dots: false,
        responsive: {
            0: {
                items: 2,
								nav: true,
            },
            768: {
                items: 3,
            },
            1200: {
                items: 3,
            }

        }
    });

// owl.on('mousewheel', '.owl-stage', function (e) {
//     if (e.deltaY>0) {
//         owl.trigger('next.owl');
//     } else {
//         owl.trigger('prev.owl');
//     }
//     e.preventDefault();
// });

    $('#contactForm').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            //var url = "php/contact.php";
            var url = $('form').attr('action');
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