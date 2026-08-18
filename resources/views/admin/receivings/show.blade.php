@extends('admin.master')

@section('title', 'RTL - Detil Penerimaan')

@section('content')
<div class="section-header">
  <h1>Detil Penerimaan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.receivings.index') }}">Penerimaan</a></div>
    <div class="breadcrumb-item">Detil Penerimaan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Penerimaan</h2>
  <p class="section-lead">
    Form untuk detil penerimaan
  </p>

  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <h4>Detil Penerimaan</h4>
        </div>
        <div class="card-body">
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kode</label>
            <div class="col-sm-12 col-md-7">
              {{ $receiving->code }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Tanggal</label>
            <div class="col-sm-12 col-md-7">
              {{ $receiving->date }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">User</label>
            <div class="col-sm-12 col-md-7">
              {{ $receiving->user ? $receiving->user->username : 'Tidak Ada' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Supplier</label>
            <div class="col-sm-12 col-md-7">
              {{ $receiving->supplier->name }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Harga</label>
            <div class="col-sm-12 col-md-7">
              {{ 'Rp. ' . number_format($receiving->total_price) }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Berat</label>
            <div class="col-sm-12 col-md-7">
              {{ number_format($receiving->total_weight, 2) . 'g' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Catatan</label>
            <div class="col-sm-12 col-md-7">
              {{ $receiving->notes }}
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
                <th>Warna</th>
                <th>Harga Beli</th>
                <th>Berat (g)</th>
                <th>Kuantitas</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($receiving->details as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><p>{{ $item->product->title }}</p></td>
                    <td>
                        <div
                            title="{{ $item->color->name }}"
                            style="width:24px;height:24px;background:{{ $item->color->hex_code }};"
                        >
                        </div>
                    </td>
                    <td>{{ 'Rp. ' . number_format($item->price_buy) }}</td>
                    <td>{{ number_format($item->weight, 2) . 'g' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ 'Rp. ' . number_format($item->price_buy * $item->quantity) }}</td>
                </tr>
              @endforeach
                <tr>
                    <td colspan="5"></td>
                    <th>Total Price</th>
                    <td>{{ 'Rp. ' . number_format($receiving->total_price) }}</td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
