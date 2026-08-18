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
    <h1>Reset Password</h1>
    <p>Silahkan masukkan pasword lama dan password baru anda!</p>

    <form method="POST" method="frontend.auth.reset_password.post" class="reset-pass-form">
      {{ csrf_field() }}
      <div class="form-group">
        @if ($errors->has('password'))
            <span style="color: red;">{{ $errors->first('password') }}</span>
        @endif
        <input type="password" id="new-pass" name="password" class="form-control" placeholder="Password Baru">
        <label class="norm-label">Password Baru</label>
      </div>

      <div class="form-group">
        <input type="password" id="confirm-new-pass" name="password_confirmation" class="form-control"
          placeholder="Konfirmasi Password Baru">
        <label class="norm-label">Konfirmasi Password Baru</label>
      </div>

      <input type="submit" class="btn" value="Reset Password">
    </form>
  </div>
</div>
@endsection
