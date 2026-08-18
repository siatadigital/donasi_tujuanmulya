@extends('admin::layouts.default')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if ($isChartAreaAccessible > 0)
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Area</h3>
          </div>
          <div class="box-body">
            <form method="GET">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">Tipe</label>
                    <select name="period_type" id="period-type" class="form-control">
                      @foreach ($periodTypes as $item)
                      <option value="{{ $item['value'] }}" {{ request()->period_type === $item['value'] ? 'selected' : '' }}>
                        {{ $item['label'] }}
                      </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Periode</label>
                    <div style="display:flex;">
                      <input type="text" id="period-from" name="period_from" placeholder="" value="{{ request()->period_from }}" class="form-control" style="margin-right:4px;background:white;" readonly />
                      <input type="text" id="period-to" name="period_to" placeholder="" value="{{ request()->period_to }}" class="form-control" style="background:white;" readonly />
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <button class="btn btn-primary" style="margin-top:24px;">
                    <i class="fa fa-search"></i>
                  </button>
                  <a href="{{ route('admin.page.getIndex') }}" class="btn btn-danger" style="margin-top:24px;">
                    <i class="fa fa-refresh"></i>
                  </a>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-md-12">
                <div class="chart">
                  <canvas id="chart-area" height="325"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if (count($blogViewer) > 0)
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Artikel</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="chart">
                  <div id="container1" style="width:100%; height:400px;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if ($isChartAkadAccessible > 0)
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Akad</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="chart">
                  <canvas id="chart-akad" height="400"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if ($isChartMethodAccessible > 0)
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Metode Pembayaran</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="chart">
                  <canvas id="chart-method" height="400"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @if ($isChartTotalAccessible > 0)
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Total Transaksi</h3>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <div class="chart">
                  <canvas id="chart-total" height="400"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </section>

  <!-- ChartJS 1.0.1 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="{{ url('/assets/admin/js/date_fns.min.js') }}"></script>
@stop

