@extends('admin.master')

@section('title', 'RTL - Detil User')

@section('content')
<div class="section-header">
  <h1>Detil User</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.index') }}">User</a></div>
    <div class="breadcrumb-item">Detil User</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Detil User</h2>
  <p class="section-lead">
    Form untuk detil user
  </p>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Detil User</h4>
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
                <label class="col-form-label text-md-left col-12 col-md-3 col-lg-3">Bagian</label>
                <div class="col-sm-12 col-md-7">
                    {{ $user->type->name }}
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
  </div>
</div>
@endsection
