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
          <div class="bannerBox" style="background-image:url(/public/images/product/product_07/hero_banner/hb_00.jpg)"> <img class="owl-lazy" data-src="/public/images/product/product_07/hero_banner/hb_00.jpg" alt="hb_00"/> </div>
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
          <li><img src="/images/product/frl/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="/images/product/cbl-ha/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="defaultIntro pb-5 pt-1 " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="flex justify-center items-center flex-col text-center px-4">
            <hr>
            <h2>Page not found</h2>

                <div class="box-main-404 flex justify-center items-center py-20">
                    <div class="outer-box w-[200px] h-[290px] shadow-[0px_0px_9px_#a09d9d] p-[10px] inline-block mr-[60px] -rotate-7">
                        <div class="inner-box w-full h-full bg-[#1e2337] flex items-center justify-center">
                            <div class="text-[170px] text-white font-sans">4</div>
                        </div>
                    </div>

                    <div class="outer-box w-[200px] h-[290px] shadow-[0px_0px_9px_#a09d9d] p-[10px] inline-block mr-[60px] rotate-3">
                        <div class="inner-box w-full h-full bg-[#1e2337] flex items-center justify-center">
                            <div class="text-[170px] text-white font-sans">0</div>
                        </div>
                    </div>

                    <div class="outer-box w-[200px] h-[290px] shadow-[0px_0px_9px_#a09d9d] p-[10px] inline-block mr-[60px] rotate-[14deg]">
                        <div class="inner-box w-full h-full bg-[#1e2337] flex items-center justify-center">
                            <div class="text-[170px] text-white font-sans">4</div>
                        </div>
                    </div>
                </div>
                <h2 style="font-size: 40px;font-weight: bold">We're sorry.</h2>
                <h3 class="text-cream-500">The page you're looking for cannot be found.</h3>
                <p>If you typed the URL directly, please make sure the spelling is correct. If you clicked on a link to get here, the link is outdated.
                </p>
                <p>If you're not sure how you got here, <a href="/">go back</a> to the previous page or return to our <a href="/" class="btn btn-sm btn-primary">homepage</a>.</p>
          </div>
        </div>
      </div>
    </div>

    @include('contactInfo')



@endsection