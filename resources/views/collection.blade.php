@extends ("layouts.default1")  

@section ('content')

<div class="popup"></div>

<section class=" alternate" style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="text-align:center;margin-top: 15px">
                <h4>HOROLOGICAL SCULPTURES</h4>
            </div>

            <div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>
            <div style="padding: 15px; text-align: center;font-size: 18px;">
                <b>WE BELIEVE</b> IN COMING UP WITH ORIGINAL IDEAS AND TURNING THEM INTO REALITY THAT IS BOTH <b>INNOVATIVE AND MEASURABLE</b>.
            </div>

        </div>
    </div>
</section>

@foreach ($products as $product)
<section class="m_bottom_50">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-7" style="text-align:center">
                <div style="padding: 25px 0 0;" >
                    @if (!empty($product->images->first()->location))
                    <img src="public/uploads/<?php echo $product->images->first()->location?>" style="max-width:100%" />
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-lg-4">
                <div class="titles">
                    <div style="padding: 18px 0 0;">
                        {{$product->p_title}}
                    </div>
                    <div class="font_5" style="line-height: 35px">
                        {{$product->p_reference}}
                    </div> 
                </div>

                <div style="padding: 25px 0 0;" class="m_bottom_25">
                    <?php 
                        $smlDesc=str_replace('&lt','<',$product->p_smalldescription);
                        $smlDesc=str_replace('&gt','<',$smlDesc) ;

                        if ($product->p_size != 'None') {
                            echo '<i class="size">'. strtoupper($product->p_size.' '.$product->p_title).'</i><hr>';
                        }
                        echo $smlDesc;
                    ?>
                </div>
                <a href="" class="submit collection" data-id="{{ $product->id }}">View Collection</a>
            </div>
            
            
        </div>
    </div>
</section>
@endforeach

@endsection


@section ('jquery')

<script>
    function createLightSlider() {
        $('#lightslider').lightSlider({
            item: 1,
            gallery: false,
            loop: true,
        });
        
    }

    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $(document).on('change','.product-size input', function (e) {
            var _this = $(this);
            $.ajax({
                type: "GET",
                url: "{{action('AjaxProductsController@ajaxGetProduct')}}",
                data: { 
                    _token: "{{csrf_token()}}",
                    id: $(_this).val()
                },
                success: function (result) { 
                    if (result != "false" ) {
                        $('.description').hide().html(result.content).fadeTo(800,1)
                        //$('.pricing').hide().html(result.price).fadeTo(800,1)
                    } else
                        alert ('This product does not have any additional information.');
                }
            })
        })
        
        $('.collectionmenu').addClass('active');
        $('.collection').click( function (e) {
            e.preventDefault();
            var _this = $(this);
            //alert ('This feature is under construction.');
            $.ajax({
                type: "GET",
                url: "{{action('AjaxProductsController@ajaxLoadProduct')}}",
                data: { 
                    _token: "{{csrf_token()}}",
                    id: $(_this).attr('data-id')
                },
                success: function (result) { 
                    if (result != "false" ) {
                        $('.popup').html(result);
                        $('.popup').fadeIn("slow");
                        $('html, body').css('overflow','hidden')
                        
                        createLightSlider();
                    } else
                        alert ('This product has not been any additional information.');
                }
            })
        })

        function closePopup() {
            $('.popup').fadeOut("slow");
            $('html, body').css('overflow','initial')
        }

        $(document).on('keydown input', function (e) {
            if (e.which == 27) {
                closePopup();
            }
        })
        
        $(document).on('click','.close-popup', function() {
            closePopup();
        })

    });    
</script>

@endsection        
