@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
  <div class="container">
    <div class="top-nav with-border solo-top">
      <a href="{{ route('frontend.home') }}" class="back-link">
        <i class="fas fa-chevron-left"></i>
        Kembali ke halaman utama
      </a>
      <a href="{{ route('frontend.home') }}" class="web-logo mx-auto">
        <img src="{{ $settings['WEB_LOGO'] }}">
      </a>
    </div>
  </div>
</header>
@endsection

@section('content')
<div class="container">
  <div class="forgot-reset-password">
    <h1>Lupa Password?</h1>
    <p>Kami telah mengirim petunjuk ke email anda <b>{{ session('email') }}</b></p>

    <form method="POST" action="{{ route('frontend.auth.forgot_password.post') }}" class="forgot-pass-form-response">
      {{ csrf_field() }}
      <div class="form-group">
        <input name="email" type="hidden" value="{{ session('email') }}">
        <button id="count-btn" class="btn disabled" disabled>Kirim Ulang (<span>60</span>)</button>
        <button type="submit" id="resend-btn" class="btn active">Kirim Ulang</button>
      </div>
    </form>
  </div>
</div>
@endsection
