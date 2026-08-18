@extends('admin.master')

@section('title', 'RTL - Dashboard')

@section('content')
<div class="row">
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-stats">
        <div class="card-stats-title">Statistik Penjualan<!-- - -->
          <!-- Belum Berfungsi -->
          <!-- <div class="dropdown d-inline">
            <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month">Agustus</a>
            <ul class="dropdown-menu dropdown-menu-sm">
              <li class="dropdown-title">Pilih Bulan</li>
              <li><a href="#" class="dropdown-item">Januari</a></li>
              <li><a href="#" class="dropdown-item">Februari</a></li>
              <li><a href="#" class="dropdown-item">Maret</a></li>
              <li><a href="#" class="dropdown-item">April</a></li>
              <li><a href="#" class="dropdown-item">Mei</a></li>
              <li><a href="#" class="dropdown-item">Juni</a></li>
              <li><a href="#" class="dropdown-item">Juli</a></li>
              <li><a href="#" class="dropdown-item active">Agustus</a></li>
              <li><a href="#" class="dropdown-item">September</a></li>
              <li><a href="#" class="dropdown-item">Oktober</a></li>
              <li><a href="#" class="dropdown-item">November</a></li>
              <li><a href="#" class="dropdown-item">Desember</a></li>
            </ul>
          </div> -->
        </div>
        <div class="card-stats-items">
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $countStatusOrder3 }}</div>
            <div class="card-stats-item-label">Perlu Dikirim</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $countStatusOrder5 }}</div>
            <div class="card-stats-item-label">Pengiriman</div>
          </div>
          <div class="card-stats-item">
            <div class="card-stats-item-count">{{ $countStatusOrder6 }}</div>
            <div class="card-stats-item-label">Sudah Diterima</div>
          </div>
        </div>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-archive"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Penjualan</h4>
        </div>
        <div class="card-body">
          {{ $countStatusOrder }} Transaksi
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="balance-chart" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-dollar-sign"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Omset Penjualan Bulan Ini</h4>
        </div>
        <div class="card-body">
          Rp. {{ number_format($revenueThisMonth) }}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12">
    <div class="card card-statistic-2">
      <div class="card-chart">
        <canvas id="sales-chart" height="80"></canvas>
      </div>
      <div class="card-icon shadow-primary bg-primary">
        <i class="fas fa-shopping-bag"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Penjualan Hari Ini</h4>
        </div>
        <div class="card-body">
          {{ number_format($totalSales) }}
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h4>Pengeluaran vs Pemasukan</h4>
      </div>
      <div class="card-body">
        <div style="display:flex;flex-direction:row-reverse;">
            <input type="text" name="turnover-period" class="form-control" placeholder="Periode" style="width:200px;margin-left:8px;" />
            <button type="button" id="btn-filter-turnover" class="btn btn-primary" style="margin-left:8px;">Filter</button>
            <button type="button" id="btn-reset-turnover" class="btn btn-danger">Reset</button>
        </div>
        <br><br>
        <div id="spinner" style="display:none;">
            <div class="d-flex justify-content-center">
                <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="margin:48px;">
            </div>
        </div>
        <canvas id="chart-turnover" height="158"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div id="top-product" class="card gradient-bottom">
      <div class="card-header">
        <h4>Top 5 Produk Terlaris</h4>
        <div class="card-header-action dropdown">
          <a href="#" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">Hari Ini</a>
          <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <li class="dropdown-title">Pilih Periode</li>
            <li><a href="#" class="dropdown-item active" data-key="today">Hari Ini</a></li>
            <li><a href="#" class="dropdown-item" data-key="week">Minggu Ini</a></li>
            <li><a href="#" class="dropdown-item" data-key="month">Bulan Ini</a></li>
            <li><a href="#" class="dropdown-item" data-key="year">Tahun Ini</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body" id="top-5-scroll">
        <ul class="list-unstyled list-unstyled-border"></ul>
      </div>
      <div class="card-footer pt-3 d-flex justify-content-center">
        <div class="budget-price justify-content-center">
          <div class="budget-price-square bg-primary" data-width="20"></div>
          <div class="budget-price-label">Penjualan</div>
        </div>
        <div class="budget-price justify-content-center">
          <div class="budget-price-square bg-danger" data-width="20"></div>
          <div class="budget-price-label">Pembelian (Kulakan)</div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h4>Loyalitas Pelanggan Tahun Ini</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive table-invoice">
          <table class="table table-striped">
            <tr>
              <th>Ranking</th>
              <th>Nama Pelanggan</th>
              <th>Total Transaksi</th>
              <th>Total Nominal Pembelian</th>
            </tr>
            @foreach($topCustomers as $index => $customer)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td class="font-weight-600">{{ $customer->fullname }}</td>
              <td>
                {{ number_format($customer->total_sales) }}
              </td>
              <td>Rp. {{ number_format($customer->total_amount) }}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h4>Top Kota Tahun Ini</h4>
      </div>
      <div class="card-body">
        <div class="row">
          @foreach ($topCitySalesThisYear as $index => $sales)
          <div class="col-sm-6 mb-3">
            <div class="media">
              <div class="media-body">
                <div class="media-title">({{ $index + 1 }}) {{ $sales->city_name }}</div>
                <div class="text-small text-muted">{{ $sales->total_sales }} Penjualan</i></div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h4>Tagihan Penjualan Belum Bayar Bulan Ini</h4>
        <div class="card-header-action">
          <a href="#" class="btn btn-danger">Lihat Lainnya <i class="fas fa-chevron-right"></i></a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive table-invoice">
          <table class="table table-striped">
            <tr>
              <th>Kode Penjualan</th>
              <th>Nama Pelanggan</th>
              <th>Status</th>
              <th>Nominal Transaksi</th>
            </tr>
            @foreach ($unpaidSalesThisMonth as $sales)
            <tr>
              <td><a href="#">{{ $sales->code }}</a></td>
              <td class="font-weight-600">{{ $sales->customer }}</td>
              <td>
                <div class="badge badge-danger">{{ $sales->status }}</div>
              </td>
              <td>Rp {{ number_format($sales->amount) }}</td>
            </tr>
            @endforeach
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<!-- Page Specific JS File -->
<script src="{{ url('admin-assets') }}/js/page/index.js"></script>
<script>
var getDateRange = function(fromDate, toDate) {
    var from = moment(fromDate);
    var to = moment(toDate);
    var totalDays = Math.abs(from.diff(to, 'days')) + 2;
    var dates = [];

    for (let i = 0; i < totalDays - 1; i++) {
        var date = moment(fromDate)
                  .add(i, 'days')
                  .format('YYYY-MM-DD');

        dates.push(date);
    }

    return dates;
}

