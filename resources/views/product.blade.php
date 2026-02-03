@extends('layouts.default')

@section ("title",$product->model_name)

@section ('header')
<link type="text/css" rel="stylesheet" href="/js/lightslider/css/lightgallery.css"/>
<link type="text/css" rel="stylesheet" href="/js/lightslider/css/lightslider.css"/>
@endsection


<?php $imgPath=''; $text_banner_1 = ''; $text_banner_2 = ''; $text_banner_3 = ''; $text_banner_4 = ''; $banners=0?>
@if ($product->p_model=="SPL" || $product->p_model=="SPS")
  <?php $imgPath = "product_01" ?>
  <?php
    // $text_banner_1 = 'This beautiful sculpture is the perfect conversation piece for your drawing room décor - guaranteed to capture your guests attention';
    // $text_banner_2 = 'Berd Vay’e sculptures make unique and masterful centerpieces for a dining table';
    // $text_banner_3 = 'Our sculptures are the perfect compliment to your personal library. Like your most prized volumes, each crystal clear Lucite&trade; design piece holds within it the varied stories and histories of the passage of time.';
    $banners = 3
    ?>
@elseif ($product->p_model=="CBL-GA")
  <?php $imgPath = "product_07" ?>
  <?php
    // $text_banner_1 = 'Within "Galaxy" our imaginative artisans designed an abstract depiction of the watchmaker’s universe: a cluster of watch parts from treasured timepieces, now ‘vintage’ and having lost functionality. Sought out by our experts, these pieces of history are disassembled then restored to start life anew as modern art.';
    // $text_banner_2 = 'Our sculptures are the perfect compliment to your collection of art, or as a centerpiece atop any table. Each crystal clear Lucite&reg; design piece holds within it the varied stories and histories of the passage of time - and is a sure conversation piece among all who view it, from every angle.';
    // $text_banner_3 = 'The production process for "Galaxy" expands upon our signature artistic methodology, developing the brand’s repertoire through the creation of this new "2-in-1" sculpture. Each watch component encrusted orb seems to move, yet stands still within the artistry that is Berd Vay’e.';
    $banners = 3
  ?>
@elseif ($product->p_model=="CBL-HA")
  <?php $imgPath = "product_06" ?>
  <?php
    // $text_banner_1 = 'This beautiful sculpture is the perfect conversation piece for your drawing room décor - guaranteed to capture your guests attention.';
    // $text_banner_2 = 'Berd Vay’e sculptures make unique and masterful centerpieces for a dining table.';
    // $text_banner_3 = 'The perfect focal point of any room and the center of conversation. What do you see within? From every angle, our emotive skull tricks the eye and challenges the mind. This sculpture promises to leave your guests talking and pondering what their eyes did behold within the formidable and statuesque cube.';
    $banners = 3
  ?>
@elseif ($product->p_model=="CBL-GR")
  <?php $imgPath = "product_08" ?>
  <?php
    // $text_banner_1 = 'A marvel of 3D geometry, “Gravity” rounds out our collection of 2-in-1 sculptures, boasting our watch parts-encrusted horosphere within a stately cube of pure Lucite.';
    // $text_banner_2 = 'Our “Gravity" sculpture illustrates the literal force of suspension, of movement halted in time to showcase the naturally magnified intricacies of the components within.';
    // $text_banner_3 = 'Gravity” is a showstopper within any room - your guests will be awed by the feat of balance of the inner orb that rests within our precariously perched cube - as if the entire sculpture has been caught tumbling through time and space.';
    $banners = 3
  ?>
@elseif ($product->p_model=="SKS" || $product->p_model=="SKL")
  <?php $imgPath = "product_03" ?>
  <?php
    // $text_banner_1 = 'A visual metaphor, the Lost in Time sculpture showcases the complexities of both the human mind and the watchmaker’s art.';
    // $text_banner_2 = 'An exceptional gift to make any occasion all the more memorable - and a gift which will stand the test of time.';
    // $text_banner_3 = 'Your living space should be filled with your own personal collection of art that fascinates the mind and evokes passion within.';
    // $text_banner_4 = 'A bold mix of new and old; modern design encapsulating vintage heirloom components. A perfect accessory to your personal library.';
    $banners = 4
  ?>
