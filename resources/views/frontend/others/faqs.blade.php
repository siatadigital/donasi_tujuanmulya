@extends('frontend.master')

<?php $routeName = Route::currentRouteName(); ?>

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      <div class="col-md-3">
        <div class="history-side-content">
          <div class="title">
            Informasi dan Bantuan
          </div>
        </div>
        <div class="history-side-links">
          <div>
            <a href="{{ route('frontend.others.about') }}">Tentang Kami</a>
          </div>
          <div>
            <a href="{{ route('frontend.others.faqs') }}" class="active">FAQs</a>
          </div>
          <div>
            <a href="{{ route('frontend.others.terms_and_agreements') }}">Syarat dan Ketentuan</a>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="informasi-bantuan">
          <img src="img/syarat-ketentuan.png">
          <form class="search-faqs">
            <div class="form-row">
              <h5>FAQs</h5>
              <div class="search-box">
                <i class="fas fa-search"></i>
                <input class="form-control" type="search" placeholder="Search" aria-label="Search">
              </div>
              <input type="submit" class="btn" value="Cari">
            </div>
          </form>
          <div class="faqs-info" id="accordion">
            @foreach($content as $index => $item)
            <?php $part = explode('#?', $item) ?>
            <div class="card">
              <button id="heading{{ $index }}" class="card-header" data-toggle="collapse" data-target="#collapse{{ $index }}"
                aria-expanded="true" aria-controls="collapse{{ $index }}">
                {{ $part[0] }}
                <i class="fas fa-angle-down"></i>
              </button>

              <div id="collapse{{ $index }}" class="collapse" aria-labelledby="heading{{ $index }}" data-parent="#accordion">
                <div class="card-body">{!! $part[1] !!}</div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
