@extends ("layouts.default1")

@section("content")
@include ("menus")
    <div style="margin-bottom: 75px"></div>
    
    <div class="col-md-12" style="text-align:center">
        <img src="/public/images/404.png" style="max-width: 100%" />
        <h2 style="font-size: 40px;font-weight: bold">We're sorry.</h2>
        <h3>The page you're looking for cannot be found.</h3>
        <p>If you typed the URL directly, please make sure the spelling is correct. If you clicked on a link to get here, the link is outdated.
        </p>
        <p>If you're not sure how you got here, <a href="javascript:history.back(-1)">go back</a> to the previous page or return to our <a href="/">homepage</a>.</p>
    </div>


@endsection    