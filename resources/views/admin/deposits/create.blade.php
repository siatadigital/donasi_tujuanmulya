@extends('admin.master')

@section('title', 'RTL - Tambah Deposit')

@section('content')
<div class="section-header">
  <h1>Tambah Deposit</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.deposits.index') }}">Deposit</a></div>
    <div class="breadcrumb-item">Tambah Deposit</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Deposit</h2>
  <p class="section-lead">
    Form untuk tambah accounting
  </p>

  <form action="{{ route('admin.deposits.store') }}" method="POST">
    {{ csrf_field() }}
    <div class="card">
        <div class="card-header">
        <h4>Tambah Deposit</h4>
        </div>
        <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label for="user_customer_id">Customer</label>
                <select name="user_customer_id" id="user_customer_id" class="form-control select2" required>
                    <option value="">Pilih Customer</option>
                    @foreach($customers as $item)
                    <option value="{{ $item->id }}" data-type="{{ $item->customer ? $item->customer->deposit_discount_type : '' }}">{{ $item->fullname }}</option>
                    @endforeach
                </select>
            </div>
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
                    'label' => 'Diskon Customer selama memiliki Deposit',
                    'type' => 'select',
                    'name' => 'deposit_discount_type',
                    'required' => TRUE,
                    'options' => $discountTypes,
                    'value' => '',
                    'error' => $errors->first('deposit_discount_type'),
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
            'label' => 'Tanggal',
            'type' => 'text',
            'name' => 'date',
            'required' => TRUE,
            'class' => 'datepicker',
            'value' => old('date'),
            'error' => $errors->first('date'),
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

new Cleave('input[name=amount_in]', cleaveOptions);
new Cleave('input[name=amount_out]', cleaveOptions);

$(document).ready(function(){
    $('#user_customer_id').change(function(){
        var dataType = $(this).find(':selected').attr('data-type');
        $("select[name=deposit_discount_type]").val(dataType).change();
    });
});
</script>
@endsection
