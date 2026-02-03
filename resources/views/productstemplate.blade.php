@section ('header')
<link href="{{ asset('/fancybox/jquery.fancybox.min.css') }}" rel="stylesheet">
@endsection

@if (!$products->isEmpty())
@include('toolbar')
@endif

<div class="row">
    @if (!$products->isEmpty())
    <?php $i=0 ?>
    @foreach ($products as $product)
    <div class="col-sm-6 col-lg-3 col-md-4 custom-item-width">
        <div class="thumbnail" data-id="{{ $product->id }}">
            @if (@count($product->images))
            <?php $image = $product->images->first() ?>
            <img style="width: 225px" title="{{ $image->title }}" alt="{{ $image->title }}" src="{{ URL::to('/uploads') .  '/' . $image->location }}" alt="">
            @else
            <img style="width: 225px" src="{{ URL::to('/uploads')}}/no-image.jpg" alt="">
            @endif
            
            <span class="sticker-wrapper top-left"><span class="sticker new">{{ Status()->get($product->p_status) }}</span></span>
            <div class="inquire">
                <button class="btn btn-secondary btn-sm" aria-pressed="false" autocomplete="off" style="width: 100%" type="">Click to Inquire</button>
            </div>
            <div class="caption">
                {{$product->categories->category_name}} {{$product->p_model}}
            </div>
            <div class="container item-info">
                <div class="row">
                    <ul class="form-list">
                        <li class="control">
                            <div class="attribs">
                                <label for="" class="second_font m_right_17 m_top_2 d_inline_b">Price</label>
                                <span class="price">${{@number_format($product->p_price,2)}}</span>
                            </div>
                        </li>
                        <li class="control">
                            <div class="attribs">
                                <label for="" class="second_font m_right_17 m_top_2 d_inline_b">Retail</label>
                                <span class="retail">${{@number_format($product->p_retail,2)}}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- <div class="product-info">
                <div style="padding:4px 7px;">
                    <ul>
                        <div class="attribs">
                        <li class="control">
                            <label class="second_font m_right_17 m_top_2 d_inline_b" for="attr_{{$i+1}}">Papers</label>
                            <span id="attr_{{$i+1}}">{{ $product->p_papers == 1 ? 'Yes' : 'No' }}</span>
                        </li>
                        </div>
                        <div class="attribs">
                        <li class="control">
                            <label class="second_font m_right_17 m_top_2 d_inline_b" for="attr_{{$i+2}}">Box</label>
                            <span id="attr_{{$i+2}}">{{ $product->p_box == 1 ? 'Yes' : 'No' }}</span>
                        </li>
                        </div>

                        <div class="attribs">
                        <li class="control">
                            <label class="second_font m_right_17 m_top_2 d_inline_b" for="attr_{{$i+4}}">Condition</label>
                            <span id="attr_{{$i+4}}">{{ Conditions()[$product->p_condition] }}</span>
                        </li>
                        </div>
                        <?php $i=$i+4 ?>
                    </ul>
                </div>
               
            </div> -->
        </div>
    </div>
    @endforeach
    @else
        <div style="text-align:center">No products found in this category</div>
    @endif
</div>

@if (!$products->isEmpty())
@include('toolbar')
@endif


    <div id="product-inquiry" style="max-width: 100%;display:none">
        <div class="popup-header">
            <h3 style="padding: 12px; text-align: left">Product Inquiry</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 img-panel">
                    <img src="" style="width:170px" class="mt-1" />
                    <div class="caption"></div>
                    <div class="price"></div>
                    <div class="retail"></div>
                </div>
                <div class="col-md-9 form-panel">
                    
                    <div class="pb-2">Send an inquiry by filling out the form below</div>

                    {{ Form::open(array('url' => 'ajaxInquiry', 'class' => 'inquiry-form')) }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('contact_name', 'Your Name') }}
                                    {{ Form::text('contact_name', null, array('class' => 'form-control')) }}
                                    {{ Form::hidden('product_id', null, array('class' => 'form-control', "id" => 'product_id')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('company_name', 'Company Name') }}
                                    {{ Form::text('company_name', null, array('class' => 'form-control','required' => 'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('email', 'Email Address') }}
                                    {{ Form::text('email', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('phone', 'Phone Number') }}
                                    {{ Form::text('phone', null, ['class' => 'form-control','required' => 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('notes', 'Additional Notes') }}
                                    {{ Form::textarea('notes', null, ['class' => 'form-control','rows' => 4, 'cols' => 40]) }}
                                </div>
                                <div class="pb-3 float-right">
                            {{ Form::submit('Send Inquiry', array('class' => 'btn btn-primary submit-inquiry')) }}
                            </div>
                            </div>
                            
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        
    </div>

@endsection

@section ("canonicallink")
    <link rel="canonical" href="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>" />
@endsection

@section ('footer')
    <script src="{{ asset('/fancybox/jquery.fancybox.min.js') }}"></script>
@endsection

@section ("jquery-add-1")

<script>
    $(document).ready( function() {

        $('.inquire button').click( function () {
            var _this = $(this);
            $('.inquiry-form')[0].reset();;

            $.fancybox.open({
                src: "#product-inquiry",
                type: 'inline',
                beforeShow: function() {
                    $('.img-panel img').attr('src', $(_this).parents('.thumbnail').find('img').attr('src'));
                    $('.img-panel .caption').text($(_this).parent().next().text());
                    $('.img-panel .price').text('Price: '+$(_this).parent().next().next().find('.price').text());
                    $('.img-panel .retail').text('Retail: '+$(_this).parent().next().next().find('.retail').text());
                    $('#product_id').val($(_this).parents('.thumbnail').attr('data-id'));
                }
            });
        })

        $('.submit-inquiry').click( function (e) {
            e.preventDefault();
            $.ajax ( {
                type: 'post',
                dataType: 'json',
                url: $('.inquiry-form').attr('action'),
                data: {inquiry: $('.inquiry-form').serialize(),_token: "{{csrf_token()}}"},
                success: function(response) {
                    if ($.isEmptyObject(response.error)) {
                        $.fancybox.close();
                        $.fancybox.open({
                            src: "<div><p style='padding: 30px 20px;width: 90%'>Your inquiry has been submitted successfully. Someone will get back to you as soon as possible.</p></div>",
                            type: 'html',
                        });
                    } else {
                        alert (response.error)
                    }
                    
                }
            })
        })
    })
</script>
@endsection