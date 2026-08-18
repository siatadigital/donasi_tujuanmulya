@extends('frontend.master')

@section('content')
<div class="container">
    <div class="tracking-order-head">
      <h1>Periksa Status Pemesanan</h1>
      <p>
        Anda dapat memeriksa status pemesanan dengan menggunakan kode yang telah kami kirimkan melalui email anda.
      </p>
    </div>

    <form action="{{ route('frontend.tracking.result') }}" class="tracking-order-form">
      <div class="form-group">
        <input type="text" id="kode-pemesanan" name="kode-pemesanan" class="form-control" placeholder="Kode Pemesanan">
        <label class="norm-label">Kode Pemesanan</label>
      </div>
      <input type="submit" class="btn" value="Periksa Status">
    </form>
  </div>
@endsection