@section('scripts')
<script>
  var ctxArea = document.getElementById('chart-area');
  var ctxAkad = document.getElementById('chart-akad');
  var ctxMethod = document.getElementById('chart-method');
  var ctxTotal = document.getElementById('chart-total');
  var ctxArtikel = document.getElementById('chart-artikel');

  var manualPayments = JSON.parse('{!! json_encode($manualPayments) !!}');
  var periodDates = JSON.parse('{!! json_encode($periods->pluck("date")) !!}');
  var periodAmounts = JSON.parse('{!! json_encode($periods->pluck("amount")) !!}');

  var periodHit = JSON.parse('{!! json_encode($periodsArtikel->pluck("data")) !!}');

  var toCurrency = function(amount) {
    return amount > 0
      ? amount.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.")
      : "0";
  };

  @if ($isChartAreaAccessible > 0)
  var chartArea = new Chart(ctxArea, {
    type: 'line',
    data: {
      labels: periodDates,
      datasets: [
          {
            backgroundColor: 'rgba(243, 156, 18, 0.5)',
            borderColor: 'rgb(243, 156, 18)',
            data: periodAmounts,
            label: 'Transaksi',
            fill: false,
          }
      ],
    },
    options: {
      spanGaps: false,
      elements: {
				line: {
					tension: 0.000001
				}
      },
      scales: {
				xAxes: [{
					ticks: {
						autoSkip: false,
						maxRotation: 0,
					},
				}],
        yAxes: [
          {
            ticks: {
              callback: function(label, index, labels) {
                return label / 1000 + 'k';
              },
            },
            scaleLabel: {
              display: true,
              labelString: '1k = 1000',
            },
          },
        ],
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var label = data.labels[tooltipItem.index];
            var value = data.datasets[0]['data'][tooltipItem.index];

            return `${label}: Rp. ${toCurrency(value)}`;
          }
        }
      },
      plugins: {
        filler: {
          propagate: false,
        },
      },
    },
  });
  @endif

  document.addEventListener('DOMContentLoaded', function () {
       const chart = Highcharts.chart('container1', {
           chart: {
               type: 'line'
           },
           title: {
               text: ''
           },
           xAxis: {
               categories: JSON.parse('{!! json_encode($periods->pluck("date")) !!}')
           },
           yAxis: {
               title: {
                   text: 'Total'
               }
           },
           series: [
             @foreach ($blogViewer as $item)
               {
               name: '{{$item->blog ? $item->blog->title : "-"}}',
               data: JSON.parse('{!! json_encode($item->hitCount($item->blog_id)) !!}')
             },
             @endforeach
           ]
       });
   });


  @if ($isChartAkadAccessible > 0)
  var chartAkad = new Chart(ctxAkad, {
    type: 'pie',
    data: {
      labels: ['Infak Terikat', 'Infak Umum', 'Zakat'],
      datasets: [{
        data: [
          {{ $totalSupportersAmountSuccess }},
          {{ $totalDonationsAmountSuccess }},
          {{ $totalZakatsAmountSuccess }}
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var total = Number('{{ $totalAmountSuccess }}');
            var label = data.labels[tooltipItem.index];
            var value = data.datasets[0]['data'][tooltipItem.index];
            var percent = value * 100 / total;

            return `${label}: Rp. ${toCurrency(value)} (${percent}%)`;
          }
        }
      }
    }
  });
  @endif

  @if ($isChartMethodAccessible > 0)
  var chartMethod = new Chart(ctxMethod, {
    type: 'pie',
    data: {
      labels: ['Transfer', 'Auto', 'E-Wallet'],
      datasets: [{
        data: [
          {{ $countPayManual }},
          {{ $countPayAuto }},
          {{ $countPayEWallet }},
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var isManual = tooltipItem.index === 0;
            var total = Number('{{ $countPay }}');

            if (isManual) {
              return manualPayments
                .map(function(item) {
                  return `${item.bank_name}: ${item.count} (${item.percent}%)`
                });
            }

            label = data.labels[tooltipItem.index];
            value = data.datasets[0]['data'][tooltipItem.index];
            percent = value * 100 / total;

            return `${label}: ${toCurrency(value)} (${percent}%)`;
          }
        }
      }
    }
  });
  @endif

  @if ($isChartTotalAccessible > 0)
  var chartTotal = new Chart(ctxTotal, {
    type: 'pie',
    data: {
      labels: ['Sukses', 'Gagal'],
      datasets: [{
        data: [
          {{ $countTransactionsSuccess }},
          {{ $countTransactionsFailed }},
        ],
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 99, 132, 0.2)',
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
          'rgba(255, 99, 132, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var total = Number('{{ $countTransactions }}');
            var label = data.labels[tooltipItem.index];
            var value = data.datasets[0]['data'][tooltipItem.index];
            var percent = value * 100 / total;

            return `${label}: ${toCurrency(value)} (${percent}%)`;
          }
        }
      }
    }
  });
  @endif

  $('#period-type').on('change', function() {
    var value = $(this).val();
    var placeholder = '';
    var pickerOptions = {};

    switch (value) {
      case 'date':
        placeholder = 'Tanggal';

        pickerOptions = {
          format: 'dd/mm/yyyy',
        };
        break;

      case 'month':
        placeholder = 'Bulan';

        pickerOptions = {
          format: 'mm/yyyy',
          viewMode: "months", 
          minViewMode: "months"
        };
        break;

      case 'year':
        placeholder = 'Tahun';

        pickerOptions = {
          format: 'yyyy',
          viewMode: "years", 
          minViewMode: "years"
        };
        break;
    
      default:
        break;
    }

    $('#period-from').val('');
    $('#period-to').val('');

    $('#period-from').datepicker('destroy');
    $('#period-to').datepicker('destroy');

    $('#period-from').datepicker(pickerOptions);
    $('#period-to').datepicker(pickerOptions);

    $('#period-from').attr('placeholder', `Dari ${placeholder}`);
    $('#period-to').attr('placeholder', `Ke ${placeholder}`);
  });

  $('#period-type').change();

  $('#period-from').val('{{ request()->period_from }}');
  $('#period-to').val('{{ request()->period_to }}');

  $('#period-from').on('change', function() {
    var value = $(this).val();

    $('#period-to').val(value);
  });

  $('#period-to').on('change', function() {
    var periodType = $('#period-type').val();
    var fromParts = $('#period-from').val().split('/');
    var toParts = $(this).val().split('/');
    var formattedFrom = ''; 
    var formattedTo = ''; 

    switch (periodType) {
      case 'date':
        formattedFrom = `${fromParts[2]}-${fromParts[1]}-${fromParts[0]}T00:00:00`;
        formattedTo = `${toParts[2]}-${toParts[1]}-${toParts[0]}T00:00:00`;
        break;

      case 'month':
        formattedFrom = `${fromParts[1]}-${fromParts[0]}-01T00:00:00`;
        formattedTo = `${toParts[1]}-${toParts[0]}-01T00:00:00`;
        break;

      case 'year':
        formattedFrom = `${fromParts[0]}-01-01T00:00:00`;
        formattedTo = `${toParts[0]}-01-01T00:00:00`;
        break;
    
      default:
        break;
    }

    var parsedFrom = dateFns.parse(formattedFrom);
    var parsedTo = dateFns.parse(formattedTo);
    var isBefore = dateFns.isBefore(parsedTo, parsedFrom);

    if (isBefore) {
      $('#period-from').val($(this).val());
    }
  });
</script>
@stop
