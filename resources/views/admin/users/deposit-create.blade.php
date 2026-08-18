@extends('admin.master')

@section('title', 'RTL - Tambah Deposit')

@section('content')
<div class="section-header">
  <h1>Tambah Deposit</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.index') }}">User</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.show', ['id' => $id]) }}">Detail User</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.users.deposits.index', ['id' => $id]) }}">Deposit</a></div>
    <div class="breadcrumb-item">Tambah Deposit</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Deposit</h2>
  <p class="section-lead">
    Form untuk tambah deposit
  </p>

  <form action="{{ route('admin.users.deposits.store', ['id' => $id]) }}" method="POST">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header">
        <h4>Tambah Deposit Customer</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              @component('admin.components.form-input', [
                  'label' => 'Bank',
                  'type' => 'select',
                  'name' => 'bank_id',
                  'required' => TRUE,
                  'options' => $banks,
                  'value' => old('bank_id'),
                  'error' => $errors->first('bank_id'),
              ])
              @endcomponent
            </div>
            <div class="col-md-4">
              @component('admin.components.form-input', [
                  'label' => 'Nominal',
                  'type' => 'text',
                  'name' => 'amount',
                  'required' => TRUE,
                  'value' => old('amount'),
                  'error' => $errors->first('amount'),
              ])
              @endcomponent
            </div>
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

new Cleave('input[name=amount]', cleaveOptions);
</script>
@endsection
