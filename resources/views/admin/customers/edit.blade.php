@extends('admin.master')

@section('title', 'RTL - Ubah Customer')

@section('content')
<div class="section-header">
  <h1>Ubah Customer</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.customers.index') }}">Customer</a></div>
    <div class="breadcrumb-item">Ubah Customer</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Customer</h2>
  <p class="section-lead">
    Form untuk ubah customer
  </p>

  <form action="{{ route('admin.customers.update', ['id' => $user->id]) }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                <h4>Ubah Customer</h4>
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
                <div class="row">
                    <div class="col-md-6">
                        @component('admin.components.form-input', [
                            'label' => 'Reseller',
                            'type' => 'select',
                            'name' => 'is_reseller',
                            'value' => $user->customer ? $user->customer->is_reseller : 0,
                            'options' => [
                                0 => 'Tidak',
                                1 => 'Ya',
                            ],
                            'error' => $errors->first('is_reseller'),
                            'required' => TRUE,
                        ])
                        @endcomponent
                    </div>
                    <div class="col-md-6">
                        @component('admin.components.form-input', [
                            'label' => 'Kode Reseller (Opsional)',
                            'type' => 'text',
                            'name' => 'reseller_code',
                            'value' => $user->customer ? $user->customer->reseller_code : '',
                            'error' => $errors->first('reseller_code'),
                        ])
                        @endcomponent
                    </div>
                </div>
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
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-primary">Simpan</button>
            </div>
            </div>
        </div>
    </div>
  </form>
</div>
@endsection

