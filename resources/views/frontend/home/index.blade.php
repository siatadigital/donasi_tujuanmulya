@extends('frontend.master')

@section('content')
<div class="home-slider">
  <div id="home-slider" class="owl-carousel owl-theme">
    @foreach($sliders as $item)
      @php
        $isExists = $item->photo && file_exists(public_path('uploads/sliders/' . $item->photo));
        $filename = $isExists ? $item->photo : 'default.png';
        $src = url('uploads/sliders/' . $filename);
      @endphp
      <div class="item">
        <a href="{{ $item->link }}" target="_blank">
          <img src="{{ $src }}">
        </a>
      </div>
    @endforeach
  </div>
</div>
<br/>
<br/>
<div class="kategori-produk">
  <div class="container">
    <h1>Kategori Produk</h1>
    <div class="row">
      <div class="col-md-6">
        @foreach($categories_first as $item)
          @php
            $isExists = $item->photo && file_exists(public_path('uploads/categories/thumb/' . $item->photo));
            $filename = $isExists ? $item->photo : 'default.png';
            $src = url('uploads/categories/thumb/' . $filename);
          @endphp
          <a href="{{ route('frontend.product.list.category', ['slugCategory' => $item->slug]) }}" class="kategory-box big">
            <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;">
              <div class="overlay"></div>
            </div>
            <div class="text-box">
              <span>{{ $item->name }}</span>
            </div>
          </a>
        @endforeach
      </div>
      <div class="col-md-6">
        @foreach($categories as $item)
          @php
            $isExists = $item->photo && file_exists(public_path('uploads/categories/thumb/' . $item->photo));
            $filename = $isExists ? $item->photo : 'default.png';
            $src = url('uploads/categories/thumb/' . $filename);
          @endphp
          <a href="{{ route('frontend.product.list.category', ['slugCategory' => $item->slug]) }}" class="kategory-box small">
            <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;">
              <div class="overlay"></div>
            </div>
            <div class="text-box">
              <span>{{ $item->name }}</span>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="terlaris">
  <div class="container">
    <h1>Terlaris Bulan Ini</h1>
    <div class="row">
      @foreach($topProducts as $item)
        @php
          $photo = $item->photos ? explode('|', $item->photos)[0] : NULL;
          $isExists = $photo && file_exists(public_path('uploads/products/small/' . $photo));
          $filename = $isExists ? $photo : 'default.png';
          $src = url('uploads/products/small/' . $filename);
        @endphp
        <div class="col-lg-3 col-md-6 col-sm-6 col-6">
          <a href="{{ route('frontend.product.detail', array('slug' => $item->slug)) }}" class="produk-box">
            <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;">
              @if($item->discount)
                <span class="promo">PROMO!</span>
              @endif
            </div>
            <div class="text-box">
              <div class="name">{{ $item->title }}</div>
              @if($item->discount)
                <div>
                  <span class="price-promo">Rp. {{ number_format($item->price_sell_normal) }}</span>
                  <span class="price">Rp. {{ number_format($item->price_sell_normal - $item->normal_discount_amount) }}</span>
                </div>
              @else
                <div>
                  <span class="price">Rp. {{ number_format($item->price_sell_normal) }}</span>
                </div>
              @endif
            </div>
          </a>
        </div>
      @endforeach
    </div>
    <p class="text-center">
      <a href="{{ route('frontend.product.list') }}" class="btn">Lihat Produk Lainnya</a>
    </p>
  </div>
</div>

<div class="terlaris">
  <div class="container">
    <h1>Produk Terbaru</h1>
    <div class="row">
      @foreach($latestProducts as $item)
        @php
          $photo = $item->photos ? explode('|', $item->photos)[0] : NULL;
          $isExists = $photo && file_exists(public_path('uploads/products/small/' . $photo));
          $filename = $isExists ? $photo : 'default.png';
          $src = url('uploads/products/small/' . $filename);
        @endphp
        <div class="col-lg-3 col-md-6 col-sm-6 col-6">
          <a href="{{ route('frontend.product.detail', array('slug' => $item->slug)) }}" class="produk-box">
            <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;">
              @if($item->discount)
                <span class="promo">PROMO!</span>
              @endif
            </div>
            <div class="text-box">
              <div class="name">{{ $item->title }}</div>
              @if($item->discount)
                <div>
                  <span class="price-promo">Rp. {{ number_format($item->price_sell_normal) }}</span>
                  <span class="price">Rp. {{ number_format($item->price_sell_normal - $item->normal_discount_amount) }}</span>
                </div>
              @else
                <div>
                  <span class="price">Rp. {{ number_format($item->price_sell_normal) }}</span>
                </div>
              @endif
            </div>
          </a>
        </div>
      @endforeach
    </div>
    <p class="text-center">
      <a href="{{ route('frontend.product.list') }}" class="btn">Lihat Produk Lainnya</a>
    </p>
  </div>