var fetchTurnover = function(chart, period) {
    $('#spinner').show();
    $('#chart-turnover').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.accountings') }}?period=" + period,
        success: function(response) {
            $('#spinner').hide();
            $('#chart-turnover').show();

            var accountings = response.data.map(function(item) {
                var date = moment(item.created_at).format('YYYY-MM-DD')

                return Object.assign(item, { date: date });
            });
            var parts = period.split(' - ');
            var fromDate = moment(parts[0], 'DD-MM-YYYY');
            var toDate = moment(parts[1], 'DD-MM-YYYY');
            var dates = getDateRange(fromDate, toDate);
            var incomes = [];
            var outcomes = [];

            for (var date of dates) {
                var currentAccountings = _.filter(accountings, { date: date });
                var income = _.sumBy(currentAccountings, 'amount_in');
                var outcome = _.sumBy(currentAccountings, 'amount_out');

                incomes.push(income);
                outcomes.push(outcome);
            }

            chart.data.labels = dates;
            chart.data.datasets[0].data = incomes;
            chart.data.datasets[1].data = outcomes;
            chart.update();
        },
        error: function() {
            $('#spinner').hide();
            $('#chart-turnover').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil omset',
                position: 'topRight'
            });
        }
    });
};

var fetchTopProducts = function(key) {
    $('#spinner').show();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.products') }}?filter=top_sales&period=" + key,
        success: function(response) {
            $('#spinner').hide();
            $('#top-product .list-unstyled').empty();

            for (const item of response.data) {
                const thumbnail = item.photos ? `{{ asset('uploads/products/small') }}/${item.photos.split()[0]}` : "{{ asset('uploads/products/default.png') }}";

                const element = `
                <li class="media">
                    <img class="mr-3 rounded" width="55" src="${thumbnail}" alt="product">
                    <div class="media-body">
                        <div class="float-right">
                        <div class="font-weight-600 text-muted text-small">${toCurrency(item.total_sales)} Terjual</div>
                        </div>
                        <div class="media-title">${item.title}</div>
                        <div class="mt-1">
                        <div class="budget-price">
                            <div class="budget-price-square bg-primary" data-width="64%"></div>
                            <div class="budget-price-label">Rp ${toCurrency(item.total_price_sell)}</div>
                        </div>
                        <div class="budget-price">
                            <div class="budget-price-square bg-danger" data-width="43%"></div>
                            <div class="budget-price-label">Rp ${toCurrency(item.total_price_buy)}</div>
                        </div>
                        </div>
                    </div>
                </li>
                `;

                $('#top-product .list-unstyled').append(element);
            }
        },
        error: function() {
            $('#spinner').hide();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil produk terlaris',
                position: 'topRight'
            });
        }
    });
};

