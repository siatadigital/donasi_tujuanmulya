@extends('admin.master')

@section('title', 'RTL - Pembayaran Ongkir')

@section('content')
<div class="section-header">
  <h1>Pembayaran Ongkir</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Pembayaran Ongkir</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Pembayaran Ongkir</h2>
  <p class="section-lead">
    Daftar pembayaran ongkir
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Pembayaran Ongkir</h4>
        </div>
        <div class="card-body">
          <form id="form-filter" action="{{ route('admin.reports.courier-costs') }}">
            <div class="row">
              <div class="col-md-3">
                @component('admin.components.form-input', [
                    'label' => 'Kurir',
                    'type' => 'select',
                    'name' => 'courier',
                    'options' => $couriers,
                    'value' => $courier,
                    'error' => $errors->first('courier'),
                ])
                @endcomponent
              </div>
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
          <a target="_blank" href="{{ route('admin.reports.courier-costs.print') }}" class="btn btn-primary">Print</a>
          <br><br>
          <div class="table-responsive">
            <table class="table table-striped" id="table">
              <thead>
                <tr>
                  <th class="text-center" width="32px">
                    #
                  </th>
                  <th>Kode Pengiriman</th>
                  <th>Kurir</th>
                  <th>Layanan</th>
                  <th>Tanggal</th>
                  <th>Ongkir</th>
                </tr>
              </thead>
              <tbody>
              @if ($items->count())
              @foreach($items as $index => $item)
              <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->code }}</td>
                  <td>{{ $item->courier_info }}</td>
                  <td>{{ $item->courier_service_info }}</td>
                  <td>{{ $item->date }}</td>
                  <td>{{ number_format($item->courier_cost) }}</td>
              </tr>
              @endforeach
              <tr>
                  <td colspan="5">
                    <p class="text-right mb-0"><strong>Total Masuk</strong></p>
                  </td>
                  <td>{{ number_format($items->sum('courier_cost')) }}</td>
              </tr>
              <tr>
                  <td colspan="5">
                    <p class="text-right mb-0"><strong>Total Keluar</strong></p>
                  </td>
                  <td>{{ number_format($costOut) }}</td>
              </tr>
              <tr>
                  <td colspan="5">
                    <p class="text-right mb-0"><strong>Selisih</strong></p>
                  </td>
                  <td>{{ number_format($items->sum('courier_cost') - $costOut) }}</td>
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
