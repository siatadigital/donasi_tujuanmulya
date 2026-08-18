@extends('admin.master')

@section('title', 'RTL - Tambah Lookbook')

@section('content')
<div class="section-header">
  <h1>Tambah Lookbook</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.lookbooks.index') }}">Lookbook</a></div>
    <div class="breadcrumb-item">Tambah Lookbook</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Lookbook</h2>
  <p class="section-lead">
    Form untuk tambah lookbook
  </p>

  <form action="{{ route('admin.lookbooks.store') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Lookbook</h4>
          </div>
          <div class="card-body">
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
                'label' => 'Tanggal',
                'type' => 'text',
                'name' => 'date',
                'value' => old('date'),
                'class' => 'datepicker',
                'error' => $errors->first('date'),
            ])
            @endcomponent
          </div>
          <div class="card-footer text-right">
            <button class="btn btn-primary">Simpan</button>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h4>Lainnya</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Aktif',
                'type' => 'select',
                'name' => 'is_active',
                'value' => old('is_active'),
                'options' => [
                    0 => 'Tidak',
                    1 => 'Ya',
                ],
                'error' => $errors->first('is_active'),
                'required' => TRUE,
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Cover',
                'type' => 'image',
                'name' => 'cover_photo',
                'value' => old('cover_photo'),
                'required' => TRUE,
                'error' => $errors->first('cover_photo'),
            ])
            @endcomponent
            @component('admin.components.form-input', [
                'label' => 'Foto',
                'type' => 'images',
                'name' => 'photos',
                'value' => old('photos'),
                'required' => TRUE,
                'error' => $errors->first('photos'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection
