@extends ("layouts.default1")  

@section ('header') 
<link href="{{ asset('/public/lightgallery/css/lightgallery.css') }}" rel="stylesheet">    
<link href="{{ asset('/public/lightgallery/css/justifiedGallery.min.css') }}" rel="stylesheet">
@endsection

@section ('content')

<div class="m_top_75"></div>

@if (isset($posts))
<h4 class="text-center">NEWS ARTICLES</h4>
<div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>
@foreach ($posts as $post)
<article class="article-small-header m_bottom_25">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <img style="max-width: 100%" alt="{{ $post->title }}" class="m_top_6" src="/public/images/posts/thumbs/{{ $post->image }}">
            </div>
            <div class="col-md-8">
                <p><b class="post-title">{{ $post->title }}</b>
                <br>{{ $post->subtitle }}
                </p>
                
                <!-- {!! $post->post !!} -->
                <?php echo strlen($post->post) > 500 ? substr($post->post,0,500).' ...<br><a href="news/'.$post->id.'"> Read More &raquo;</a>' : $post->post ?>
            </div>
        </div>
</article>
<div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>
@endforeach

{!! str_replace('/?', '?', $posts->render()) !!}
@else
<h4 class="text-center post-title">{{ $post->title }}</h4>
<h5 class="text-center">{{ $post->subtitle }}</h5>
<div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>
<article class="article-small-header m_bottom_25">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 text-center m_bottom_25">
                <img style="max-width: 100%" alt="{{ $post->title }}" class="m_top_6" src="/public/images/posts/{{ $post->image }}">
            </div>
            <div class="col-md-11" style="margin: 0 auto">
                {!! $post->post !!}
            </div>
        </div>
</article>

@endif
<div class="m_bottom_25"></div>
@endsection