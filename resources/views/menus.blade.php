    <nav class="navbar navbar-toggleable-md navbar-light bg-faded ">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="/">
          <img src="/images/berdvaye-logo.gif">
      </a>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
            <a class="nav-link" href="\">Home <span class="sr-only">(current)</span></a>
          </li>
        <li class="nav-item {{ Request::is('collection') ? 'active' : '' }}">
            <a class="nav-link" href="/collection">Collection</a>
        </li>
        <li class="dropdown {{ Request::is('news') ? 'active' : '' }}">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">News <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="/news">Press Releases</a></li>
          </ul>
        </li>        
        <li class="nav-item {{ Request::is('biography') ? 'active' : '' }}">
            <a class="nav-link" href="/biography">Biography</a>
        </li>
        <li class="nav-item {{ Request::is('our-catalog') ? 'active' : '' }}">
            <a class="nav-link" href="/our-catalog">Catalog</a>
        </li>
        <li class="nav-item {{ Request::is('authorized-dealers') ? 'active' : '' }}">
            <a class="nav-link" href="/authorized-dealers">Authorized Dealers</a>
        </li>        
      </div>
    </nav>