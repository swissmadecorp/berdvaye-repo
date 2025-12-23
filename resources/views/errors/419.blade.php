@extends ("layouts.default")

@section ("title")
  Page Expired
@endsection

@section ('content')
<div class="pageLayout">
  <!-- HEADER -->
  <header class="bg-darkgray">
    <div class="container-fluid clearfix">
      <button class="menuToggler"> </button>
      @include ('nav')
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <div class="defaultBanner">
      <div id="defaultBanner" class="owl-carousel owl-theme ownCarousel">
        <div class="item">
          <div class="bannerBox" style="background-image:url(images/product/product_07/hero_banner/hb_00.jpg)"> <img class="owl-lazy" data-src="/public/images/product/product_07/hero_banner/hb_00.jpg" alt="hb_00"/> </div>
        </div>
      </div>
      <div class="bannerBox-info">
        <h3>Page Expired</h3>
      </div>
    </div>

    <div class="productThumb " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="/images/product/hgs/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="/images/product/product_05/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="/images/product/cbl-ha/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="defaultIntro pb-5 pt-1 " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="flex justify-center items-center flex-col text-center px-4">
            <hr>
            <h2>Page expired</h2>

                <img src="/images/errors/419.jpg" style="max-width: 100%" />
                <h2 style="font-size: 40px;font-weight: bold">We're sorry.</h2>
                <h3 class="text-cream-300">The page you're looking for is exired.</h3>
                <p>If you typed the URL directly, please make sure the spelling is correct. If you clicked on a link to get here, the link is outdated.
                </p>
                <p>If you're not sure how you got here, <a href="/">go back</a> to the previous page or return to our <a href="/" class="btn btn-sm btn-primary">homepage</a>.</p>
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')

    @include('modalform')

@endsection