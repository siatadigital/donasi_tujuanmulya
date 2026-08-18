@extends('admin.master')

@section('title', 'RTL - Tambah Pengaturan')

@section('content')
<div class="section-header">
  <h1>Tambah Pengaturan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.settings.index') }}">Pengaturan</a></div>
    <div class="breadcrumb-item">Tambah Pengaturan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Pengaturan</h2>
  <p class="section-lead">
    Form untuk tambah pengaturan
  </p>

  <div class="card">
    <form action="{{ route('admin.settings.store') }}" method="POST">
      <div class="card-header">
        <h4>Tambah Pengaturan</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        @component('admin.components.form-input', [
            'label' => 'Kunci',
            'type' => 'text',
            'name' => 'key',
            'required' => TRUE,
            'value' => old('key'),
            'error' => $errors->first('key'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Nilai',
            'type' => 'text',
            'name' => 'value',
            'required' => TRUE,
            'value' => old('value'),
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
