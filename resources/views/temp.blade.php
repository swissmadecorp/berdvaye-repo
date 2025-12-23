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
      @include ('nav')
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
      <span class="flex justify-center mb-10 uppercase text-2xl text-gray-500 uppercase">Featured In</span>
      <div class="container-fluid bg-cream-300 p-1.5 rounded-[78px]">
        <div class="row clearfix justify-content-center">

            <div class="flex justify-center gap-8 rounded-md">
              <a target="_blank" href="https://www.nytimes.com/2021/09/26/fashion/watches-lucite-sculptures-bard-vaye.html"><img src="assets/nyt.jpg" alt=""></a>
              <a target="_blank" href="https://www.forbes.com/sites/nancyolson/2021/06/11/berd-vaye-artfully-melds-the-past-and-the-future/"><img src="assets/forbes.jpg"></a>
              <a target="_blank" href="https://www.hodinkee.com/articles/horological-sculptures-berd-vaye"><img src="assets/hdkee.jpg"></a>
              <a target="_blank" href="https://usa.watchpro.com/tag/berd-vaye/"><img src="assets/watchpro.jpg"></a>
            </div>

        </div>
      </div>
    </div>


    <div class="defaultIntro animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-8 col-md-6">
            <p class="text-center">Dive into the secret inner-workings of the history of watchmaking through show-stopping, limited edition sculptures.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="defaultSlider animateRun text-red-400 " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
      <div class="container-fluid bg-black">
        <div class="gallerySilder">
          <div id="gallerySilder" class="owl-carousel owl-theme ownCarousel">
            <div class="item"><a href="product/gravity" class="galleryLink"><img src="assets/products/cbl-gr.png" alt="thumbnail"> <span>Gravity</span></a></div>
            <div class="item"><a href="product/galaxy" class="galleryLink"><img src="assets/products/cbl-ga.png" alt="thumbnail"> <span>Galaxy</span></a></div>
            <div class="item"><a href="product/hallucination" class="galleryLink"><img src="assets/products/cbl-ha.png" alt="thumbnail"> <span>Hallucination</span></a></div>
            <div class="item"><a href="product/lost-in-time" class="galleryLink"><img src="assets/products/sks.png" alt="thumbnail"> <span>Lost in Time</span></a></div>
            <div class="item"><a href="product/horosphere" class="galleryLink"><img src="assets/products/sps.png" alt="thumbnail"> <span>Horosphere</span></a></div>
            <div class="item"><a class="galleryLink" href="product/time-squared"><img src="assets/products/cbs.png" alt="Time Square"> <span>Time Squared</span></a></div>
            <!-- <div class="item"><a class="galleryLink" href="product/time-framed"><img src="assets/products/cbl-ha.png" alt="Time Framed"> <span>Time Framed</span></a></div> -->
            <div class="item"><a href="product/grand-master-king" class="galleryLink"><img src="assets/products/king.png" alt="Grand Master King"> <span>Grand Master King</span></a></div>
            <div class="item"><a href="product/queen-of-parts" class="galleryLink"><img src="assets/products/queen.png" alt="Queen of Parts"> <span>Queen of Parts</span></a></div>
            <div class="item"><a href="product/rook" class="galleryLink"><img src="assets/products/rook.png" alt="Rook"> <span>Rook</span></a></div>
            <div class="item"><a href="product/bishop" class="galleryLink"><img src="assets/products/bishop.png" alt="Bishop"> <span>Bishop</span></a></div>
            <div class="item"><a href="product/knight" class="galleryLink"><img src="assets/products/knight.png" alt="Knight"> <span>Knight</span></a></div>
            <div class="item"><a class="galleryLink" href="product/passage-through-time"><img src="assets/products/hgs.png" alt="Passage Through Time"> <span>Passage Through Time</span></a></div>
            <div class="item"><a href="product/pawn" class="galleryLink"><img src="assets/products/pawn.png" alt="Pawn"> <span>Pawn</span></a></div>
            <div class="item"><a href="product/grenade" class="galleryLink"><img src="assets/products/grd.png" alt="grenade"> <span>Grenade</span></a></div>
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
          <!--     <div class="col-12 col-sm-3 col-md-2 text-center"> <a href="lost_in_time" class="btn mt-3">View Collection</a> </div>-->
        </div>
      </div>
    </div>
<div
  x-data="{
    bg: 'assets/banner-1.jpg',
    images: ['assets/banner-1.jpg','assets/banner-2.jpg','assets/banner-3.jpg','assets/banner-4.jpg']
  }"
  x-init="images.forEach(src => { const img = new Image(); img.src = src })"
  class="flex h-[75vh] w-full relative overflow-hidden"
