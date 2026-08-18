<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Penjualan Offline Gudang</title>
  <link rel="stylesheet" href="{{ public_path('admin-assets/css/report-print.css') }}" />
  <style>
    .report-table td:nth-child(3),
    .report-table td:nth-child(4) {
        text-align: center;
    }
  </style>
</head>

<body>
  <h1 class="title">Laporan Penjualan Offline Gudang</h1>
  <br>
  <table class="report-table">
    <thead>
      <tr>
        <th class="text-center" width="32px">
          #
        </th>
        <th>Kode Penjualan</th>
        <th>Status</th>
        <th>Tanggal</th>
        <th>Total Harga</th>
      </tr>
    </thead>
    <tbody>
        @foreach($items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->code }}</td>
            <td>{{ $item->status_name }}</td>
            <td>{{ $item->date }}</td>
            <td>{{ number_format($item->amount_transfer) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <p class="total-caption"><strong>Total Semua</strong></p>
            </td>
            <td>
                <p class="total-price">{{ number_format($items->sum('amount_transfer')) }}</p>
            </td>
        </tr>
    </tbody>
  </table>
</body>

</html>
