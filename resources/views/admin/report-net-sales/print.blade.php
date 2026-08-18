<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Penjualan Bersih</title>
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
  <h1 class="title">Laporan Penjualan Bersih</h1>
  <p class="date">{{ $period ?: 'Semua Penjualan' }}</p>
  <hr><br>
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
  <hr><br>
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
  <hr><br>
  <div class="summary-item">
    <div class="summary-item--column">
      <p class="summary-item--label">Total Pendapatan Bersih</p>
    </div>
    <div class="summary-item--column">
      <p class="summary-item--value">Rp. {{ number_format($totalGrossIncome - $totalOutcome) }}</p>
    </div>
  </div>
</body>

</html>
