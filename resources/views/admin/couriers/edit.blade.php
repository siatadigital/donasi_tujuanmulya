@extends('admin.master')

@section('title', 'RTL - Ubah Kurir')

@section('content')
<div class="section-header">
  <h1>Ubah Kurir</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.couriers.index') }}">Kurir</a></div>
    <div class="breadcrumb-item">Ubah Kurir</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Kurir</h2>
  <p class="section-lead">
    Form untuk tambah kurir
  </p>

  <div class="card">
    <form action="{{ route('admin.couriers.update', ['id' => $courier->id]) }}" method="POST">
      <div class="card-header">
        <h4>Ubah Kurir</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        @component('admin.components.form-input', [
            'label' => 'Kode',
            'type' => 'text',
            'name' => 'code',
            'required' => TRUE,
            'value' => $courier->code,
            'error' => $errors->first('code'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Nama',
            'type' => 'text',
            'name' => 'name',
            'required' => TRUE,
            'value' => $courier->name,
            'error' => $errors->first('name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Aktif',
            'type' => 'select',
            'name' => 'is_active',
            'required' => TRUE,
            'options' => [
                0 => 'Tidak',
                1 => 'Ya',
            ],
            'value' => $courier->is_active,
            'error' => $errors->first('is_active'),
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
