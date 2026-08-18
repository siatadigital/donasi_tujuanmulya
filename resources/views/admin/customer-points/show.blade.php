@extends('admin.master')

@section('title', 'RTL - Detil Customer')

@section('content')
<div class="section-header">
  <h1>Detil Customer</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.customer-points.index') }}">Poin Customer</a></div>
    <div class="breadcrumb-item">Detil Poin Customer</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil Poin Customer</h2>
  <p class="section-lead">
    Form untuk detil poin customer
  </p>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Detil Poin Customer</h4>
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
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Jumlah Poin</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->current_point_amount }} poin
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Tanggal Berlaku</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->point_valid_from ?: 'Belum Belanja' }}
                </div>
            </div>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Tanggal Kadaluarsa</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->customer->point_expired_at ?: 'Belum Belanja' }}
                </div>
            </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Histori Klaim Hadiah</h4>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nama Hadiah</th>
                    <th>Target</th>
                    <th>Diberikan</th>
                    <th>Tanggal Klaim</th>
                </tr>
            </thead>
            <tbody>
                @if ($claimedRewards->count())
                    @foreach ($claimedRewards as $index => $claimed)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td width="140px"><img src="{{ $claimed->reward->getPhoto() }}" class="table-thumb mt-4 mb-4"></td>
                        <td>{{ $claimed->reward->title }}</td>
                        <td>{{ $claimed->target_point }} poin</td>
                        <td>{{ $claimed->is_given ? 'Sudah' : 'Belum' }}</td>
                        <td>{{ $claimed->created_at }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">
                            <p class="text-center mt-4">Tidak ada hadiah diklaim.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
