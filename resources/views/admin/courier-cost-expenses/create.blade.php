@extends('admin.master')

@section('title', 'RTL - Tambah Pengeluaran Ongkir')

@section('content')
<div class="section-header">
  <h1>Tambah Pengeluaran Ongkir</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.courier-cost-expenses.index') }}">Pengeluaran Ongkir</a></div>
    <div class="breadcrumb-item">Tambah Pengeluaran Ongkir</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Pengeluaran Ongkir</h2>
  <p class="section-lead">
    Form untuk tambah pengeluaran ongkir
  </p>

  <form action="{{ route('admin.courier-cost-expenses.store') }}" method="POST">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header">
        <h4>Tambah Pengeluaran Ongkir</h4>
        </div>
        <div class="card-body">
        <div class="row">
            <div class="col-md-4">
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
            <div class="col-md-4">
                @component('admin.components.form-input', [
                    'label' => 'Ongkir',
                    'type' => 'text',
                    'name' => 'amount_out',
                    'value' => old('amount_out'),
                    'error' => $errors->first('amount_out'),
                ])
                @endcomponent
            </div>
            <div class="col-md-4">
                @component('admin.components.form-input', [
                    'label' => 'Kurir',
                    'type' => 'select',
                    'name' => 'courier_id',
                    'options' => $couriers,
                    'value' => old('courier_id'),
                    'error' => $errors->first('courier_id'),
                    'required' => TRUE,
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

new Cleave('input[name=amount_out]', cleaveOptions);
</script>
@endsection
