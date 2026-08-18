@extends('admin.master')

@section('title', 'RTL - Ubah User')

@section('content')
<div class="section-header">
  <h1>Ubah User</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.index') }}">User</a></div>
    <div class="breadcrumb-item">Ubah User</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah User</h2>
  <p class="section-lead">
    Form untuk tambah user
  </p>

  <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">
                <h4>Ubah User</h4>
            </div>
            <div class="card-body">
                @component('admin.components.form-input', [
                    'label' => 'Nama Lengkap (Opsional)',
                    'type' => 'text',
                    'name' => 'fullname',
                    'value' => $user->fullname,
                    'error' => $errors->first('fullname'),
                ])
                @endcomponent
                @component('admin.components.form-input', [
                    'label' => 'Username',
                    'type' => 'text',
                    'name' => 'username',
                    'required' => TRUE,
                    'value' => $user->username,
                    'error' => $errors->first('username'),
                ])
                @endcomponent
                <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Email (Opsional)',
                        'type' => 'text',
                        'name' => 'email',
                        'value' => $user->email,
                        'error' => $errors->first('email'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'No. Telp (Opsional)',
                        'type' => 'tel',
                        'name' => 'phone',
                        'value' => $user->phone,
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
                    'value' => $user->address,
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
                        'value' => $user->type_id,
                        'options' => $types,
                        'error' => $errors->first('type_id'),
                        'required' => TRUE,
                    ])
                    @endcomponent
                    @component('admin.components.form-input', [
                        'label' => 'Aktif',
                        'type' => 'select',
                        'name' => 'is_active',
                        'value' => $user->is_active,
                        'options' => [
                            0 => 'Tidak',
                            1 => 'Ya',
                        ],
                        'error' => $errors->first('is_active'),
                        'required' => TRUE,
                    ])
                    @endcomponent
                    <div id="page-privilege">
                    @component('admin.components.form-input', [
                        'label' => 'Hak Akses Halaman',
                        'type' => 'checkboxes',
                        'name' => 'menus',
                        'value' => $menuPrivileges,
                        'options' => $menuAdmins,
                        'error' => $errors->first('menus'),
                    ])
                    @endcomponent
                    </div>
                    <div id="sales-privilege">
                    @component('admin.components.form-input', [
                        'label' => 'Hak Akses Penjualan',
                        'type' => 'checkboxes',
                        'name' => 'sales_statuses',
                        'value' => $salesPrivileges,
                        'options' => $salesStatuses,
                        'error' => $errors->first('sales_statuses'),
                    ])
                    @endcomponent
                    </div>
                    <div id="stock-privilege">
                    @component('admin.components.form-input', [
                        'label' => 'Hak Akses Stok',
                        'type' => 'checkboxes',
                        'name' => 'stock_types',
                        'value' => $stockPrivileges,
                        'options' => $stockTypes,
                        'error' => $errors->first('stock_types'),
                    ])
                    @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
  </form>
</div>
@endsection
