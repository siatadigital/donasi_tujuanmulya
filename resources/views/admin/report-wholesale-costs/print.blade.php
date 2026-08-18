<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Biaya Kulak</title>
  <link rel="stylesheet" href="{{ public_path('admin-assets/css/report-print.css') }}" />
  <style>
    .report-table td:nth-child(3),
    .report-table td:nth-child(4) {
        text-align: center;
    }

    .report-table td:nth-child(5) {
        text-align: right;
    }

    .report-table .total-price {
        text-align: right;
    }
  </style>
</head>

<body>
  <h1 class="title">Laporan Biaya Kulak</h1>
  <br>
  <table class="report-table">
    <thead>
      <tr>
        <th class="text-center" width="32px">
          #
        </th>
        <th>Nama Supplier</th>
        <th>Jumlah Item</th>
        <th>Tanggal</th>
        <th>Total Biaya</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $index => $item)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->supplier->name }}</td>
        <td>{{ $item->details->sum('quantity') }}</td>
        <td>{{ $item->date }}</td>
        <td>Rp. {{ number_format($item->total_price) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="4">
          <p class="total-caption"><strong>Total</strong></p>
        </td>
        <td class="total-price">Rp. {{ number_format($items->sum('total_price')) }}</td>
      </tr>
    </tbody>
  </table>
</body>

</html>
