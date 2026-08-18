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
            <a href="{{ route('frontend.others.about') }}" class="active">Tentang Kami</a>
          </div>
          <div>
            <a href="{{ route('frontend.others.faqs') }}">FAQs</a>
          </div>
          <div>
            <a href="{{ route('frontend.others.terms_and_agreements') }}">Syarat dan Ketentuan</a>
          </div>
        </div>
      </div>
      <div class="col-md-9">
        <div class="informasi-bantuan">
          <img src="img/about.png">
          {!! $content !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
