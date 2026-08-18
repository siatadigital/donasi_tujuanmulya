@extends('admin.master')

@section('title', 'RTL - Tambah Banner')

@section('content')
<div class="section-header">
  <h1>Tambah Banner</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.sliders.index') }}">Banner</a></div>
    <div class="breadcrumb-item">Tambah Banner</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Banner</h2>
  <p class="section-lead">
    Form untuk tambah banner
  </p>

  <div class="card">
    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
      <div class="card-header">
        <h4>Tambah Banner</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
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
            'required' => TRUE,
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Link',
            'type' => 'text',
            'name' => 'link',
            'value' => old('link'),
            'error' => $errors->first('link'),
            'required' => TRUE,
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Foto',
            'type' => 'image',
            'name' => 'photo',
            'value' => old('photo'),
            'required' => TRUE,
            'error' => $errors->first('photo'),
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
