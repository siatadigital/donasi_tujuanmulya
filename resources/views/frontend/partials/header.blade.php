<header class="navbar fixed-top navbar-expand-lg">
  <div class="container">
    <div id="top-nav" class="top-nav">
      <ul class="navbar-nav nav-sosmed">
        <li class="nav-item">
          <a target="_blank" class="nav-link" href="{{ $settings['FACEBOOK_LINK'] }}"><i class="fab fa-facebook-f"></i></a>
        </li>
        <li class="nav-item">
          <a target="_blank" class="nav-link" href="{{ $settings['TWITTER_LINK'] }}"><i class="fab fa-twitter"></i></a>
        </li>
        <li class="nav-item">
          <a target="_blank" class="nav-link" href="{{ $settings['INSTAGRAM_LINK'] }}"><i class="fab fa-instagram"></i></a>
        </li>
        <li class="nav-item">
          <a target="_blank" class="nav-link" href="{{ $settings['WA_LINK'] }}?text={{ $settings['WA_DEFAULT_MSG'] }}"><i class="fab fa-whatsapp"></i></a>
        </li>
      </ul>
      <a href="{{ route('frontend.home') }}" class="web-logo mx-auto">
        <img src="{{ $settings['WEB_LOGO'] }}">
      </a>
      <form method="get" action="{{ route('frontend.product.list') }}" class="header-form form-inline">
        <div class="search-box noBorder">
          <button id="btn-search" type="button" class="btn btn-search"><i class="fas fa-search"></i></button>
          <div id="input-box" class="input-box">
            <input class="form-control" type="search" name="search" placeholder="Search" aria-label="Search" value="{{ request()->search }}">
          </div>
          <button id="btn-close" type="button" class="btn btn-close"><i class="fas fa-times"></i></button>
        </div>
        @if(auth()->guest())
          <a id="btn-logreg" class="btn" href="{{ route('frontend.auth') }}"><i class="far fa-user"></i> Login / Daftar</a>
        @endif
        @if(auth()->user())
          @if(auth()->user()->role == 'customer')
            <a id="btn-logreg" class="btn" href="{{ route('frontend.order.list') }}">
                <i class="far fa-user"></i>
                Akun
                @if ($deposit_amount)
                <span class="small" style="color:#aaa;">(Rp. {{ number_format($deposit_amount) }})</span>
                @endif
            </a>
          @endif
        @endif
        @if(auth()->user())
          @if(auth()->user()->role == 'customer')
            <a id="btn-chart" class="btn" href="{{ route('frontend.cart') }}"><i class="fas fa-shopping-bag"></i> Keranjang ({{ $cart_details_count }})</a>
          @endif
        @else
          <a id="btn-chart" class="btn" href="{{ route('frontend.cart') }}"><i class="fas fa-shopping-bag"></i> Keranjang ({{ $cart_details_count }})</a>
        @endif
      </form>
    </div>
    <div id="main-navigation" class="main-nav">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul id="main-navbar-nav" class="navbar-nav mx-auto">
          <li class="nav-item active">
            <a class="nav-link" href="{{ url('/') }}">BERANDA</a>
          </li>
          @foreach($menu_header as $menu)
          <li class="nav-item {{ $menu->childs->count() ? 'dropdown' : '' }}">
            <a
              class="nav-link {{ $menu->childs->count() ? 'dropdown-toggle' : '' }}"
              href="{{ $menu->childs->count() ? '#' : $menu->link }}"
              {!! $menu->childs->count() ? 'data-toggle="dropdown"' : '' !!}>
              {{ $menu->name }}
              @if($menu->childs->count())
                &nbsp;<i class="fas fa-angle-down"></i>
              @endif
            </a>
            @if($menu->childs->count())
            <div class="dropdown-menu sm-menu" aria-labelledby="navbarDropdown">
              <div class="row">
                <div class="col-sm show-all">
                  <a class="dropdown-item" href="{{ route('frontend.product.list.category', ['slugCategory' => $menu->slug]) }}">LIHAT SEMUA</a>
                </div>
                <div class="col-sm sub-menu">
                  @foreach($menu->childs->sortBy('position') as $child)
                    <a class="dropdown-item" href="{{ route('frontend.product.list.category', ['slugCategory' => $child->slug]) }}">{{ $child->name }}</a>
                  @endforeach
                </div>
              </div>
            </div>
            @endif
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</header>