@elseif ($product->p_model=="CBS" || $product->p_model=="CBL")
  <?php $imgPath = "product_02" ?>
  <?php
    // $text_banner_1 = 'This ingeniously balanced cubic sculpture adds dimension and intrigue to any living space and will captivate your visitors.';
    // $text_banner_2 = 'Artistic revelry; a showstopping collector’s piece designed to fascinate.';
    // $text_banner_3 = 'An exercise in dynamism; a seemingly suspended feat of design perfect for display in a study.';
    // $text_banner_4 = 'Natural magnification through crystal clear Lucite creates the illusion that metallic components are floating effortlessly in the space between riveting dinner conversations.';
    $banners = 4
  ?>
@elseif ($product->p_model=="HGS" || $product->p_model=="HGL")
  <?php $imgPath = "product_04" ?>
  <?php
    // $text_banner_1 = 'Suspend time while entertaining your guests - a luxe accessory for any bar cart or serving tray.';
    // $text_banner_2 = 'Perhaps the most literal sculpture in the collection, the components of time literally stand still in an illusion of time passing like sands through an hourglass.';
    // $text_banner_3 = 'High design; a style-forward accessory that flawlessly blends vintage and modern themes.';
    // $text_banner_4 = 'Expect the unexpected; Berd Vay’e sculptures are a testament to an individual’s passion for design and whimsy.';
    $banners = 4
  ?>
@elseif ($product->p_model=="FRL")
  <?php $imgPath = "product_05" ?>
  <?php
    $banners = '1';
    // $text_banner_1 = 'Crystal clear Lucite encapsulated by a traditional frame designed to adorn your walls with the unexpected.';
    // $text_banner_2 = 'The Timed Frame collector’s pieces add a subtle, yet complex and sophisticated air of luxury to any room.';
    // $text_banner_3 = 'Meant to be studied, the viewer can more easily appreciated the individual intricacies of each and every component in its suspended state.';
    // $text_banner_4 = 'Immortalize an art - the art of watchmaking - and create new art therein. A studied and purposeful approach to preservation through creation.';
    $banners = 4
  ?>
@elseif ($product->p_model=="GRD")
  <?php $imgPath = "product_15" ?>
  <?php
    // $text_banner_1 = 'The grenade, a sinister contraption, was ingeniously packed with watch parts, its inner workings a chaotic blend of precision and frozen time.';
    // $text_banner_2 = 'The grenade, filled with intricate watch parts, seemed to embody a paradox—an instrument of chaos built from the very gears that once measured moments of calm.';
    // $text_banner_3 = 'The grenade, its core packed with shimmering watch parts, felt like a ticking monument to time suspended in an instant.';
    $banners = 3
    ?>
@elseif ($product->p_model=="KING")
  <?php
    $imgPath = "product_09";
    $banners = 2;
  ?>
@elseif ($product->p_model=="QUEEN")
  <?php
    $imgPath = "product_10";
    $banners = 2;
    ?>
  <?php
    // $text_banner_1 = 'The chess queen, a symbol of elegance and power, glided across the board with commanding grace, her every move a delicate balance of strategy and dominance.';
    // $text_banner_2 = 'The chess queen, intricately crafted with watch gears hidden within, seemed to hum with quiet energy, as if time itself powered her calculated dominion over the board.';
    // $text_banner_3 = 'Forged with delicate watch gears nestled inside, moved with an almost mechanical precision, a masterful blend of timeless elegance and engineered purpose.';
    ?>
@elseif ($product->p_model=="ROOK")
  <?php
    $imgPath = "product_11";
    $banners = 2;
  ?>
@elseif ($product->p_model=="BISHOP")
  <?php
      $imgPath = "product_12" ;
      $banners = 2;

    ?>
@elseif ($product->p_model=="KNIGHT")
  <?php
    $imgPath = "product_13";
    $banners = 2;
  ?>
