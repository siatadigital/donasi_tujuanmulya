<?php $routeName = Route::currentRouteName(); ?>

<div class="col-md-3">
  <div class="history-side-content">
    <div class="title">
      Profil Saya
    </div>
    <div class="main">
      <img src="{{ url('img/bar-code.png') }}">
      <div class="nama">{{ auth()->user()->fullname }}</div>
      <a href="{{ route('frontend.auth.logout') }}">Log Out</a>
    </div>
  </div>
  <div class="history-side-links">
    <div>
      <a href="{{ route('frontend.order.list') }}" class="{{ Str::contains($routeName, 'order.') ? 'active' : '' }}">Riwayat Pemesanan</a>
    </div>
    <div>
      <a href="{{ route('frontend.return.list') }}" class="{{ Str::contains($routeName, 'return.') ? 'active' : '' }}">Riwayat Pengembalian</a>
    </div>
    <div>
      <a href="{{ route('frontend.profile.edit') }}" class="{{ Str::contains($routeName, 'profile.edit') ? 'active' : '' }}">Pengaturan Akun</a>
    </div>
  </div>
</div>
