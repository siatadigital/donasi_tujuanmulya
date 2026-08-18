@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
    <div class="container">
      <div class="top-nav with-border solo-top">
        <a href="#" class="back-link" onclick="window.history.back(); return false;">
          <i class="fas fa-chevron-left"></i>
          Ubah Informasi Pembeli
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
  <div class="grafis-checkout">
    <div class="grafis-checkout-lines">
      <div class="row mx-0">
        <div class="col-4 px-0">
          <div class="lines active"></div>
        </div>
        <div class="col-4 px-0">
          <div class="lines"></div>
        </div>
        <div class="col-4 px-0">
          <div class="lines"></div>
        </div>
      </div>
    </div>
    <div class="grafis-checkout-circle">
      <div class="row mx-0">
        <div class="col-3 px-0">
          <div class="info-box active">
            <div class="circle"></div>
            <div class="text">
              Informasi Pembeli
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box active">
            <div class="circle"></div>
            <div class="text">
              Tujuan Pengiriman
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box">
            <div class="circle"></div>
            <div class="text">
              Checkout
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box">
            <div class="circle"></div>
            <div class="text">
              Pembayaran
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="checkout-tujuan-main">
    <div class="row">
      <div class="col-md-6">
        <div class="checkout-info-pembeli">
          <h1>Informasi Pembeli</h1>
          <div class="info-box">
            <div class="head">
              Nama Lengkap
            </div>
            <div class="main nama">{{ $delivery ? $delivery->origin_fullname : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              Alamat Email
            </div>
            <div class="main email">{{ $delivery ? $delivery->origin_email : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              No. Hp
            </div>
            <div class="main noHp">{{ $delivery ? $delivery->origin_phone : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              Alamat Lengkap Pembeli
            </div>
            <div class="main">
              {!! $delivery ? $delivery->origin_address . '<br />' . $delivery->originSubdistrict->province->name . ', ' . $delivery->originSubdistrict->city->name . ', ' . $delivery->originSubdistrict->name : '' !!}
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 checkout-info-pembeli">
        <form method="post" action="{{ route('frontend.checkout.step2.post') }}" class="checkout-tujuan-form checkout-form">
          {{ csrf_field() }}
          <h1>Informasi Pengiriman</h1>
          <p>
            Apakah anda ingin menggunakan data informasi pengiriman sama seperti pada form Infomasi Pembeli?
          </p>
          <div class="tipe-data">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="tipe-data" id="tipe-data1" value="option1" checked>
              <label class="form-check-label" for="tipe-data1">
                Tidak
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="tipe-data" id="tipe-data2" value="option2">
              <label class="form-check-label" for="tipe-data2">
                Ya, Samakan dengan informasi pembeli
              </label>
            </div>
          </div>
          <div class="form-group">
            <input type="text" name="destination_fullname" class="form-control" id="destination_fullname" placeholder="Nama Lengkap" value="{{ $delivery ? $delivery->destination_fullname : '' }}" required>
            <label class="norm-label" for="destination_fullname">Nama Lengkap</label>
          </div>
          <!-- <div class="form-group">
            <input type="email" name="destination_email" class="form-control" id="destination_email" placeholder="Alamat Email" value="{{ $delivery ? $delivery->destination_email : '' }}" required>
            <label class="norm-label" for="destination_email">Alamat Email</label>
          </div> -->
          <div class="form-group">
            <input type="text" name="destination_phone" class="form-control no-spinner" id="destination_phone" placeholder="Nomor HP" value="{{ $delivery ? $delivery->destination_phone : '' }}" required>
            <label class="norm-label" for="destination_phone">Nomor HP</label>
          </div>
          <div class="dashed-title">
            <span>Kota/Kabupaten</span>
          </div>
          <div class="form-group">
            <select name="destination_subdistrict_id" class="form-control checkout-select" id="destination_subdistrict_id" data-url="{{ route('ajax.subdistricts') }}" required>
                <option value="">Cari Kecamatan</option>
                @if ($delivery && $delivery->destinationSubdistrict)
                    <option value="{{ $delivery->destination_subdistrict_id }}" selected>
                        {{ $delivery->destinationSubdistrict->province->name }} - {{ $delivery->destinationSubdistrict->city->name }} - {{ $delivery->destinationSubdistrict->name }}
                    </option>
                @endif
            </select>
          </div>
          <div class="form-group">
            <textarea class="form-control" name="destination_address" id="destination_address" rows="3" placeholder="Alamat Lengkap Pengiriman" required>{{ $delivery ? $delivery->destination_address : '' }}</textarea>
            <label class="norm-label" for="destination_address">Alamat Lengkap Pengiriman</label>
          </div>
          <div class="text-center">
            <input type="submit" class="btn" value="Checkout">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
