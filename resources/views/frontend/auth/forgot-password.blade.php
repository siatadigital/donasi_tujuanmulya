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
    <p>Anda akan menerima Email untuk memulihkan password anda.</p>

    <form method="POST" action="{{ route('frontend.auth.forgot_password.post') }}" class="forgot-pass-form">
      {{ csrf_field() }}
      <div class="form-group">
        <input type="email" id="email-lupa-pass" name="email" class="form-control" placeholder="Email">
        <label class="norm-label">Email</label>
      </div>
      <input type="submit" class="btn" value="Konfirmasi">
    </form>
  </div>
</div>
@endsection
