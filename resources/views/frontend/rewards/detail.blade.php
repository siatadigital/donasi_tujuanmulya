@extends('frontend.master')
@php
  $isExists = $reward->photo && file_exists(public_path('uploads/rewards/' . $reward->photo));
  $filename = $isExists ? $reward->photo : 'default.png';
  $srcMain = url('uploads/rewards/' . $filename);
@endphp

@section('meta')
<title>{{ $settings['WEB_TITLE'] }} | {{ $reward->title }}</title>
<meta name="description" content="{{ substr(strip_tags($reward->description), 0, 250) }}">

<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{ $settings['WEB_TITLE'] }} | {{ $reward->title }}" />
<meta property="og:site_name" content="{{ $settings['WEB_TITLE'] }} | {{ $reward->title }}">
<meta property="og:description" content="{{ substr(strip_tags($reward->description), 0, 250) }}" />
<meta property="og:image" itemprop="image" content="{{ $srcMain }}" />
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="300">
<meta property="og:image:height" content="300">
@endsection

@section('content')
<div class="container">
  <div class="product-detail-breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumbs as $breadcrumb)
            <li
                class="breadcrumb-item {{ $breadcrumb->is_last ? 'active' : '' }}"
                {{ $breadcrumb->is_last ? 'aria-current="page"' : '' }}
            >
                <a {!! !$breadcrumb->is_last ? "href=\"{$breadcrumb->link}\"" : '' !!}>
                    {{ $breadcrumb->label }}
                </a>
            </li>
            @endforeach
        </ol>
    </nav>
  </div>

  <div class="product-detail-main">
    <div class="row">
      <div class="col-lg-5 col-md-6">
        <div class="product-detail-preview">
          <img id="main-img-prev" class="main-prev" src="{{ $srcMain }}">
        </div>
      </div>
      <div class="col-lg-7 col-md-6">
        @if (auth()->check())
        <p>Poin Saya : <span id="my-point">{{ auth()->user()->customer ? auth()->user()->customer->current_point_amount : 0 }}</span> poin</p>
        @endif
        <h4 class="product-detail-name" style="margin-bottom:8px;">{{ $reward->title }}</h4>

        <div class="product-detail-price">
          <span id="price-main" class="promo">{{ number_format($reward->target_point) }} Poin</span>
        </div>
        <form method="post" action="{{ route('ajax.reward.claim', array('slug' => $reward->slug)) }}" id="product-detail-form">
          {{ csrf_field() }}
          <input type="hidden" name="reward_id" value="{{ $reward->id }}">
          @if ($errors->any())
              @foreach ($errors->all() as $error)
                  <div>{{$error}}</div>
              @endforeach
          @endif
          <div id="spinner" style="display:none;">
              <div class="d-flex justify-content-center">
                  <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="margin-bottom:48px;">
              </div>
          </div>
          <div class="product-detail-info buttons">
              <a
                  class="btn addtocart"
                  href="#"
                  data-href="{{ route('ajax.reward.claim', array('slug' => $reward->slug)) }}"
              >
                  <i class="fas fa-shopping-bag"></i>
                  Klaim Hadiah
              </a>
          </div>
          <div class="product-detail-info">
            <h5 class="head">Deskripsi Hadiah</h5>
            <div class="main">
              <p>
                {!! $reward->description !!}
              </p>
            </div>
          </div>
          <div class="product-detail-share">
            <h5 class="head">Bagikan Kepada Teman</h5>
            <a target="_blank" href="https://www.facebook.com/sharer.php?u={{ route('frontend.reward.detail', array('slug' => $reward->slug)) }}"><img src="{{ url('img/facebook.png') }}"> Facebook</a>
            <a target="_blank" href="https://twitter.com/intent/tweet?text={{ $reward->title }}&url={{ route('frontend.reward.detail', array('slug' => $reward->slug)) }}"><img src="{{ url('img/twitter.png') }}"> Twitter</a>
            <a target="_blank" href="https://api.whatsapp.com/send?text={{ $reward->title }} {{ route('frontend.reward.detail', array('slug' => $reward->slug)) }}"><img src="{{ url('img/whatsapp.png') }}"> WhatsApp</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
  <script>
    $(document).ready(function(){
      $('a.btn.addtocart').click(function(){
        var url = $(this).data('href');
        var data = $('#product-detail-form').serialize();

        $('#spinner').show();
        $('.product-detail-info.buttons').hide();

        $.ajax({
            method: "POST",
            url: url,
            data: data,
            success: function(response) {
                $('#spinner').hide();
                $('.product-detail-info.buttons').show();
                $('#my-point').text(response.data);

                swal({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Berhasil mengklaim hadiah dan harap tunggu Admin menghubungi Anda',
                });
            },
            error: function(error) {
                $('#spinner').hide();
                $('.product-detail-info.buttons').show();

                const message = error.status === 400 ? error.responseJSON.error : 'Gagal mengklaim hadiah';

                swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                });
            }
        });

        return false;
      });
    });
  </script>
@endsection
