<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="Chronograph Watches, Watches, New Arrivals, Watches for Men, Watches for Women, Used Watches, Luxury, Luxury Watch Brands, Used Luxury Watches for Sale, High end Watches, Discount Luxury Watches, Discount Watches, Discount Watches Online, Pre owned Luxury Watches, Harry Winston, Michael Kors, Hublot, Franck Muller, Vacheron Constantin, Rolex, Tag Heuer, Panerai, Breitling, Chopard, Ulysse Nardin, Cartier, Jaeger LeCoultre, IWC, Audemars Piguet, Omega, Girard-Perragaux, Patek Philippe, Perrelet, Michele, Zenith, Jacob &amp; Co., Men, Women, Online, Brands, Swissmade." />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Berdvaye Inc. - Modern Art with Vintage  Parts
</title>

    <!-- Bootstrap Core CSS -->
    <!--<link href="css/bootstrap.min.css" rel="stylesheet"> -->
    @if (isset($stockJquery) && $stockJquery)
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    @endif
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/public/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="/public/images/favicons/manifest.json">
    <link rel="mask-icon" href="/public/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/public/images/favicons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Berd Vaye Inc.">
    <meta name="application-name" content="Berd Vaye Inc.">
    <meta name="msapplication-config" content="/public/images/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('/public/lightslider/css/lightslider.css') }}" rel="stylesheet">
    <!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'> -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Raleway:300,400,500,700,600" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link href="{{ asset('/public/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/public/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/public/css/default.css') }}" rel="stylesheet">
    @yield('styles')

    @yield('header')

    @yield("canonicallink")
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

@include ("menus")
@yield('content')

<footer>
    <section class="alternate m_bottom_25">
        <div class="container">
            {{ Form::open(array('action'=>'InquiryController@ajaxInquiry', 'id' => 'inquiryForm')) }}
                <div class="col-md-12" style="text-align:center">
                    <div style="padding: 25px 20px 0;">
                        <h4>CONTACT US</h4>
                    </div>
                </div>

                <div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>
                <div class="row">
                <div class="col-md-6">
                    <ul class="ul-table">
                        <li>
                            Berd Vaye Inc.<br>
                            610 5th Ave, #2222 
                            <br>New York, NY 10185
                            
                        </li>
                        <li>Tel: 833-BERDVAYE
                            <br>(833) 237-3829
                            <br>Email: info@berdvaye.com
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-6 m_bottom_50 fullname">
                            
                            {{ Form::text('fullname','',array('placeholder'=>"YOUR NAME: *",'class'=>'form-control', 'id'=>'fullname')) }}
                        </div>
                        <div class="col-6 m_bottom_50 email">
                            <input class="form-control" placeholder="E-MAIL: *" type="text" name="email" id="email-input"  required>
                        </div>
                        <div class="col-12 m_bottom_50">
                            <textarea class="form-control" placeholder="TELL US EVERYTHING" type="text" name="message" id="message-input"></textarea>
                        </div>
                        <div class="col-12 m_bottom_50" style="text-align: right">
                            <button type="submit" class="submit inquiry">SUBMIT</button>
                        </div>
                        
                    </div>
                </div>
            </div>
            {{ Form::close() }}

        </div>
    </section>

    <section class="m_bottom_50">
        <div style="position: relative">
            
            <a href="javascript:void(0)" class="back-top">
                <i class="fa fa-angle-double-up" aria-hidden="true"></i>
            </a>
        </div>
        <div class="col-md-12" style="text-align: center">
            <p style="font-size: 12px">©2017 ALL RIGHT RESERVED. DESIGNED BY BERDVAYE INC. DESIGN</p>
        </div>

        <script>
            $(document).ready(function(){
                $('.inquiry').click(function(e){
                    e.preventDefault();
                    $('.inquiry').attr('disabled','disabled');
                    $('.inquiry').removeClass('submit')
                    $('.inquiry').addClass('submit-disabled');
                    $('.inquiry').text('Please wait ...');

                    $.ajax({
                        type: "POST",
                        url: "{{action('InquiryController@ajaxInquiry')}}",
                        dataType: 'json',
                        data: { 
                            _token: "{{csrf_token()}}",
                            inquiry: $('form').serialize()
                        },
                        success: function (result) { 
                            $('.inquiry').text('SUBMIT');
                            if (result=='success') {
                                $('#fullname').val('');
                                $('#message-input').val('');
                                $('#email-input').val('');
                                $('input:-webkit-autofill').each(function(){
                                    var text = $(this).val();
                                    var name = $(this).attr('name');
                                    $(this).after(this.outerHTML).remove();
                                    $('input[name=' + name + ']').val(text);
                                });
                                alert ('Your inquiry has been emailed. You will be contacted as soon as possible.')
                            } else if (result.length>0) {

                                if (result.length > 1) {
                                    if ($('#error-fullname').length==0)
                                        $('.fullname').append('<div id="error-fullname">'+result[0]+'</div>')
                                } else if (result.length == 1) {
                                    if ($('#error-email').length==0)
                                        $('.email').append('<div id="error-email">'+result[0]+'</div>')
                                }

                            }
                            
                            $('.inquiry').attr('disabled',false);
                            $('.inquiry').removeClass('submit-disabled')
                            $('.inquiry').addClass('submit inquiry')
                        }
                    })
                })

                $('.fullname').focusin(function () {
                    if ($('#error-fullname').length>0)
                        $('#error-fullname').remove();
                })

                $('.email').focusin(function () {
                    if ($('#error-email').length>0)
                        $('#error-email').remove();
                })            
                //Check to see if the window is top if not then display button
                $(window).scroll(function(){
                    if ($(this).scrollTop() > 100) {
                        $('.back-top').fadeIn();
                    } else {
                        $('.back-top').fadeOut();
                    }
                });
                
                //Click event to scroll to top
                $('.back-top').click(function(){
                    $('html, body').animate({scrollTop : 0},1500);
                    return false;
                });
                
            });
        </script>

    </section>
</footer>
<script async src="{{ asset('/public/lightslider/js/lightslider.min.js') }}"></script>
<script async src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script async src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
@yield('footer')
@yield('jquery')

<!-- gtag('config', 'UA-109441022-1'); -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117697304-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-117697304-1');
</script>


</body>

</html>
