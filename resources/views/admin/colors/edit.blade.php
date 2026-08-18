@extends('admin.master')

@section('title', 'RTL - Ubah Warna')

@section('content')
<div class="section-header">
  <h1>Ubah Warna</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.colors.index') }}">Warna</a></div>
    <div class="breadcrumb-item">Ubah Warna</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Warna</h2>
  <p class="section-lead">
    Form untuk ubah warna
  </p>

  <div class="card">
    <form action="{{ route('admin.colors.update', ['id' => $color->id]) }}" method="POST">
      <div class="card-header">
        <h4>Ubah Warna</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" />

        @component('admin.components.form-input', [
            'label' => 'Nama',
            'type' => 'text',
            'name' => 'name',
            'required' => TRUE,
            'value' => $color->name,
            'error' => $errors->first('name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Kode Hex',
            'type' => 'color',
            'name' => 'hex_code',
            'required' => TRUE,
            'value' => $color->hex_code,
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
