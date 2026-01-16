@extends('layouts.default')

@section ("title")
  Contact
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header>
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
      @include ('nav')
    </div>
  </header>

  <!-- MAIN -->
  <main>

  <div class="defaultBanner">
    <img src="\images\policies.jpg" alt="Privacy Policy">
      <div class="bannerBox-info opacity-80">
        <h3>Privacy Policy</h3>
      </div>
    </div>

  <div class="pt-4 pb-5 text-gray-300">
    <div class="container animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="justify-content-center">
        <div class="pt-4">

        <p>Berd Vaye operates the <a href="http://www.berdvaye.com">http://www.berdvaye.com</a> website, which provides the SERVICE.</p>
            <p>This page is used to inform website visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service.</p>
            <p>If you choose to use our Service, then you agree to the collection and use of information in relation with this policy.
                The Personal Information that we collect are used for providing and improving the Service. We will not use or share your information with anyone except as described in this Privacy Policy.</p>
            <p>The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions,
                    which is accessible at <a href="http://www.berdvaye.com">http://www.berdvaye.com</a>, unless otherwise defined in this Privacy Policy.</p>
                    <p><strong>Information Collection and Use</strong></p>
            <p>For a better experience while using our Service, we may require you to provide us with certain personally identifiable information, including but not limited to your name, phone number,
                and postal address. The information that we collect will be used to contact or identify you.</p>
            <p><strong>Log Data</strong></p><p>We want to inform you that whenever you visit our Service, we collect information that your browser sends to us that is called Log Data.
                    This Log Data may include information such as your computer’s Internet Protocol (“IP”) address,
                    browser version, pages of our Service that you visit, the time and date of your visit, the time spent on those pages, and other statistics.</p>
                    <p><strong>Cookies</strong></p>
            <p>Cookies are files with small amount of data that is commonly used an anonymous unique identifier.
                These are sent to your browser from the website that you visit and are stored on your computer’s hard drive.</p>
            <p>Our website uses these “cookies” to collection information and to improve our Service.
                    You have the option to either accept or refuse these cookies, and know when a cookie is being sent to your computer.
                    If you choose to refuse our cookies, you may not be able to use some portions of our Service.</p>
            <p><strong>Service Providers</strong></p><p>We may employ third-party companies and individuals due to the following reasons:</p>
                        <ul style="list-style: disc">
                            <li class="first-child">To facilitate our Service;</li>
                            <li>To provide the Service on our behalf;</li>
                            <li>To perform Service-related services; or</li>
                            <li class="last-child">To assist us in analyzing how our Service is used.</li>
                        </ul>
            <br>
            <p>We want to inform our Service users that these third parties have access to your Personal Information.
                The reason is to perform the tasks assigned to them on our behalf. However, they are obligated not to disclose or use the information for any other purpose.</p>
                <p><strong>Security</strong></p><p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it.
                    But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>
                    <p><strong>Links to Other Sites</strong></p>
            <p>Our Service may contain links to other sites. If you click on a third-party link, you will be directed to that site.
                        Note that these external sites are not operated by us. Therefore, we strongly advise you to review the Privacy Policy of these websites. We have no control over, and
                        assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>
            <p><strong>Children’s Privacy</strong></p><p>Our Services do not address anyone under the age of 13.
                We do not knowingly collect personal identifiable information from children under 13. In the case we discover that a child under 13 has provided us with personal information,
                we immediately delete this from our servers. If you are a parent or guardian and you are aware that your child has provided us with personal information,
                please contact us so that we will be able to do necessary actions.</p
                <p><strong>Changes to This Privacy Policy</strong></p>
                <p>We may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. We will notify you of any changes by posting the new
                    Privacy Policy on this page. These changes are effective immediately, after they are posted on this page.</p>
            <p><strong>Contact Us</strong></p>
            <p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us.</p>

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

    $('.homemenu').addClass('active');
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

    $(document).on('keydown', '.trimSpace', function(e) {
        if (e.which === 32 && e.target.selectionStart === 0) {
            return false;
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

});
</script>

@endsection
