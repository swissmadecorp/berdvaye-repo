<!doctype html>
<html itemscope itemtype="http://schema.org/Organization" lang="en">
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GT-PLHLV53N"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'GT-PLHLV53N');
</script>

<meta charset="utf-8">
<meta name="HandheldFriendly" content="True" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta property="og:url" content="@yield('og_url')">
<meta property="og:site_name" content="Modern Art Vintage Parts">
<meta property="og:type" content="product">
<meta property="og:title" content="@yield('og_title')">
<meta property="og:description" content="@yield('og_desc')">
<meta property="og:image" content="@yield('og_image')">
<meta property="og:image:secure_url" content="@yield('og_image')">
<meta property="og:price:amount" content="@yield('og_price')">
<meta property="og:price:currency" content="USD">

<meta name="twitter:title" content="@yield('og_title')">
<meta name="twitter:description" content="@yield('og_desc')">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="@yield('og_image')">
<meta name="twitter:image:width" content="480">
<meta name="twitter:image:height" content="480">

<title>@yield('title')</title>

@stack('meta')
<link rel="icon" href="/images/favicon.ico"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet">
<link href="/css/berdvaye_global.css" rel="stylesheet">
<link href="/css/total-cart-pro.css" rel="stylesheet">
@stack('header')
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/jQuery-viewport-checker-master/dist/jquery.viewportchecker.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-validator/js/validator.min.js"></script>
<script type="text/javascript" src="/js/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
<meta name="google-site-verification" content="hwCyBKuLmbx_fUmImbIra7vM-Wwyg-6h6sMFUZDp_II" />
@vite(['resources/css/app.css','resources/js/app.js'])

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2264134867419924');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=2264134867419924&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

</head>

<body class="font-playfair">

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


      {{ $slot }}

      <div class="defaultContact animateRun bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
            <div class="container">
         <div class="row justify-content-center">
            <div class="col-12 col-sm-10">
               <div class="defaultContact-text text-center">
               <p>Masterminds in freezing time, Berd Vay’e offers an exclusive look into the secret inner-workings of watchmaking through its show-stopping, limited edition sculptures. Each and every gear, barrel, wheel, spring, escapement wheel, pinion, bridge, and hands is storied. Components are restored, polished and meticulously positioned, balanced in precious, crystal clear shatter-resistant Lucite&reg;.</p>
               </div>
            </div>
         </div>
            </div>
      </div>

    <div class="defaultFooter-links animateRun bg-gray-900" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="20%">
    <div class="grid grid-col-2 md:grid-col-4 sm:grid-col-5 justify-content-center">
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
          </div>
        </div>
      </div>
    </div>
  </footer>

      @include('modalform')
   </div>
    <!-- SCRIPT -->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>  -->

    @stack('footer')
    @stack('jquery')

    <script type="text/javascript">
        jQuery.noConflict();
        jQuery(document).ready(function($) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.pricingModal', function(e) {
                e.preventDefault();
                var productName = $(this).closest('.defaultBox-info').find('h2').html();
                var productSize = $(this).closest('.defaultBox-info').find('.productBox-size .active').html();
                $('#inputProduct').val(productName);
                $('#inputProductsize').val(productSize);
                $('#pricingModal').show();
            });

            $(document).on('click', '[data-dismiss="modal"]', function(e) {
                e.preventDefault();
                $('#pricingModal').hide();
                $('#pricingForm')[0].reset();
            });

            setTimeout(function() {
              $('.animateRun').viewportChecker();
            }, 1000);

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

        // CLICK FUNCTION
    $('.defaultimg').each(function() {
        $(this).click(function() {
            var $siblingImg = $(this).siblings();
            var checkRight = $(this).parent().hasClass('float-right');
            var checkLeft = $(this).parent().hasClass('float-left');
            if (!$(this).hasClass('rightImg') && checkLeft == true) {
                $siblingImg.removeClass('rightImg');
                $(this).addClass('rightImg');
            }
            if (!$(this).hasClass('leftImg') && checkRight == true) {
                $siblingImg.removeClass('leftImg');
                $(this).addClass('leftImg');
            }
        });

    });

    // HEADER MENU FUNCTION
    if ($(window).width() <= 768) {
        $(document).on('click', '.homemenu', function(e) {
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

    $(document).on('click', '.menuToggler', function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
        if (window.innerWidth < 767) {
            $('header nav').css('position','relative');
        } else {
            $('header nav').css('position','absolute');
        }
        $('header nav').slideToggle();
    });


    $(document).on('click', function(e) {
        var $trigger = $('.menuToggler');
        if (!$trigger.is(e.target) && $trigger.has(e.target).length === 0) {
            $('header nav').slideUp();
            $('.menuToggler').removeClass('active');
        }
    });

      $('#creationProcessLink').on('click', function(e) {
        e.preventDefault();
        const submenu = $('#creationProcessSubmenu');

        if (submenu.hasClass('max-h-0')) {
          // Open: animate to full height
          const scrollHeight = submenu.prop('scrollHeight') + "px";
          submenu.css('max-height', scrollHeight);
          submenu.removeClass('max-h-0');
        } else {
          // Close: animate back to 0
          submenu.css('max-height', '0');
          submenu.addClass('max-h-0');
        }
      });

    $(document).on('click', 'header nav, .homemenu', function(e) {
        e.stopPropagation();
    });
    $(document).on('keydown', '.trimSpace', function(e) {
        if (e.which === 32 && e.target.selectionStart === 0) {
            return false;
        }
    });

    // $('.add-to-cart').click( function (e) {
    //     _this = $(this);
    //     $.ajax({
    //         type: "post",
    //         data: {id: $(_this).attr('data-url')},
    //         url: "{{ route('add.to.cart') }}",
    //         success: function (result) {
    //             // if (isMobile())
    //                   document.location.href = '/cart';
    //             // else {
    //                 //if ($('.cart-anim').length>0) {
    //                   //$('#cart').html(result);
    //                   $('html,body').animate({ scrollTop: 0 }, 'slow');
    //                     //$('.cart-anim').addClass('move-to-cart')

    //                   // setTimeout(function(){ window.location.reload(); }, 500);
    //                 //}
    //             //}
    //         }
    //     })
    // })

    $('#pricingForm').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            //var url = "public/php/price.php";
            var url = '';
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function(data) {
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"> <a class="close" data-dismiss="alert" aria-hidden="true">&times;</a>' + messageText + '</div>';
                    if (messageAlert && messageText) {
                        $('#pricingForm').find('.messages').html(alertBox);
                        $('#pricingForm')[0].reset();
                    }
                }
            });
            return false;
        }
    });
});
</script>
<script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script><script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siqd59a939ceebbb806d1ec7837113020dcba195e898df1547e95ad2a18cb9c130f" defer></script>
</body>
</html>
