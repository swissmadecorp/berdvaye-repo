@extends('layouts.default')

@section ('content')
<body>
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
    <div class="defaultIntro animateRun pt-5 pb-5" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container">
        <div class="row pt-2 pb-2 clearfix justify-content-center">
          <div class="col-12 col-sm-6">
            <div class="videoWrap p-2">
              <video width="100%" poster="video/video_01.jpg">
                <source src="video/BERDVAYE_1_insta.mp4" type="video/mp4">
                Your browser does not support HTML5 video. </video>
              <div class="playpause"></div>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <div class="videoWrap p-2">
              <video width="100%" poster="video/video_02.jpg">
                <source src="video/BERDVAYE_2_insta.mp4" type="video/mp4">
                Your browser does not support HTML5 video. </video>
              <div class="playpause"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')
    @endsection

@section ('jquery')
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

  $('.videosmenu').addClass('active');
  $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_06.jpg" alt="menu_banner_06">');

    setTimeout(function() {
        $('.animateRun').viewportChecker();
    }, 1000);


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

    $(document).on('click', 'header nav, .mobileMenu', function(e) {
        e.stopPropagation();
    });

		$('.videoWrap').click(function () {
								var $this = $(this);
								var $video = $this.find('video');
								var $btn = $this.find('.playpause');
								var $prtRel = $(this).parent('div').siblings('div');
								$video.attr('controls', 'controls').get(0).play();
								$btn.fadeOut();
								$prtRel.find('video').removeAttr('controls').get(0).pause();
								$prtRel.find('.playpause').fadeIn();
		});


});
</script>

@endsection