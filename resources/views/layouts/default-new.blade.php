<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="author" content="Ephraim Babekov">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Berd Vaye Inc.</title>

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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet"> -->

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/images/favicons/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicons/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicons/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicons/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicons/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicons/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicons/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicons/apple-touch-icon-152x152.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon-180x180.png" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/OwlCarousel2-2.3.4/dist/owl.carousel.min.js"></script>

    <link href="/js/mmenu/mmenu.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <link href="/css/berdvaye-new.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> -->
    <!-- <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script> -->
    <!-- <script src="/js/fileupload/jquery.ui.widget.js"></script> -->

    @vite(['resources/css/app.css','resources/js/app.js'])
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .mm-menu.mm-left { width: 250px !important; }
        .mm-navbar__title {background: #870400}

        .dropdown-content { display: none; position: absolute; background-color: #ffffff; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 1; } .dropdown:hover .dropdown-content { display: block; } .dropdown-content a { color: black; padding: 12px 16px; text-decoration: none; display: block; } .dropdown-content a:hover { background-color: #ddd; }
    </style>

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
    <script src="/js/mmenu/mmenu.js"></script>

    <div id="main" class="dark:bg-black text-white">
        @include ('layouts.header')
        @yield('content')
        @include('layouts.footer')

    </div>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#mobile-menu").mmenu();
            var API = $("#mobile-menu").data('mmenu');

            $("#menu-button").click(function() {
                API.open();
            })

            darkModeSetting();

            function darkModeSetting() {
                isDark = localStorage.getItem('darkMode');
                if (isDark == 1) {
                    $('html').addClass('dark');
                } else {
                    $('html').removeClass('dark');
                    $('#toggleSwitch').prop('checked',false)
                }
            }

            $('#toggleSwitch').change(function() {
                var isDark = 0;

                if (!$(this).is(':checked')) {
                    $('html').removeClass('dark');
                    isDark = 0;
                } else {
                    $('html').addClass('dark');
                    isDark = 1;
                }

                localStorage.setItem('darkMode',isDark)
            })
        })


    </script>
</body>

</html>
