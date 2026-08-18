@extends('admin.master')

@section('title', 'RTL - Ubah Kategori Artikel')

@section('content')
<div class="section-header">
  <h1>Ubah Kategori Artikel</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.blog-categories.index') }}">Kategori Artikel</a></div>
    <div class="breadcrumb-item">Ubah Kategori Artikel</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Kategori Artikel</h2>
  <p class="section-lead">
    Form untuk ubah kategori artikel
  </p>

  <div class="card">
    <form action="{{ route('admin.blog-categories.update', ['id' => $category->id]) }}" method="POST">
      <div class="card-header">
        <h4>Ubah Kategori Artikel</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" />

        @component('admin.components.form-input', [
            'label' => 'Nama',
            'type' => 'text',
            'name' => 'name',
            'required' => TRUE,
            'value' => $category->name,
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
            'value' => $category->is_active,
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
