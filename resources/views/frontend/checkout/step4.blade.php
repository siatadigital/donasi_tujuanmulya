@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
    <div class="container">
      <div class="top-nav with-border solo-top">
        <a href="#" class="back-link" onclick="window.history.back(); return false;">
          <i class="fas fa-chevron-left"></i>
          Ubah Informasi Tujuan Pengiriman
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
          <div class="lines active"></div>
        </div>
        <div class="col-4 px-0">
          <div class="lines active"></div>
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
          <div class="info-box active">
            <div class="circle"></div>
            <div class="text">
              Checkout
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box active">
            <div class="circle "></div>
            <div class="text">
              Pembayaran
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="metode-bayar-head">
    <h1>Metode Pembayaran</h1>
    <p>
      Pilih metode pembayaran yang ingin anda gunakan. Anda dapat melakukan pembayaran melalui instansi berikut.
    </p>
  </div>

  <form method="POST" action="{{ route('frontend.checkout.step4.post') }}" class="form-metode-bayar">
    {{ csrf_field() }}
    <div class="row">
      @foreach($banks as $index => $bank)
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="form-check btn pil-bank">
          <input class="form-check-input" type="radio" name="bank_id" id="pil-bank{{ $index + 1 }}" value="{{ $bank->id }}">
          <label class="form-check-label" for="pil-bank{{ $index + 1 }}"></label>
          <img src="{{ $bank->getLogo() }}">
          <i class="fas fa-check"></i>
        </div>
      </div>
      @endforeach
      @if ($isCashEnabled)
      <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="form-check btn pil-bank">
          <input class="form-check-input" type="radio" name="bank_id" id="pil-bank{{ count($banks) + 1 }}" value="0">
          <label class="form-check-label" for="pil-bank{{ count($banks) + 1 }}"></label>
          <p style="margin:0px">Tunai</p>
          <i class="fas fa-check"></i>
        </div>
      </div>
      @endif
    </div>
    <input type="submit" class="btn" value="Bayar Sekarang">
  </form>
</div>
@endsection
