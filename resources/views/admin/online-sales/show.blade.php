@extends('admin.master')

@section('title', 'RTL - Detil Penjualan')

@section('content')
<div class="section-header">
  <h1>Detil Penjualan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.online-sales.index') }}">Penjualan</a></div>
    <div class="breadcrumb-item">Detil Penjualan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Penjualan</h2>
  <p class="section-lead">
    Form untuk detil penjualan
  </p>

  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <h4>Detil Penjualan</h4>
        </div>
        <div class="card-body">
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kode</label>
            <div class="col-sm-12 col-md-7">
              {{ $sales->code }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Tanggal</label>
            <div class="col-sm-12 col-md-7">
              {{ $sales->date }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">User</label>
            <div class="col-sm-12 col-md-7">
              {{ $sales->user ? $sales->user->username : 'Tidak Ada' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Customer</label>
            <div class="col-sm-12 col-md-7">
              {{ $sales->delivery ? $sales->delivery->destination_fullname : 'Belum ada nama penerima' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Subtotal</label>
            <div class="col-sm-12 col-md-7">
              {{ 'Rp. ' . number_format($sales->total_price) }}
            </div>
          </div>
          @if ($sales->coupon_id)
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kupon ({{ $sales->coupon_discount_percent }}%)</label>
            <div class="col-sm-12 col-md-7">
              <p class="mb-0">{{ $sales->coupon_name }}</p>
              <p class="mb-0"><strong>{{ $sales->coupon_code }}</strong></p>
              <p>{{ 'Rp. ' . number_format($sales->total_price * $sales->coupon_discount_percent / 100) }}</p>
            </div>
          </div>
          @endif
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Harga</label>
            <div class="col-sm-12 col-md-7">
            {{ 'Rp. ' . number_format($sales->total_price - ($sales->total_price * $sales->coupon_discount_percent / 100)) }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Berat</label>
            <div class="col-sm-12 col-md-7">
              {{ number_format($sales->total_weight) . 'g' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Catatan</label>
            <div class="col-sm-12 col-md-7">
              {{ $sales->notes }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Status</label>
            <div class="col-sm-12 col-md-7">
              {{ $sales->status_name }}
            </div>
          </div>
          <form action="{{ route('admin.online-sales.status.update', ['id' => $sales->id]) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <select name="status_id" class="form-control mb-2">
            @foreach($statuses as $status)
                <option value="{{ $status->id }}" {{ $status->id == $sales->status_id ? 'selected' : '' }}>{{ $status->name }}</option>
            @endforeach
            </select>
            <button class="btn btn-primary btn-block">Ubah</button>
          </form>
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
                <th>Harga Jual</th>
                <th>Berat (g)</th>
                <th>Kuantitas</th>
                <th>Diskon</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sales->details as $index => $item)
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
                    <td>{{ 'Rp. ' . number_format($item->price_sell_normal) }}</td>
                    <td>{{ number_format($item->weight) . 'g' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->discount }}%</td>
                    <td>{{ 'Rp. ' . number_format($item->price_sell_normal * $item->quantity) }}</td>
                </tr>
              @endforeach
                <tr>
                    <th colspan="7"><h6 class="text-right mb-0">Subtotal</h6></th>
                    <td>{{ 'Rp. ' . number_format($sales->total_price) }}</td>
                </tr>
                <tr>
                    <th colspan="7"><h6 class="text-right mb-0">Kupon ({{ $sales->coupon_discount_percent ?: 0 }}%)</h6></th>
                    <td>{{ 'Rp. ' . number_format($sales->total_price * $sales->coupon_discount_percent / 100) }}</td>
                </tr>
                <tr>
                    <th colspan="7"><h6 class="text-right mb-0">Total</h6></th>
                    <td>{{ 'Rp. ' . number_format($sales->total_price - ($sales->total_price * $sales->coupon_discount_percent / 100)) }}</td>
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
