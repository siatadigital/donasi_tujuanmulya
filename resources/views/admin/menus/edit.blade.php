@extends('admin.master')

@section('title', 'RTL - Ubah Menu')

@section('content')
<div class="section-header">
  <h1>Ubah Menu</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.menus.index') }}">Menu</a></div>
    <div class="breadcrumb-item">Ubah Menu</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Menu</h2>
  <p class="section-lead">
    Form untuk tambah menu
  </p>

  <div class="card">
    <form action="{{ route('admin.menus.update', ['id' => $menu->id]) }}" method="POST">
      <div class="card-header">
        <h4>Ubah Menu</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @component('admin.components.form-input', [
            'label' => 'Menu Induk (Opsional)',
            'type' => 'select',
            'name' => 'parent_id',
            'options' => $menus,
            'value' => $menu->parent_id,
            'error' => $errors->first('parent_id'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Judul',
            'type' => 'text',
            'name' => 'title',
            'required' => TRUE,
            'value' => $menu->title,
            'error' => $errors->first('title'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Link',
            'type' => 'text',
            'name' => 'link',
            'required' => TRUE,
            'value' => $menu->link,
            'error' => $errors->first('link'),
        ])
        @endcomponent
        <div class="row">
            <div class="col-md-4">
                @component('admin.components.form-input', [
                    'label' => 'Level',
                    'type' => 'number',
                    'name' => 'level',
                    'required' => TRUE,
                    'value' => $menu->level,
                    'error' => $errors->first('level'),
                    'additional' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ])
                @endcomponent
            </div>
            <div class="col-md-4">
                @component('admin.components.form-input', [
                    'label' => 'Posisi',
                    'type' => 'number',
                    'name' => 'position',
                    'required' => TRUE,
                    'value' => $menu->position,
                    'error' => $errors->first('position'),
                    'additional' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ])
                @endcomponent
            </div>
            <div class="col-md-4">
                @component('admin.components.form-input', [
                    'label' => 'Tipe',
                    'type' => 'select',
                    'name' => 'type',
                    'required' => TRUE,
                    'options' => [
                        'header' => 'Header',
                        'footer' => 'Footer',
                    ],
                    'value' => $menu->type,
                    'error' => $errors->first('type'),
                ])
                @endcomponent
            </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
