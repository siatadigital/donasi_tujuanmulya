<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Penjualan Kotor</title>
  <link rel="stylesheet" href="{{ public_path('admin-assets/css/report-print.css') }}" />
  <style>
  .date {
    text-align: center;
  }

  .summary-item {
    display: flex;
  }

  .summary-item p {
    margin: 0px;
  }

  .summary-item--column {
    flex: 1;
  }

  .summary-item--label {
    font-weight: bold !important;
  }

  .summary-item--value {
    text-align: right;
  }
  </style>
</head>

<body>
  <h1 class="title">Laporan Penjualan Kotor</h1>
  <p class="date">{{ $period ?: 'Semua Penjualan' }}</p>
  <hr><br>
  <div class="summary-item" style="padding-left: 32px;">
    <div class="summary-item--column">
      <p class="summary-item--label">Penjualan</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalSales) }}</p>
    </div>
  </div>
  <div class="summary-item" style="padding-left: 32px;">
    <div class="summary-item--column">
      <p class="summary-item--label">Ongkir Diterima</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalCourierCost) }}</p>
    </div>
  </div>
  <div class="summary-item" style="padding-left: 32px;">
    <div class="summary-item--column">
      <p class="summary-item--label">Retur</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalReturn) }}</p>
    </div>
  </div>
  <div class="summary-item" style="padding-left: 32px;">
    <div class="summary-item--column">
      <p class="summary-item--label">Diskon Penjualan</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalDiscount) }}</p>
    </div>
  </div>
  <hr><br>
  <div class="summary-item">
    <div class="summary-item--column">
      <p class="summary-item--label">Total Penjualan</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalAllSales) }}</p>
    </div>
  </div>
  <div class="summary-item">
    <div class="summary-item--column">
      <p class="summary-item--label">Total Penerimaan</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalReceiving) }}</p>
    </div>
  </div>
  <hr><br>
  <div class="summary-item">
    <div class="summary-item--column">
      <p class="summary-item--label">Total Pendapatan Kotor</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalAllSales - $totalReceiving) }}</p>
    </div>
  </div>
</body>

</html>
