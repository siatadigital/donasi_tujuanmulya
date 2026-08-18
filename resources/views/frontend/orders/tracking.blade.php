@extends('frontend.master')

@section('content')
<div class="container">
  <div class="tracking-order-head">
    <h1>Periksa Status Pemesanan</h1>
    @if (session()->has('failed'))
    <p>Maaf, kami tidak dapat menemukan pesanan dengan kode <strong>{{ session('failed') }}</strong>.</p>
    @else
    <p>
      Anda dapat memeriksa status pemesanan dengan menggunakan kode yang telah kami kirimkan melalui email anda.
    </p>
    @endif
  </div>

  <form method="POST" action="{{ route('frontend.order.tracking.post') }}" class="tracking-order-form">
    {{ csrf_field() }}
    <div class="form-group">
      @if ($errors->has('code'))
      <p class="text-center">{{ $errors->first('code') }}</p>
      @endif
      <input type="text" id="kode-pemesanan" name="code" class="form-control" placeholder="Kode Pemesanan">
      <label class="norm-label">Kode Pemesanan</label>
    </div>
    <input type="submit" class="btn" value="Periksa Status">

  </form>
</div>
@endsection
