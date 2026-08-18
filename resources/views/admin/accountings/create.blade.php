@extends('admin.master')

@section('title', 'RTL - Tambah Accounting')

@section('content')
<div class="section-header">
  <h1>Tambah Accounting</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.accountings.index') }}">Accounting</a></div>
    <div class="breadcrumb-item">Tambah Accounting</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Accounting</h2>
  <p class="section-lead">
    Form untuk tambah accounting
  </p>

  <form action="{{ route('admin.accountings.store') }}" method="POST">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header">
        <h4>Tambah Accounting</h4>
        </div>
        <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Kategori',
                    'type' => 'select',
                    'name' => 'category_id',
                    'required' => TRUE,
                    'options' => $categories,
                    'value' => old('category_id'),
                    'error' => $errors->first('category_id'),
                ])
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Tanggal',
                    'type' => 'text',
                    'name' => 'date',
                    'required' => TRUE,
                    'class' => 'datepicker',
                    'value' => old('date'),
                    'error' => $errors->first('date'),
                ])
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Uang Keluar',
                    'type' => 'text',
                    'name' => 'amount_out',
                    'value' => old('amount_out'),
                    'error' => $errors->first('amount_out'),
                ])
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('admin.components.form-input', [
                    'label' => 'Uang Masuk',
                    'type' => 'text',
                    'name' => 'amount_in',
                    'value' => old('amount_in'),
                    'error' => $errors->first('amount_in'),
                ])
                @endcomponent
            </div>
        </div>
        @component('admin.components.form-input', [
            'label' => 'Deskripsi',
            'type' => 'textarea',
            'name' => 'description',
            'value' => old('description'),
            'error' => $errors->first('description'),
        ])
        @endcomponent
        </div>
        <div class="card-footer text-right">
        <button class="btn btn-primary">Simpan</button>
        </div>
    </div>
  </form>
</div>
@endsection

@section('js')
<script>
var cleaveOptions = { numeral: true };

new Cleave('input[name=amount_in]', cleaveOptions);
new Cleave('input[name=amount_out]', cleaveOptions);
</script>
@endsection
