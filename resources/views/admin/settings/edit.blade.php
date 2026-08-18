@extends('admin.master')

@section('title', 'RTL - Ubah Pengaturan')

@section('content')
<div class="section-header">
  <h1>Ubah Pengaturan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></div>
    <div class="breadcrumb-item">Ubah Pengaturan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Pengaturan</h2>
  <p class="section-lead">
    Form untuk tambah pengaturan
  </p>

  <div class="card">
    <form action="{{ route('admin.settings.update', ['id' => $setting->id]) }}" method="POST">
      <div class="card-header">
        <h4>Ubah Pengaturan</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @component('admin.components.form-input', [
            'label' => 'Kunci',
            'type' => 'text',
            'name' => 'key',
            'required' => TRUE,
            'value' => $setting->key,
            'error' => $errors->first('key'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Nilai',
            'type' => 'text',
            'name' => 'value',
            'required' => TRUE,
            'value' => $setting->value,
            'error' => $errors->first('value'),
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
