@extends('layouts.default')

@section ("title")
  Contact
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
  <div class="defaultBanner contactBanner">
    <div id="defaultBanner" class="owl-carousel owl-theme ownCarousel bg-darkgray">
      <div class="item">
        <div class="bannerBox" style="background-image:url(images/other_banners/ob_05.jpg)"> <img class="owl-lazy" data-src="images/other_banners/ob_05.jpg" alt="ob_05"/> </div>
      </div>
    </div>
  </div>
  <!-- <div class="defaultAddress mt-0 removeBorder animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
    <div class="container">
      <div class="row clearfix justify-content-center text-gray-400">
        <div class="col-12 text-center">
          <h2 class="pb-4 text-cream-500">Contact</h2>
        </div>
        <div class="col-12 col-sm-4 text-center text-gray-500">
          <div class="defaultAddress-info">
            <p class="text-gray-500"><b class="icon-dark"><img src="images/contact/icon_call.png" alt="icon_call"></b><strong class="text-uppercase font-weight-bold d-block">Call us:</strong>(833) 237-3829</p>
          </div>
        </div>
        <div class="col-12 col-sm-4 text-right text-gray-500">
          <div class="defaultAddress-info">
            <p class="text-gray-500"><b class="icon-dark"><img src="images/contact/icon_mail.png" alt="icon_mail"></b><strong class="text-uppercase font-weight-bold d-block">Mail us:</strong><a href= "mailto:info@berdvaye.com">info@berdvaye.com</a></p>
          </div>
        </div>
      </div>
    </div>
  </div> -->
  <div class="pt-4 pb-5">
    <div class="container animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%" style="background-image:url(images/contact/bg_01.jpg); background-size:cover;">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-4">
          <div class="defaultForm bg-black/80 rounded-lg text-gray-500">
            <h2 class="text-uppercase text-center">Get in Touch</h2>
            <small class="text-center d-block text-black-50">We'd love to hear from you!</small>
            <!-- <form role="form" id="contactForm" method="post" action="assets/php/contact.php"> -->
            <form id='contactForm'>
              <div class="messages"></div>
              <ul class="defaultForm-list pl-0">
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
                <li class="text-center pt-3">
                  <button type="submit" class="btn">Submit</button>
                </li>
              </ul>
            </form>
          </div>
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

  $('.contactmenu').addClass('active');
  $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_05.jpg" alt="menu_banner_05">');

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

    $('#contactForm').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            // var url = "assets/php/contact.php";

            $.ajax({
                type: "POST",
                url: "{{ route('ajax.inquiry') }}",
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