>
  <!-- Background layers -->
  <template x-for="(image, index) in images" :key="index">
    <div
      class="absolute inset-0 bg-cover bg-center transition-opacity duration-700"
      :style="`background-image: url(${image})`"
      :class="bg === image ? 'opacity-100' : 'opacity-0'"
    ></div>
  </template>

  <!-- Grid overlay -->
  <!-- Mobile: 1 column (vertical), md+: 4 columns -->
  <div class="grid grid-cols-1 md:grid-cols-4 w-full h-full relative z-10">

    <!-- ITEM 1 -->
    <div
      @mouseenter="bg='assets/banner-1.jpg'"
      class="relative flex flex-col justify-end text-black cursor-pointer overflow-hidden group border-b md:border-b-0 md:border-r border-white/40 h-full"
    >
      <!-- Original Text -->
      <div class="text-2xl transition-opacity duration-400 group-hover:opacity-0 mb-2 z-10 flex flex-col px-4">
        <span class="block">Deconstructing</span>
        <span class="block">Time</span>
      </div>

      <!-- Sliding Panel -->
      <div class="absolute inset-x-0 bottom-0 h-1/4 bg-cream-400/50 flex flex-col justify-center text-xl text-black transform translate-y-full transition-transform duration-400 group-hover:translate-y-0 px-4">
        <span class="block">Deconstructing</span>
        <span class="block">Time</span>
      </div>
    </div>

    <!-- ITEM 2 -->
    <div
      @mouseenter="bg='assets/banner-2.jpg'"
      class="relative flex flex-col justify-end text-black cursor-pointer overflow-hidden group border-b md:border-b-0 md:border-r border-white/40 h-full"
    >
      <div class="text-2xl transition-opacity duration-400 group-hover:opacity-0 mb-2 z-10 flex flex-col px-4">
        <span class="block">Designing</span>
        <span class="block">& Preparation</span>
      </div>
      <div class="absolute inset-x-0 bottom-0 h-1/4 bg-cream-400/50 flex flex-col justify-center text-xl text-black transform translate-y-full transition-transform duration-400 group-hover:translate-y-0 px-4">
        <span class="block">Designing</span>
        <span class="block">& Preparation</span>
      </div>
    </div>

    <!-- ITEM 3 -->
    <div
      @mouseenter="bg='assets/banner-3.jpg'"
      class="relative flex flex-col justify-end text-black cursor-pointer overflow-hidden group border-b md:border-b-0 md:border-r border-white/40 h-full"
    >
      <div class="text-2xl transition-opacity duration-400 group-hover:opacity-0 mb-2 z-10 flex flex-col px-4">
        <span class="block">Forming</span>
      </div>
      <div class="absolute inset-x-0 bottom-0 h-1/4 bg-cream-400/50 flex flex-col justify-center text-xl text-black transform translate-y-full transition-transform duration-400 group-hover:translate-y-0 px-4">
        <span class="block">Forming</span>
      </div>
    </div>

    <!-- ITEM 4 -->
    <div
      @mouseenter="bg='assets/banner-4.jpg'"
      class="relative flex flex-col justify-end text-black cursor-pointer overflow-hidden group h-full"
    >
      <div class="text-2xl transition-opacity duration-400 group-hover:opacity-0 mb-2 z-10 flex flex-col px-4">
        <span class="block">Finishing</span>
      </div>
      <div class="absolute inset-x-0 bottom-0 h-1/4 bg-cream-400/50 flex flex-col justify-center text-xl text-black transform translate-y-full transition-transform duration-400 group-hover:translate-y-0 px-4">
        <span class="block">Finishing</span>
      </div>
    </div>

  </div>
