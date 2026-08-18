@extends('admin.master')

@section('title', 'RTL - Tambah Bank')

@section('content')
<div class="section-header">
  <h1>Tambah Bank</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.banks.index') }}">Bank</a></div>
    <div class="breadcrumb-item">Tambah Bank</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Bank</h2>
  <p class="section-lead">
    Form untuk tambah bank
  </p>

  <div class="card">
    <form action="{{ route('admin.banks.store') }}" method="POST" enctype="multipart/form-data">
      <div class="card-header">
        <h4>Tambah Bank</h4>
      </div>
      <div class="card-body">
        {{ csrf_field() }}
        @component('admin.components.form-input', [
            'label' => 'Nama Bank',
            'type' => 'text',
            'name' => 'bank_name',
            'required' => TRUE,
            'value' => old('bank_name'),
            'error' => $errors->first('bank_name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Nama Pemilik',
            'type' => 'text',
            'name' => 'account_name',
            'required' => TRUE,
            'value' => old('account_name'),
            'error' => $errors->first('account_name'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'No. Rekening',
            'type' => 'text',
            'name' => 'account_number',
            'required' => TRUE,
            'value' => old('account_number'),
            'error' => $errors->first('account_number'),
        ])
        @endcomponent
        @component('admin.components.form-input', [
            'label' => 'Logo',
            'type' => 'image',
            'name' => 'bank_logo',
            'error' => $errors->first('bank_logo'),
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

@section('js')
<script>
// new Cleave('input[name=account_number]', {
//     numeral: true,
//     delimiter: '',
// });
</script>
@endsection
