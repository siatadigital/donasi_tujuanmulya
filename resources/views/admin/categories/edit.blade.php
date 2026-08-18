@extends('admin.master')

@section('title', 'RTL - Ubah Kategori')

@section('content')
<div class="section-header">
  <h1>Ubah Kategori</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.categories.index') }}">Kategori</a></div>
    <div class="breadcrumb-item">Ubah Kategori</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Kategori</h2>
  <p class="section-lead">
    Form untuk ubah kategori
  </p>

  <div class="card">
    <form action="{{ route('admin.categories.update', ['id' => $category->id]) }}" method="POST" enctype="multipart/form-data">
      <div class="card-header">
        <h4>Ubah Kategori</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        @component('admin.components.form-input', [
            'label' => 'Kategori Induk (Opsional)',
            'type' => 'select',
            'name' => 'parent_id',
            'value' => $category->parent_id,
            'options' => $categories,
            'error' => $errors->first('parent_id'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Nama',
            'type' => 'text',
            'name' => 'name',
            'value' => $category->name,
            'required' => TRUE,
            'error' => $errors->first('name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Foto (Opsional)',
            'type' => 'image',
            'name' => 'photo',
            'value' => $category->photo ? url('uploads/categories/' . $category->photo) : NULL,
            'error' => $errors->first('photo'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Tampilkan di Homepage',
            'type' => 'select',
            'name' => 'is_featured_home',
            'value' => $category->is_featured_home,
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
