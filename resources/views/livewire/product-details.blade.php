<div>

@push ('header')
<link type="text/css" rel="stylesheet" href="/js/lightslider/css/lightgallery.css"/>
<link type="text/css" rel="stylesheet" href="/js/lightslider/css/lightslider.css"/>
@endpush

@section ("title",$currentProduct->model_name)
@section('og_title', "Modern Art ".$currentProduct->model_name." Sculpture")
@section('og_desc', $currentProduct->description)

@section('og_url', url()->current())

    <div class="bg-darkgray defaultBox full-visible grid-cols-2 md:grid mt-2 p-3 visible">
        <div wire:ignore class="defaultimg">
            <?php
                $imgPath = strtolower($model);
                $files = File::files(public_path("/images/product/$imgPath/images/"));
                $count = count($files);
            ?>
              <ul id="lightGallery" >
                @foreach (range(1, $count) as $i)
                <?php $newImgPath = asset("images/product/$imgPath/images/" . $imgPath . '-' . $i . '.jpg') ?>

                <li data-thumb="{{$newImgPath}}" data-src="{{$newImgPath}}">
                  <img src="{{$newImgPath}}" class=" rounded-lg"/>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- max-w-[30rem] -->
        <div
            x-cloak
            x-data="{isPageLoaded: @entangle('isPageLoaded')}"
            x-init="$wire.set('isPageLoaded', false);"
            @animation-complete.window="isPageLoaded = true"
            class="opacity-0 defaultAnim animateReRun justify-center md:pl-[2.75rem] p-4"
            :class="{ 'animation-lock': isPageLoaded == true }"
            data-vp-add-class="visible"
            data-vp-repeat="false"
            data-vp-offset="-99999">

            <h1 class="uppercase text-3xl dark:text-cream text-cream-300 font-playfair pb-2">{{$currentProduct->model_name}}</h1>
            <p class="text-sm dark:text-white text-gray-300">{{$currentProduct->description}}</p>
            @if ($size)

            <div x-data="{ activeSize: 'Small' }" class="productBox pt-4">
                <div class="productBox-size">
                    <a href="#" @click.prevent="activeSize = 'Small'; $wire.$call('updateItem', 'Small');" :class="{ 'active': activeSize === 'Small' }">
                        Small
                    </a>

                    <a href="#" @click.prevent=" activeSize = 'Large'; $wire.$call('updateItem', 'Large');" :class="{ 'active': activeSize === 'Large' }">
                        Large
                    </a>

                </div>
            </div>
            @endif

            <div class="text-sm pt-4">
                <p class="productBox-qty dark:text-white text-gray-300">Limited edition: {{$currentProduct->heighest_serial}} pieces</p>
                @if ($currentProduct->total_parts)
                <p class="productBox-part callParts dark:text-white text-gray-300">Number of Parts: {{$currentProduct->total_parts}}</p>
                @endif
                <p class="productBox-part callWeight dark:text-white text-gray-300">Weight: <span>{{ $currentProduct->weight }} lbs.</span> </p>
                @if ($currentProduct->dimensions)
                <p class="productBox-part callSize dark:text-white text-gray-300">Dimensions: <span>{{ $currentProduct->dimensions }}</span> <small>Inches</small></p>
                @endif

                <div class="defaultButton animateReRun justify-center md:pl-[2.75rem] md:p-4 " data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
                    <div class="flex justify-content-between">
                        <span id="price" class="text-cream-400 text-2xl">${{number_format($currentProduct->p_retail,2)}}</span>
                        <button class="cursor-pointer bg-darkred text-cream-300 transition-colors duration-300 ease-in-out px-6 py-2 h-[2.2rem] text-sm bg-darkred dark:text-cream rounded-full hover:bg-red-700 add-to-cart" wire:click="addToCart('{{ $currentProduct->p_model }}')">Add to Cart</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div wire:ignore>
        <div class="productThumb animateRun pt-2" data-vp-add-class="visible" data-vp-repeat="false" data-vp-offset="12%">
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
                    @foreach ($banners as $file)
                        <?php $bannerPath = "/images/product/$imgPath/banners/$file"; ?>
                        <div class="item">
                            <div class="productBanner-Box"> <img class="owl-lazy" data-src="{{$bannerPath}}" alt="banner_01"/>

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
    </div>

@push ('jquery')
<script type="text/javascript" src="/js/lightslider/js/lightgallery.min.js"></script>
<script type="text/javascript" src="/js/lightslider/js/lightslider.js"></script>

    @script
    <script type="text/javascript">
        jQuery.noConflict();
        jQuery(document).ready(function($) {

            $('#lightGallery').lightSlider({
                gallery:true,
                item:1,
                loop:true,
                thumbItem:9,
                slideMargin:10,
                enableDrag: false,
                currentPagerPosition:'left',
            });

            setTimeout(function() {

                $('.animateReRun').viewportChecker({
                    classToAdd: 'visible',
                    repeat: false,
                    offset: -99999, // Guaranteed trigger

                    callbackFunction: function(elem, action) {
                        if (action === 'add') {
                            const transitionDuration = 900;

                            setTimeout(function() {
                                // 1. Dispatch the event. Alpine/Livewire takes over from here.
                                window.dispatchEvent(new CustomEvent('animation-complete'));

                                // 2. We no longer need to manually remove classes.
                                // The 'animation-lock' class (applied by the binding) will
                                // override 'defaultAnim.animateReRun' and stop any further transitions.
                                console.log("Animation completed, signaling Alpine to lock state.");
                            }, transitionDuration);
                        }
                    }
                });
            }, 1000);

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
        });

        </script>
    @endscript
@endpush
</div>