</div>

<div class="terlaris">
  <div class="container">
    <!-- Redirect to Store page -->
    <a href="{{ $storeLink }}">
        <img src="{{ asset('uploads/pages/' . $storeImage) }}" style="width:100%;">
    </a>
  </div>
</div>

<div class="inspirasi">
  <div class="container">
    <h1>Inspirasi Terbaru</h1>
    @if (count($lookbooks) > 0)
      <div class="row">
        @php
          $isExists = $lookbooks[0]->cover_photo && file_exists(public_path('uploads/lookbooks/thumb/' . $lookbooks[0]->cover_photo));
          $filename = $isExists ? $lookbooks[0]->cover_photo : 'default.png';
          $src = url('uploads/lookbooks/thumb/' . $filename);
        @endphp
        <div class="col-md-4">
          <a href="{{ route('frontend.product.list.lookbook', ['slugLookbook' => $lookbooks[0]->slug]) }}" class="inspirasi-box big">
            <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
          </a>
        </div>
      @endif
      <div class="col-md-4">
      @if (count($lookbooks) > 1)
        @php
          $isExists = $lookbooks[1]->cover_photo && file_exists(public_path('uploads/lookbooks/thumb/' . $lookbooks[1]->cover_photo));
          $filename = $isExists ? $lookbooks[1]->cover_photo : 'default.png';
          $src = url('uploads/lookbooks/thumb/' . $filename);
        @endphp
        <a href="{{ route('frontend.product.list.lookbook', ['slugLookbook' => $lookbooks[1]->slug]) }}" class="inspirasi-box small">
          <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
        </a>
      @endif
      @if (count($lookbooks) > 2)
        @php
          $isExists = $lookbooks[2]->cover_photo && file_exists(public_path('uploads/lookbooks/thumb/' . $lookbooks[2]->cover_photo));
          $filename = $isExists ? $lookbooks[2]->cover_photo : 'default.png';
          $src = url('uploads/lookbooks/thumb/' . $filename);
        @endphp
        <a href="{{ route('frontend.product.list.lookbook', ['slugLookbook' => $lookbooks[2]->slug]) }}" class="inspirasi-box small">
          <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
        </a>
      @endif
      </div>
      @if (count($lookbooks) > 3)
        @php
          $isExists = $lookbooks[3]->cover_photo && file_exists(public_path('uploads/lookbooks/thumb/' . $lookbooks[3]->cover_photo));
          $filename = $isExists ? $lookbooks[3]->cover_photo : 'default.png';
          $src = url('uploads/lookbooks/thumb/' . $filename);
        @endphp
        <div class="col-md-4">
          <a href="{{ route('frontend.product.list.lookbook', ['slugLookbook' => $lookbooks[3]->slug]) }}" class="inspirasi-box big">
            <div class="img-box" style="background: url('{{ $src }}') no-repeat center center;"></div>
          </a>
        </div>
      @endif
    </div>
  </div>
</div>

<div class="download">
  <div class="grey-box"></div>
  <div class="container">
    <div class="texts">
      <h1>Download Aplikasinya</h1>
      <p>
        Download aplikasi Rumah Tas Lucu di Apps Store dan Playstore untuk mendapatkan pengalaman berbelanja yang lebih mudah.
      </p>
      <div class="links">
        <a href="{{ $settings['APPSTORE_LINK'] }}"><img src="{{ url('img/app-store.png') }}"></a>
        <a href="{{ $settings['PLAYSTORE_LINK'] }}"><img src="{{ url('img/google-play.png') }}"></a>
      </div>
    </div>
  </div>

</div>

<div class="subscribe">
  <div class="container">
    <h1>Subscribe Newsletter</h1>
    <p>Subscribe sekarang untuk menerima update terbaru dan diskon menarik dari kami!</p>
    <link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; width:100%;}
    </style>
    <div id="mc_embed_signup">
    <form action="https://rumahtaslucu.us5.list-manage.com/subscribe/post?u=3426f650398a5f2c5d66a48ed&amp;id=27debf3918" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
      <div id="mc_embed_signup_scroll">
        <label for="mce-EMAIL">Subscribe</label>
        <input type="email" value="" name="EMAIL" class="email form-control" id="mce-EMAIL" placeholder="email address" required>
        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_3426f650398a5f2c5d66a48ed_27debf3918" tabindex="-1" value=""></div>
        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button btn"></div>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection
