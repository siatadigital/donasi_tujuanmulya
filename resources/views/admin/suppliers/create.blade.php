@extends('admin.master')

@section('title', 'RTL - Tambah Supplier')

@section('content')
<div class="section-header">
  <h1>Tambah Supplier</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.suppliers.index') }}">Supplier</a></div>
    <div class="breadcrumb-item">Tambah Supplier</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Supplier</h2>
  <p class="section-lead">
    Form untuk tambah supplier
  </p>

  <div class="card">
    <form action="{{ route('admin.suppliers.store') }}" method="POST">
      <div class="card-header">
        <h4>Tambah Supplier</h4>
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
            'label' => 'Email (Opsional)',
            'type' => 'email',
            'name' => 'email',
            'required' => FALSE,
            'value' => old('email'),
            'error' => $errors->first('email'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'No. Telp (Opsional)',
            'type' => 'tel',
            'name' => 'phone',
            'required' => FALSE,
            'value' => old('phone'),
            'error' => $errors->first('phone'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Alamat (Opsional)',
            'type' => 'textarea',
            'name' => 'address',
            'value' => old('address'),
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
