@extends('admin.master')

@section('title', 'RTL - Detil Produk')

@section('content')
<div class="section-header">
  <h1>Detil Produk</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.index') }}">Produk</a></div>
    <div class="breadcrumb-item">Detil Produk</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Produk</h2>
  <p class="section-lead">
    Form untuk detil produk
  </p>

  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4>Detil Produk</h4>
        </div>
        <div class="card-body">
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Nama Produk</label>
                <div class="col-sm-12 col-md-7">
                    {{ $product->title }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Warna</label>
                <div class="col-sm-12 col-md-7">
                    @foreach($product->productColors as $productColor)
                    <div style="display:flex;align-items:center;">
                        <span style="margin-right: 8px;">{{ $productColor->color->name }}</span>
                        <div style="width:24px;height:24px;margin-right:12px;background:{{ $productColor->color->hex_code }};" title="{{ $productColor->color->name }}"></div>
                        <span style="margin-right: 8px;">Gudang (x{{ $productColor->current_total_stock_warehouse }})</span>
                        <span style="margin-right: 8px;">Toko (x{{ $productColor->current_total_stock_store }})</span>
                        <span style="margin-right: 8px;">Shopee (x{{ $productColor->current_total_stock_shopee }})</span>
                        <span>Barcode (<strong>{{ $productColor->barcode }}</strong>)</span>
                    </div>
                    <br>
                    @endforeach
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Kategori</label>
                <div class="col-sm-12 col-md-7">
                    {{ $product->productCategories->pluck('category.name')->join(', ') }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Lookbook</label>
                <div class="col-sm-12 col-md-7">
                    {{ $product->productLookbooks->pluck('lookbook.name')->join(', ') }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Harga Beli</label>
                <div class="col-sm-12 col-md-7">
                    Rp. {{ number_format($product->price_buy) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Harga Jual (Normal)</label>
                <div class="col-sm-12 col-md-7">
                    Rp. {{ number_format($product->price_sell_normal) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Harga Jual (Reseller)</label>
                <div class="col-sm-12 col-md-7">
                    Rp. {{ number_format($product->price_sell_reseller) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Harga Jual (Seri)</label>
                <div class="col-sm-12 col-md-7">
                    Rp. {{ number_format($product->price_sell_seri) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Diskon</label>
                <div class="col-sm-12 col-md-7">
                    {{ $product->discount ?: 0 }}%
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Berat</label>
                <div class="col-sm-12 col-md-7">
                    {{ $product->weight }}g
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Bisa Pre-Order</label>
                <div class="col-sm-12 col-md-7">
                    {{ $product->is_open_preorder ? 'Ya' : 'Tidak' }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Total Stok Gudang</label>
                <div class="col-sm-12 col-md-7">
                    {{ number_format($product->current_total_stock_warehouse) }} pcs
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Total Stok Toko</label>
                <div class="col-sm-12 col-md-7">
                    {{ number_format($product->current_total_stock_store) }} pcs
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Total Stok Shopee</label>
                <div class="col-sm-12 col-md-7">
                    {{ number_format($product->current_total_stock_shopee) }} pcs
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Deskripsi</label>
                <div class="col-sm-12 col-md-7">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4>Foto</h4>
        </div>
        <div class="card-body">
        @foreach(explode('|', $product->photos) as $photo)
        <img style="width:100%;object-fit:cover;margin-bottom:8px;border:1px solid #ddd;" src="{{ url('uploads/products/small/' . $photo) }}" alt="{{ $product->title }}">
        @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
