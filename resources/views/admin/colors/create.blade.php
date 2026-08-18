@extends('admin.master')

@section('title', 'RTL - Tambah Warna')

@section('content')
<div class="section-header">
  <h1>Tambah Warna</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.colors.index') }}">Warna</a></div>
    <div class="breadcrumb-item">Tambah Warna</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Warna</h2>
  <p class="section-lead">
    Form untuk tambah warna
  </p>

  <div class="card">
    <form action="{{ route('admin.colors.store') }}" method="POST">
      <div class="card-header">
        <h4>Tambah Warna</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        @component('admin.components.form-input', [
            'label' => 'Nama',
            'type' => 'text',
            'name' => 'name',
            'required' => TRUE,
            'value' => old('name'),
            'error' => $errors->first('name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Kode Hex',
            'type' => 'color',
            'name' => 'hex_code',
            'required' => TRUE,
            'value' => old('hex_code'),
            'error' => $errors->first('hex_code'),
        ])
        @endcomponent
      </div>
      <div class="card-footer text-right">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
