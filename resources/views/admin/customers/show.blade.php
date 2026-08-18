@extends('admin.master')

@section('title', 'RTL - Detil Customer')

@section('content')
<div class="section-header">
  <h1>Detil Customer</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.customers.index') }}">Customer</a></div>
    <div class="breadcrumb-item">Detil Customer</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Customer</h2>
  <p class="section-lead">
    Form untuk detil customer
  </p>

  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4>Detil Customer</h4>
        </div>
        <div class="card-body">
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Nama Lengkap</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->fullname }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Username</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->username }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Email</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->email }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">No. Telp</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->phone }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Alamat</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->address }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Aktif</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->is_active ? 'Ya' : 'Tidak' }}
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h4>Customer</h4>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.customers.deposits.index', ['id' => $user->id]) }}" class="btn btn-primary">Deposit</a>
            <br><br>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Jumlah Deposit</label>
                <div class="col-sm-12 col-md-7">
                    {{ 'Rp. ' . number_format($user->customer ? $user->customer->current_deposit_amount : 0) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Jumlah Poin</label>
                <div class="col-sm-12 col-md-7">
                    {{ number_format($user->customer ? $user->customer->current_point_amount : 0) }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Jumlah Order</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->ordered_count ?: 'Tidak Ada' }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Mulai Order Pada</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->start_ordered_at ?: 'Tidak Ada' }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Reseller</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->is_reseller ? 'Ya' : 'Tidak' }}
                </div>
            </div>
            @if ($user->customer->is_reseller)
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Kode Reseller</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->reseller_code }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-5 col-lg-5">Masa Kadaluarsa Reseller</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->reseller_expired_at }}
                </div>
            </div>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
