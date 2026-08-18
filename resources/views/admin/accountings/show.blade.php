@extends('admin.master')

@section('title', 'RTL - Detil Accounting')

@section('content')
<div class="section-header">
  <h1>Detil Accounting</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.accountings.index') }}">Accounting</a></div>
    <div class="breadcrumb-item">Detil Accounting</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Accounting</h2>
  <p class="section-lead">
    Form untuk detil accounting
  </p>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Detil Accounting</h4>
        </div>
        <div class="card-body">
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Kategori</label>
                <div class="col-sm-12 col-md-7">
                    {{ $accounting->category->name }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">User</label>
                <div class="col-sm-12 col-md-7">
                    {{ $accounting->user ? $accounting->user->username : 'Tidak Ada' }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Uang Keluar</label>
                <div class="col-sm-12 col-md-7">
                    {{ 'Rp. ' . number_format($accounting->amount_out) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Uang Masuk</label>
                <div class="col-sm-12 col-md-7">
                    {{ 'Rp. ' . number_format($accounting->amount_in) }}
                </div>
            </div>
            <!-- <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Uang Saat Ini</label>
                <div class="col-sm-12 col-md-7">
                {{ 'Rp. ' . number_format($accounting->current_amount) }}
                </div>
            </div> -->
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Deskripsi</label>
                <div class="col-sm-12 col-md-7">
                    {{ $accounting->description ?: 'Tidak Ada' }}
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