@elseif ($product->p_model=="PAWN")
  <?php
    $imgPath = "product_14";
    $banners = 2;
    ?>
@endif

@section('og_title', "Modern Art ".$product->model_name." Sculpture")
@section('og_desc', $product->description)
@section('og_image', config('app.url'). "/images/product/". $imgPath."/product_img_01.jpg")
@section('og_url', url()->current())
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
  <main class="bg-darkgray">
    <div class="defaultBanner">
      <!-- <img src="/images/product/{{$imgPath}}/hero_banner/hb_00.jpg" alt="hb_00" class="w-100"/> -->
      <div class="bannerBox-info md:bg-gray-300/30 hidden md:block bg-darkgray">
        <h3>{{$product->model_name}}</h3>
      </div>
    </div>
    <div class="container-fluid">
      <div class="defaultBox animateRun mt-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
        <div class="row clearfix">
          <div class="col-12 col-sm-6 p-4">
            <?php
            $imgPath = strtolower($product->p_model);
                $files = File::files(public_path("/images/product/$imgPath/images/"));
                $count = count($files);
            ?>
              <ul id="lightGallery" >
                @foreach (range(1, $count) as $i)
                <?php $newImgPath = asset("images/product/$imgPath/images/" . strtolower($product->p_model) . '-' . $i . '.jpg') ?>

                <li data-thumb="{{$newImgPath}}" data-src="{{$newImgPath}}">
                  <img src="{{$newImgPath}}" class=" rounded-lg"/>
                </li>
                @endforeach
            </ul>
          </div>
          <div class="col-12 col-sm-6">
            <div class="defaultBox-info animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
              <h2 class="text-cream-300">{{$product->model_name}}</h2>
              <p class="pt-3 pb-3">{!! $product->description !!}</p>
              <div class="productBox">
                @if (!empty($productArray))
                <div class="productBox-size">
                  @foreach ($productArray as $arr)
                    <a href="#" <?= $loop->index == 0 ? 'class="active"' : '' ?> data-parts="{{$arr['parts']}}" data-weight="{{$arr['weight']}}" data-dimensions="{{$arr['dimensions']}}" data-price="{{$arr['price']}}" data-id='{{$arr["model"]}}'>{{$arr['size']}}</a>
                  @endforeach
                </div>
                @endif
                <p class="productBox-qty">Limited edition: {{$product->heighest_serial}} pieces</p>
                @if ($product->total_parts)
                <p class="productBox-part callParts">Number of Parts: {{$product->total_parts}}</p>
                @endif
                <p class="productBox-part callWeight">Weight: <span>{{ $product->weight }}</span> </p>
                @if ($product->dimensions)
                <p class="productBox-part callSize">Dimensions: <span>{{ $product->dimensions }}</span> <small>Inches</small></p>
                @endif

                <div class="flex justify-content-between">
                  <span id="price" class="text-cream-400 text-2xl">${{number_format($product->p_retail,2)}}</span>
                  <button class="btn btn-sm btn-warning add-to-cart" data-url="{{ $product->p_model }}">Add to Cart</button>
              </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb animateRun pt-5" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid clearfix">
        <ul>
          <li><img src="/images/product/{{$imgPath}}/thumb/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="/images/product/{{$imgPath}}/thumb/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="/images/product/{{$imgPath}}/thumb/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    <div class="productBanner animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid clearfix">
        <div id="productBanner" class="owl-carousel owl-theme ownCarousel">
          @foreach (range(1,$banners) as $b)
          <div class="item">
            <div class="productBanner-Box"> <img class="owl-lazy" data-src="/images/product/{{$imgPath}}/banners/banner_0{{$b}}.jpg" alt="banner_01"/>
              <!-- <div class="productBanner-info">
                <h2>01</h2>
                <p>{{$text_banner_1}}</p>
              </div> -->
            </div>
          </div>
          @endforeach
        </div>

      </div>
    </div>
    <div class="defaultIntro pb-5 pt-5 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container-fluid">
        <div class="row clearfix justify-content-center">
          <div class="col-12 col-sm-10 col-md-9">
            <h2>Berd Vay’e Benefits</h2>
            <p class="text-center">Enjoy our unique and authentic gifting & packaging for a more exciting experience of gift giving.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="productThumb pt-2 animateRun" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
      <div class="container clearfix">
        <ul>
          <li><img src="/images/product/product_benefits/thumb_01.jpg" alt="thumb_01"></li>
          <li><img src="/images/product/product_benefits/thumb_02.jpg" alt="thumb_02"></li>
          <li><img src="/images/product/product_benefits/thumb_03.jpg" alt="thumb_03"></li>
        </ul>
      </div>
    </div>
    @include('contactInfo')

    @include('modalform')
