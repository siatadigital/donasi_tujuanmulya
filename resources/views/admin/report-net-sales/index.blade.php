@extends('admin.master')

@section('title', 'RTL - Pendapatan Bersih')

@section('content')
<div class="section-header">
  <h1>Pendapatan Bersih</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Pendapatan Bersih</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Pendapatan Bersih</h2>
  <p class="section-lead">
    Daftar pendapatan bersih
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Pendapatan Bersih</h4>
        </div>
        <div class="card-body">
          <form id="form-filter" action="{{ route('admin.reports.net-sales') }}">
            <div class="row">
              <div class="col-md-3">
                @component('admin.components.form-input', [
                'label' => 'Periode',
                'type' => 'text',
                'name' => 'period',
                'value' => $period,
                'error' => $errors->first('period'),
                ])
                @endcomponent
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary" style="margin-top:29px;">Filter</button>
                <button type="button" id="btn-reset" class="btn btn-danger" style="margin-top:29px;">Reset</button>
              </div>
            </div>
          </form>
          <a target="_blank" href="{{ route('admin.reports.net-sales.print') }}" class="btn btn-primary">Print</a>
          <br><br><br />
          <div class="summary">
            <h3 class="text-center">Laporan Pendapatan Bersih</h1>
              <p class="text-center">{{ $period ?: 'Semua Penjualan' }}</p>
              <hr>
              @foreach ($accountingCategories as $category)
              <div class="summary-item" style="padding-left: 32px;">
                <div class="summary-item--column">
                  <p class="summary-item--label">{{ $category->name }}</p>
                </div>
                <div class="summary-item--column">
                  <p class="summary-item--value">Rp. {{ number_format($category->total) }}</p>
                </div>
              </div>
              @endforeach
              <hr>
              <div class="summary-item">
                <div class="summary-item--column">
                  <p class="summary-item--label">Total Pendapatan Kotor</p>
                </div>
                <div class="summary-item--column">
                  <p class="summary-item--value">Rp. {{ number_format($totalGrossIncome) }}</p>
                </div>
              </div>
              <div class="summary-item">
                <div class="summary-item--column">
                  <p class="summary-item--label">Total Pengeluaran</p>
                </div>
                <div class="summary-item--column">
                  <p class="summary-item--value">Rp. {{ number_format($totalOutcome) }}</p>
                </div>
              </div>
              <hr>
              <div class="summary-item">
                <div class="summary-item--column">
                  <p class="summary-item--label">Total Pendapatan Bersih</p>
                </div>
                <div class="summary-item--column">
                  <p class="summary-item--value">Rp. {{ number_format($totalGrossIncome - $totalOutcome) }}</p>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
var $period = $('input[name="period"]');
var $btnReset = $('#btn-reset');
var $formFilter = $('#form-filter');

$period.daterangepicker({
  locale: {
    format: 'DD/MM/YYYY'
  }
});

$period.val('{{ $period }}');

$btnReset.on('click', function() {
  $period.val('');
  $formFilter.submit();
});
</script>
@endsection
