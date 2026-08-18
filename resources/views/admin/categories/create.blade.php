@extends('admin.master')

@section('title', 'RTL - Tambah Kategori')

@section('content')
<div class="section-header">
  <h1>Tambah Kategori</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.categories.index') }}">Kategori</a></div>
    <div class="breadcrumb-item">Tambah Kategori</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Kategori</h2>
  <p class="section-lead">
    Form untuk tambah kategori
  </p>

  <div class="card">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
      <div class="card-header">
        <h4>Tambah Kategori</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        @component('admin.components.form-input', [
            'label' => 'Kategori Induk (Opsional)',
            'type' => 'select',
            'name' => 'parent_id',
            'value' => old('parent_id'),
            'options' => $categories,
            'error' => $errors->first('parent_id'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Nama',
            'type' => 'text',
            'name' => 'name',
            'value' => old('name'),
            'required' => TRUE,
            'error' => $errors->first('name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Foto (Opsional)',
            'type' => 'image',
            'name' => 'photo',
            'value' => old('photo'),
            'error' => $errors->first('photo'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Tampilkan di Homepage',
            'type' => 'select',
            'name' => 'is_featured_home',
            'value' => '0',
            'options' => ['0' => 'Tidak', '1' => 'Ya'],
            'error' => $errors->first('is_featured_home'),
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
