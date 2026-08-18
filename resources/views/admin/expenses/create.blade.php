@extends('admin.master')

@section('title', 'RTL - Tambah Pengeluaran')

@section('content')
<div class="section-header">
  <h1>Tambah Pengeluaran</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.expenses.index') }}">Pengeluaran</a></div>
    <div class="breadcrumb-item">Tambah Pengeluaran</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Pengeluaran</h2>
  <p class="section-lead">
    Form untuk tambah Pengeluaran
  </p>

  <form action="{{ route('admin.expenses.store') }}" method="POST">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header">
        <h4>Tambah Pengeluaran</h4>
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
        @component('admin.components.form-input', [
            'label' => 'Uang Keluar',
            'type' => 'text',
            'name' => 'amount_out',
            'value' => old('amount_out'),
            'error' => $errors->first('amount_out'),
        ])
        @endcomponent
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

new Cleave('input[name=amount_out]', cleaveOptions);
</script>
@endsection
