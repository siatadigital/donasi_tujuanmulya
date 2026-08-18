@extends('admin.master')

@section('title', 'RTL - Stok Keluar/Masuk')

@section('content')
<div class="section-header">
  <h1>Stok Keluar/Masuk</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Stok Keluar/Masuk</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Stok Keluar/Masuk</h2>
  <p class="section-lead">
    Daftar stok keluar/masuk
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Stok Keluar/Masuk</h4>
        </div>
        <div class="card-body">
          <form id="form-filter" action="{{ route('admin.reports.stocks') }}">
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
          @if (isset($items))
          <a target="_blank" href="{{ route('admin.reports.stocks.print') }}" class="btn btn-primary">Print</a>
          <br><br>
          <div class="table-responsive">
            <table class="table table-striped" id="table">
              <thead>
                <tr>
                  <th class="text-center" width="32px">
                    #
                  </th>
                  <th>Nama Produk</th>
                  <th>Warna</th>
                  <th>Jenis</th>
                  <th>Tanggal</th>
                  <th>Total Stok</th>
                </tr>
              </thead>
              <tbody>
              @if ($items->count())
              @foreach($items as $index => $item)
              <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->product->title }}</td>
                  <td><div style="width:24px;height:24px;background:{{ $item->color->hex_code }};"></div></td>
                  <td>{{ $item->stock_out ? 'Keluar' : 'Masuk' }}</td>
                  <td>{{ $item->created_at->format('Y-m-d') }}</td>
                  <td>{{ number_format($item->stock_out ?: $item->stock_in) }}</td>
              </tr>
              @endforeach
              <tr>
                  <td colspan="5">
                    <p class="text-right mb-0"><strong>Total Stok Keluar</strong></p>
                  </td>
                  <td>{{ number_format($items->sum('stock_out')) }}</td>
              </tr>
              <tr>
                  <td colspan="5">
                    <p class="text-right mb-0"><strong>Total Stok Masuk</strong></p>
                  </td>
                  <td>{{ number_format($items->sum('stock_in')) }}</td>
              </tr>
              @else
              <tr><td colspan="6"><p class="text-center mb-0">Tidak ada data</p></td></tr>
              @endif
              </tbody>
            </table>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
var $suppliers = $('select[name="supplier_id"]');
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
    $suppliers.val('').trigger('change');
    $period.val('');
    $formFilter.submit();
});
</script>
@endsection
