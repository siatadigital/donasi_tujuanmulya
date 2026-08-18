@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
    <div class="container">
      <div class="top-nav with-border solo-top">
        <a href="#" class="back-link" onclick="window.history.back(); return false;">
          <i class="fas fa-chevron-left"></i>
          Lanjutkan Belanja
        </a>
        <a href="{{ route('frontend.home') }}" class="web-logo mx-auto">
          <img src="{{ url('img/RTL_Logo.png') }}">
        </a>
        @if ($deposit_amount)
        <p class="deposit-amount">
            Deposit: Rp. {{ number_format($deposit_amount) }}
        </p>
        @endif
      </div>
    </div>
  </header>
@endsection

@section('content')
<div class="container">
  <div class="checkout-logreg" @if(auth()->check()) style="margin-bottom: 0;" @endif>
    <h1>Checkout</h1>
    @if(auth()->guest())
      <p>
        Silahkan Login atau Daftar menjadi member terlebih dahulu untuk melanjutkan proses belanja.
      </p>
      <a href="{{ route('frontend.auth') }}" class="btn">Login / Daftar</a>
    @endif
  </div>

  <form method="post" action="{{ route('frontend.checkout.step1.post') }}" class="checkout-guest-form">
    {{ csrf_field() }}
    @if(auth()->guest())
      <p>Atau isi form di bawah ini untuk melanjutkan proses belanja<br>sebagai tamu.</p>
      <div class="form-group">
        <input type="text" name="origin_fullname" class="form-control" id="origin_fullname" placeholder="Nama Lengkap" value="{{ $delivery ? $delivery->origin_fullname : '' }}" required>
        <label class="norm-label" for="origin_fullname">Nama Lengkap</label>
      </div>
      <div class="form-group">
        <input type="email" name="origin_email" class="form-control" id="origin_email" placeholder="Alamat Email" value="{{ $delivery ? $delivery->origin_email : '' }}" required>
        <label class="norm-label" for="origin_email">Alamat Email</label>
      </div>
      <div class="form-group">
        <input type="text" name="origin_phone" class="form-control no-spinner" id="origin_phone" placeholder="Nomor HP" value="{{ $delivery ? $delivery->origin_phone : '' }}" required>
        <label class="norm-label" for="origin_phone">Nomor HP</label>
      </div>
    @else
      <p>Form di bawah ini otomatis telah terisi untuk melanjutkan proses belanja<br>sebagai member.</p>
      <div class="form-group">
        <input type="text" name="origin_fullname" class="form-control" id="origin_fullname" placeholder="Nama Lengkap" value="{{ auth()->guest() ? '' : auth()->user()->fullname }}" readonly required>
        <label class="norm-label" for="origin_fullname">Nama Lengkap</label>
      </div>
      <div class="form-group">
        <input type="email" name="origin_email" class="form-control" id="origin_email" placeholder="Alamat Email" value="{{ auth()->guest() ? '' : auth()->user()->email }}" readonly required>
        <label class="norm-label" for="origin_email">Alamat Email</label>
      </div>
      <div class="form-group">
        <input type="text" name="origin_phone" class="form-control no-spinner" id="origin_phone" placeholder="Nomor HP" value="{{ auth()->guest() ? '' : auth()->user()->phone }}" readonly required>
        <label class="norm-label" for="origin_phone">Nomor HP</label>
      </div>
    @endif
    <div class="dashed-title">
      <span>Kota/Kabupaten</span>
    </div>
    <div class="form-group">
      <select name="origin_subdistrict_id" class="form-control checkout-select" id="origin_subdistrict_id" data-url="{{ route('ajax.subdistricts') }}" required>
        <option value="">Cari Kecamatan</option>
        @if ($delivery && $delivery->originSubdistrict)
            <option value="{{ $delivery->origin_subdistrict_id }}" selected>
                {{ $delivery->originSubdistrict->province->name }} - {{ $delivery->originSubdistrict->city->name }} - {{ $delivery->originSubdistrict->name }}
            </option>
        @endif
      </select>
    </div>
    @if(auth()->guest())
      <div class="form-group">
        <textarea class="form-control" id="origin_address" name="origin_address" rows="3" placeholder="Alamat Lengkap Pembeli" required>{{ $delivery ? $delivery->origin_address : '' }}</textarea>
        <label class="norm-label" for="origin_address">Alamat Lengkap Pembeli</label>
      </div>
    @else
      <div class="form-group">
        <textarea class="form-control" id="origin_address" name="origin_address" rows="3" placeholder="Alamat Lengkap Pembeli" required>{{ auth()->guest() ? '' : auth()->user()->address }}</textarea>
        <label class="norm-label" for="origin_address">Alamat Lengkap Pembeli</label>
      </div>
    @endif
    <p class="text-center">
      <input type="submit" class="btn" value="Lanjutkan">
    </p>
  </form>
</div>
@endsection