</div>




    <div class="defaultContact animateRun mt-10" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="defaultContact-bg" style="background-image:url(images/contact/bg_00.jpg)">
        <div class="container">
          <div class="row align-items-end">
            <div class="col-12 col-sm-6 col-md-5 col-lg-4">
              <div class="defaultForm bg-gray-400">
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
                        <input type="email" class="form-control" id="exampleInputEmail" name="email" placeholder="Email ID" required>
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
                <li><a href="/product/gravity" class="text-gray-500 hover:text-red-600">Gravity</a></li>
                <li><a href="/product/galaxy" class="text-gray-500 hover:text-red-600">Galaxy</a></li>
                <li><a href="/product/hallucination" class="text-gray-500 hover:text-red-600">Hallucination</a></li>
                <li><a href="/product/horosphere" class="text-gray-500 hover:text-red-600">Horosphere</a></li>
                <li><a href="/product/time-squared" class="text-gray-500 hover:text-red-600">Time Squared</a></li>
                <li><a href="/product/lost-in-time" class="text-gray-500 hover:text-red-600">Lost in Time</a></li>
                <li><a href="/product/passage-through-time" class="text-gray-500 hover:text-red-600">Passage Through Time</a></li>
                <li><a href="/product/time-framed" class="text-gray-500 hover:text-red-600">Time Framed</a></li>
                <li><a href="/product/grenade" class="text-gray-500 hover:text-red-600">Grenade</a></li>
              </ul>
            </div>
          </div>

          <div class="">
            <div class="defaultContact-links">
              <h3>Chess Collection</h3>
              <ul>
                <li><a href="/product/grand-master-king" class="text-gray-500 hover:text-red-600">Grand Master King</a></li>
                <li><a href="/product/queen-of-parts" class="text-gray-500 hover:text-red-600">Queen Of Parts</a></li>
                <li><a href="/product/rook" class="text-gray-500 hover:text-red-600">Rook</a></li>
                <li><a href="/product/bishop" class="text-gray-500 hover:text-red-600">Bishop</a></li>
                <li><a href="/product/knight" class="text-gray-500 hover:text-red-600">Knight</a></li>
                <li><a href="/product/pawn" class="text-gray-500 hover:text-red-600">Pawn</a></li>
              </ul>
            </div>
          </div>
          <div class="">
            <div class="defaultContact-links">
              <h3>Creation Process</h3>
              <ul>
                <li><a href="/process-deconstruction" class="text-gray-500 hover:text-red-600">Deconstructing Time</a></li>
                <li><a href="/process-design" class="text-gray-500 hover:text-red-600">Design & Preparation</a></li>
                <li><a href="/process-finishing" class="text-gray-500 hover:text-red-600">Forming & Finishing</a></li>
              </ul>
              <h3 class="mt-2"><a href="/dealers" class="text-gray-500 hover:text-red-600">Authorized dealers</a></h3>
            </div>
          </div>
          <div class="">
            <div class="defaultContact-links">
              <h3>About Berdvaye</h3>
              <ul>
                <li><a href="/about" class="text-gray-500 hover:text-red-600">Our Story</a></li>
                <li><a href="/about#authenticity" class="text-gray-500 hover:text-red-600">Authenticity</a></li>
              </ul>
              <h3 class="mt-2"><a href="/contact" class="text-gray-500 hover:text-red-600">Contact Us</a></h3>
            </div>
          </div>
          <div class="">
            <div class="defaultContact-links">
              <h3>Media</h3>
              <ul>
                <li><a href="/media#press" class="text-gray-500 hover:text-red-600">Press</a></li>
                <li><a href="/media#events" class="text-gray-500 hover:text-red-600">Events</a></li>
                <li><a href="/media#social_media" class="text-gray-500 hover:text-red-600">Social Media</a></li>
              </ul>
            </div>
          </div>
        </div>
      <div class="defaultAddress">
        <div class="container">
          <div class="row">
            <div class="col-12 col-sm-4 text-left">
              <div class="defaultAddress-info">
                <p><b><img src="images/contact/icon_insta.png" alt="icon_loc"></b><a href="https://www.instagram.com/berdvaye/" target="_blank">@berdvaye</a></p>
              </div>
            </div>
            <div class="col-12 col-sm-4 text-center">
              <div class="defaultAddress-info">
                <p><b><img src="images/contact/icon_call.png" alt="icon_call"></b>(833) BERD VAYE</p>
              </div>
            </div>
            <div class="col-12 col-sm-4 text-right">
              <div class="defaultAddress-info">
                <p><b><img src="images/contact/icon_mail.png" alt="icon_mail"></b>info@berdvaye.com</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-12"><small>&copy;copyright Berd Vay’e 2019</small></div>
        <div class="col-md-4 col-12">
          <div class="text-uppercase text-center"><a href="/terms-and-conditions">Terms & Conditions</a> | <a href="privacy-policy">Privacy Policy</a></div>
        </div>
        <div class="col-md-4 col-12">
          <div class="socialLinks"><a href="https://www.instagram.com/berdvaye/" target="_blank"><img src="images/contact/icon_instagram.png" alt="icon_in"></a><a href="https://www.facebook.com/berdvaye/" target="_blank"><img src="images/contact/icon_facebook.png" alt="icon_facebook"></a></div>
        </div>
      </div>
    </div>
  </footer>
</div>
@endsection

@section ('jquery')
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

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

    $('.homemenu').addClass('active');
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
@endsection