@endsection

@section('footer')

<script type="text/javascript" src="/js/lightslider/js/lightgallery.min.js"></script>
<script type="text/javascript" src="/js/lightslider/js/lightslider.js"></script>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {

                    $('.collectionmenu').addClass('active');
                $('.rightMenu').html('<img class="imgCenter" src="/images/menu/menu_banner_01.jpg" alt="menu_banner_05">');
    setTimeout(function() {
        $('.animateRun').viewportChecker();
    }, 1000);


    $(document).on('keydown', '.trimSpace', function(e) {
        if (e.which === 32 && e.target.selectionStart === 0) {
            return false;
        }
    });


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

    // var owl = $('#productImage');
    // owl.owlCarousel({
    //     margin: 0,
    //     mouseDrag: false,
    //     nav: true,
    //     navSpeed: 800,
    //     items: 1,
    //     loop: false,
    //     dots: true,
    //     lazyLoad: true,
    //     navText: ["<img src='/images/icons/icon_next.png' alt='next'>", "<img src='/images/icons/icon_prev.png' alt='prev'>"],
    //     autoplayHoverPause: true
    // });

    $('#lightGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:9,
        slideMargin:10,
        enableDrag: false,
        currentPagerPosition:'left',
    });

    var owl = $('#productBanner');
    owl.owlCarousel({
        margin: 0,
        mouseDrag: false,
        nav: true,
        navSpeed: 800,
        items: 1,
        loop: false,
        dots: true,
        lazyLoad: true,
        navText: ["<img src='/images/icons/icon_next.png' alt='next'>", "<img src='/images/icons/icon_prev.png' alt='prev'>"],
        autoplayHoverPause: true
    });

    $('#gallerySilder').owlCarousel({
        margin: 10,
        items: 1,
        nav: false,
        autoWidth: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            1200: {
                items: 2,
            },
            1369: {
                items: 4,
            }
        }
    });

    $(document).on('click', '.productBox-size a', function(e) {
        e.preventDefault();
        if ($('.productBox-size').children().length==2) {
            var partsVal = $(this).attr('data-parts');
            var weightVal = $(this).attr('data-weight');
            var dimensionstVal = $(this).attr('data-dimensions');
            var priceVal = $(this).attr('data-price');

            const formattedAmount = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
            }).format(Number(priceVal));

            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            $('.callParts').html('Number of Parts: ' + partsVal);
            $('.callWeight span').html(weightVal);
            $('.callSize span').html(dimensionstVal);

            // $('.add-to-cart').text('Add '+$(this).text()+' to Cart');
            $('.add-to-cart').attr('data-url',$(this).attr('data-id'))
            $('#price').text(formattedAmount);
            // $.get("{{ route('retail.price') }}", { model: $(this).attr('data-id') }).done(function(data) {
                // $('#retail_price').text(data.retail);
                // $('#percent').text(data.percent);
            // })
        }
    });


        $('.add-to-cart').click( function (e) {
            e.preventDefault();
            _this=$(this);
            $.ajax({
                type: "POST",
                url: "{{route('add.to.cart')}}",
                data: {'model': $(_this).attr('data-url'), 'qty': 1},
                success: function (result) {
                    document.location.href = '/cart';

                }
            })
        })
});
</script>
@endsection