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
@yield('meta')
<link rel="icon" href="/images/favicon.ico"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet">
<link href="/css/berdvaye_global.css" rel="stylesheet">
<link href="/css/total-cart-pro.css" rel="stylesheet">
@yield('header')
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript" src="/js/jQuery-viewport-checker-master/dist/jquery.viewportchecker.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-validator/js/validator.min.js"></script>
<script type="text/javascript" src="/js/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

<body>

    @yield('content')

    <!-- SCRIPT -->
    <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>  -->

    @yield('footer')

    <script type="text/javascript">
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            const video = document.getElementById('video');
            const image = document.getElementById('mainbanners');
            // const container = document.getElementById('videoContainer');

            if (video) {
                video.addEventListener('ended', function() {
                    // container.style.display = 'none'; // Hides the video container
                    video.style.display = 'none'; // Hides the video element
                    $('#mainbanners').removeClass('hidden');   // Shows the image
                }, false);
            }

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

    $(document).on('click', 'header nav, .mobileMenu', function(e) {
        e.stopPropagation();
    });
    $(document).on('keydown', '.trimSpace', function(e) {
        if (e.which === 32 && e.target.selectionStart === 0) {
            return false;
        }
    });

    $('.add-to-cart').click( function (e) {
        _this = $(this);
        $.ajax({
            type: "post",
            data: {id: $(_this).attr('data-url')},
            url: "{{ route('add.to.cart') }}",
            success: function (result) {
                // if (isMobile())
                      document.location.href = '/cart';
                // else {
                    //if ($('.cart-anim').length>0) {
                      //$('#cart').html(result);
                      $('html,body').animate({ scrollTop: 0 }, 'slow');
                        //$('.cart-anim').addClass('move-to-cart')

                      // setTimeout(function(){ window.location.reload(); }, 500);
                    //}
                //}
            }
        })
    })

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

@stack('jquery')
</body>
</html>