var $turnoverPeriod = $('input[name="turnover-period"]');
var $btnFilterTurnover = $('#btn-filter-turnover');
var $btnResetTurnover = $('#btn-reset-turnover');
var $topProduct = $('#top-product');

var ctxTurnover = document.getElementById("chart-turnover").getContext('2d');
var today = moment();
var sevenDaysPast = moment().subtract(6, 'days');
var thisWeek = getDateRange(sevenDaysPast, today);
var initialPeriod = sevenDaysPast.format('DD/MM/YYYY') + ' - '+ today.format('DD/MM/YYYY');

var chartTurnover = new Chart(ctxTurnover, {
  type: 'line',
  data: {
    labels: thisWeek,
    datasets: [{
      label: 'Pemasukan',
      data: [0, 0, 0, 0, 0, 0, 0],
      borderWidth: 2,
      backgroundColor: 'rgba(63,82,227,.8)',
      borderWidth: 0,
      borderColor: 'transparent',
      pointBorderWidth: 0,
      pointRadius: 3.5,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
    },
    {
      label: 'Pengeluaran',
      data: [0, 0, 0, 0, 0, 0, 0],
      borderWidth: 2,
      backgroundColor: 'rgba(254,86,83,.7)',
      borderWidth: 0,
      borderColor: 'transparent',
      pointBorderWidth: 0 ,
      pointRadius: 3.5,
      pointBackgroundColor: 'transparent',
      pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
    }]
  },
  options: {
    legend: {
      display: true
    },
    tooltips: {
        callbacks: {
            label: function(item, data) {
                var label = data.datasets[item.datasetIndex].label;
                var value = data.datasets[item.datasetIndex].data[item.index];
                return label + ': Rp. ' + toCurrency(value);
            }
        }
    },
    scales: {
      yAxes: [{
        gridLines: {
          drawBorder: false,
          color: '#f2f2f2',
        },
        ticks: {
          beginAtZero: true,
          callback: function(value, index, values) {
            return 'Rp. ' + toCurrency(value);
          }
        }
      }],
      xAxes: [{
        gridLines: {
          display: false,
          tickMarkLength: 15,
        }
      }]
    },
  }
});

fetchTurnover(chartTurnover, initialPeriod);

$turnoverPeriod.daterangepicker({
    locale: {
        format: 'DD/MM/YYYY'
    }
});

$turnoverPeriod.val('');

$btnFilterTurnover.on('click', function() {
    var period = $turnoverPeriod.val();

    fetchTurnover(chartTurnover, period);
});

$btnResetTurnover.on('click', function() {
    $turnoverPeriod.val('');

    fetchTurnover(chartTurnover, initialPeriod);
});

$topProduct.find('.dropdown-item').on('click', function() {
    var key = $(this).data('key');
    var label = $(this).text();

    $topProduct.find('.dropdown-toggle').text(label);
    $topProduct.find('.dropdown-item').removeClass('active');
    $(this).addClass('active');

    fetchTopProducts(key);
});

fetchTopProducts('today');

</script>
@endsection
