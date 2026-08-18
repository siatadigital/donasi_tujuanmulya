@extends('admin.master')

@section('title', 'RTL - Tambah User')

@section('content')
<div class="section-header">
  <h1>Tambah User</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.index') }}">User</a></div>
    <div class="breadcrumb-item">Tambah User</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah User</h2>
  <p class="section-lead">
    Form untuk tambah user
  </p>

  <form action="{{ route('admin.users.store') }}" method="POST">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">
                <h4>Tambah User</h4>
            </div>
            <div class="card-body">
                @component('admin.components.form-input', [
                    'label' => 'Nama Lengkap (Opsional)',
                    'type' => 'text',
                    'name' => 'fullname',
                    'value' => old('fullname'),
                    'error' => $errors->first('fullname'),
                ])
                @endcomponent
                @component('admin.components.form-input', [
                    'label' => 'Username',
                    'type' => 'text',
                    'name' => 'username',
                    'required' => TRUE,
                    'value' => old('username'),
                    'error' => $errors->first('username'),
                ])
                @endcomponent
                <div class="row">
                    <div class="col-md-6">
                        @component('admin.components.form-input', [
                            'label' => 'Email (Opsional)',
                            'type' => 'text',
                            'name' => 'email',
                            'value' => old('email'),
                            'error' => $errors->first('email'),
                        ])
                        @endcomponent
                    </div>
                    <div class="col-md-6">
                        @component('admin.components.form-input', [
                            'label' => 'No. Telp (Opsional)',
                            'type' => 'tel',
                            'name' => 'phone',
                            'value' => old('phone'),
                            'error' => $errors->first('phone'),
                        ])
                        @endcomponent
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @component('admin.components.form-input', [
                            'label' => 'Password',
                            'type' => 'password',
                            'name' => 'password',
                            'required' => TRUE,
                            'value' => old('password'),
                            'error' => $errors->first('password'),
                        ])
                        @endcomponent
                    </div>
                    <div class="col-md-6">
                        @component('admin.components.form-input', [
                            'label' => 'Konfirmasi Password',
                            'type' => 'password',
                            'name' => 'password_confirmation',
                            'required' => TRUE,
                            'value' => old('password_confirmation'),
                            'error' => $errors->first('password_confirmation'),
                        ])
                        @endcomponent
                    </div>
                </div>
                @component('admin.components.form-input', [
                    'label' => 'Alamat',
                    'type' => 'textarea',
                    'name' => 'address',
                    'value' => old('address'),
                    'error' => $errors->first('address'),
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
                        'label' => 'Bagian',
                        'type' => 'select',
                        'name' => 'type_id',
                        'value' => old('type_id'),
                        'options' => $types,
                        'error' => $errors->first('type_id'),
                        'required' => TRUE,
                    ])
                    @endcomponent
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
                        'label' => 'Hak Akses Halaman',
                        'type' => 'checkboxes',
                        'name' => 'menus',
                        'value' => old('menus'),
                        'options' => $menuAdmins,
                        'error' => $errors->first('menus'),
                    ])
                    @endcomponent
                    @component('admin.components.form-input', [
                        'label' => 'Hak Akses Penjualan',
                        'type' => 'checkboxes',
                        'name' => 'sales_statuses',
                        'value' => old('sales_statuses'),
                        'options' => $salesStatuses,
                        'error' => $errors->first('sales_statuses'),
                    ])
                    @endcomponent
                    @component('admin.components.form-input', [
                        'label' => 'Hak Akses Stok',
                        'type' => 'checkboxes',
                        'name' => 'stock_types',
                        'value' => old('stock_types'),
                        'options' => $stockTypes,
                        'error' => $errors->first('stock_types'),
                    ])
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
  </form>
</div>
@endsection
