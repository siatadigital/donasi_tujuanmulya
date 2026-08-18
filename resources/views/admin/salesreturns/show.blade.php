@extends('admin.master')

@section('title', 'RTL - Detil Pengembalian')

@section('content')
<div class="section-header">
  <h1>Detil Pengembalian</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.salesreturns.index') }}">Pengembalian</a></div>
    <div class="breadcrumb-item">Detil Pengembalian</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Pengembalian</h2>
  <p class="section-lead">
    Form untuk detil pengembalian
  </p>

  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <h4>Detil Pengembalian</h4>
        </div>
        <div class="card-body">
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kode</label>
            <div class="col-sm-12 col-md-7">
              {{ $salesreturn->code }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Tanggal</label>
            <div class="col-sm-12 col-md-7">
              {{ $salesreturn->date }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">User</label>
            <div class="col-sm-12 col-md-7">
              {{ $salesreturn->user ? $salesreturn->user->username : 'Tidak Ada' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Customer</label>
            <div class="col-sm-12 col-md-7">
              {{ $salesreturn->customer ? $salesreturn->customer->user->username : 'guest' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Harga</label>
            <div class="col-sm-12 col-md-7">
            {{ 'Rp. ' . number_format($salesreturn->total_price) }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Berat</label>
            <div class="col-sm-12 col-md-7">
              {{ number_format($salesreturn->total_weight) . 'g' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Catatan</label>
            <div class="col-sm-12 col-md-7">
              {{ $salesreturn->notes }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="card">
        <div class="card-header">
          <h4>Produk</h4>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Produk</th>
                <th>Harga Jual</th>
                <th>Berat (g)</th>
                <th>Kuantitas</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($salesreturn->details as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><p>{{ $item->product->title }}</p></td>
                    <td>{{ 'Rp. ' . number_format($item->price_sell_normal) }}</td>
                    <td>{{ number_format($item->weight) . 'g' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ 'Rp. ' . number_format($item->price_sell_normal * $item->quantity) }}</td>
                </tr>
              @endforeach
                <tr>
                    <th colspan="5"><h6 class="text-right mb-0">Total</h6></th>
                    <td>{{ 'Rp. ' . number_format($salesreturn->total_price) }}</td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
@if (session()->has('message'))
<script>
  iziToast.success({
    title: 'Berhasil!',
    message: '{{ session("message") }}',
    position: 'topRight'
  });
</script>
@endif
@endsection
