@extends('admin.master')

@section('title', 'RTL - Tambah Kategori Accounting')

@section('content')
<div class="section-header">
  <h1>Tambah Kategori Accounting</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.accounting-categories.index') }}">Kategori Accounting</a></div>
    <div class="breadcrumb-item">Tambah Kategori Accounting</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Kategori Accounting</h2>
  <p class="section-lead">
    Form untuk tambah bank
  </p>

  <div class="card">
    <form action="{{ route('admin.accounting-categories.store') }}" method="POST">
      <div class="card-header">
        <h4>Tambah Kategori Accounting</h4>
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
            'label' => 'Aktif',
            'type' => 'select',
            'name' => 'is_active',
            'options' => [
                0 => 'Tidak',
                1 => 'Ya',
            ],
            'value' => 0,
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
