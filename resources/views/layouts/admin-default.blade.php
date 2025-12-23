<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{{ asset('images/favicon.png') }}}">

    <title>Berdvaye Inc. - Admin page</title>

    <!-- Bootstrap core CSS -->
    <!--<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">-->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link href="{{ asset('fontawesome/css/all.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('js/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet">

    @yield('header')
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>


@if( Auth::user())

  @include('layouts.admin-nav')
  
    <div class="container-fluid">
      <div class="row">
        @include ('layouts.admin-sidebar')

        <main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
          @if (isset($pagename))
          <h1 class='page-header {{ strpos($pagename,"UnPaid")>0 || strpos($pagename,"Returned")>0 ? "statusunpaid" : '' }}'>{{ $pagename }}</h1>
          @endif

          @yield('content')
          <div class="pt-2 placeholders">
            <!-- will be used to show any messages -->
            @if (Session::has('message'))
              <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif
          </div> 
        </main>
      </div>
    </div>

    <script>
      window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
        'pusherKey' => config('broadcasting.connections.pusher.key')
      ]) !!};
      //Need to put 2 curly braces
    </script>
    <!-- <script src="{{'/public/dist/app.js'}}"></script>  -->
@else
  <div class="container-fluid">
    <div class="row">
      <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
        <button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <a class="navbar-brand" href="<?= URL::to('/')?>/admin/login">Login</a>

      </nav>
      @yield('content')
    </div>
  </div>
      
@endif

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!--<script src="{{ asset('/js/bootstrap.min.js') }}"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="{{ asset('js/general.js') }}"></script>
    <script src="{{ asset('js/customers/jquery.customer.js') }}"></script>
    <script src="{{ asset('js/jquery-confirm/jquery-confirm.min.js') }}"></script>
    @yield('footer')
    <script>
        $(document).ready( function() {
          $body = $("body");

          $('.dropdown').click(function(){
            $('.dropdown-menu').toggleClass('show');
          });

          $(document).on({
            ajaxStart: function() { $body.addClass("loading");},
            ajaxStop: function() { $body.removeClass("loading");}
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            statusCode: {
              419: function(){
                location.reload(); 
              }
            }
        });
      });
    </script>


    @yield('jquery')
    
  </body>
</html>