@extends('admin.master')

@section('title', 'RTL - Detil Pengiriman')

@section('content')
<div class="section-header">
  <h1>Detil Pengiriman</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.deliveries.index') }}">Pengiriman</a></div>
    <div class="breadcrumb-item">Detil Pengiriman</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Pengiriman</h2>
  <p class="section-lead">
    Form untuk detil pengiriman
  </p>

  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4>Detil Pengiriman</h4>
        </div>
        <div class="card-body">
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kode</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->code }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Tanggal</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->date }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">User</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->user ? $delivery->user->username : 'Tidak Ada' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kode Pemesanan</label>
            <div class="col-sm-12 col-md-7">
              <a href="{{ route('admin.sales.show', ['id' => $delivery->sales_id]) }}">{{ $delivery->sales->code }}</a>
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">No. Resi</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->expedition_number ?: '-' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Nama Kurir</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->courier_info }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Layanan Kurir</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->courier_service_info }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Estimasi</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->courier_estd }} hari
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Ongkos Kirim</label>
            <div class="col-sm-12 col-md-7">
                Rp. {{ number_format($delivery->courier_cost, 2) }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Total Berat</label>
            <div class="col-sm-12 col-md-7">
              {{ number_format($delivery->total_weight, 2) . 'g' }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Catatan</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->notes ?: '-' }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4>Info Pembeli</h4>
        </div>
        <div class="card-body">
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Nama Lengkap</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->origin_fullname }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Email</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->origin_email }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">No. HP</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->origin_phone }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Alamat</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->origin_address }}
              <br>
              {{ $delivery->originCity->province->name }}, {{ $delivery->originCity->name }}
              <br>
              {{ $delivery->origin_postcode }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4>Tujuan Pengiriman</h4>
        </div>
        <div class="card-body">
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Nama Lengkap</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->destination_fullname }}
            </div>
          </div>
          <!-- <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Email</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->destination_email }}
            </div>
          </div> -->
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">No. HP</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->destination_phone }}
            </div>
          </div>
          <div class="form-group row mb-4">
            <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Alamat</label>
            <div class="col-sm-12 col-md-7">
              {{ $delivery->destination_address }}
              <br>
              {{ $delivery->destinationCity->province->name }}, {{ $delivery->destinationCity->name }}
              <br>
              {{ $delivery->destination_postcode }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
            <th>Berat (g)</th>
            <th>Kuantitas</th>
            <th>Total Berat</th>
          </tr>
        </thead>
        <tbody>
          @foreach($delivery->sales->details as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>
              <p>{{ $item->product->title }}</p>
            </td>
            <td>
              <div title="{{ $item->color->name }}"
                style="width:24px;height:24px;background:{{ $item->color->hex_code }};">
              </div>
            </td>
            <td>{{ number_format($item->weight, 2) . 'g' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->weight * $item->quantity) . 'g' }}</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="4"></td>
            <th>Total Berat</th>
            <td>{{ number_format($delivery->total_weight) . 'g' }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
