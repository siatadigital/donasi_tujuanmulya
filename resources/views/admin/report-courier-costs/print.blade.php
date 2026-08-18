<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Pembayaran Ongkir</title>
  <link rel="stylesheet" href="{{ public_path('admin-assets/css/report-print.css') }}" />
  <style>
    .report-table td:nth-child(3),
    .report-table td:nth-child(4),
    .report-table td:nth-child(5) {
        text-align: center;
    }
  </style>
</head>

<body>
  <h1 class="title">Laporan Pembayaran Ongkir</h1>
  <br>
  <table class="report-table">
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
                <p class="total-caption"><strong>Total Masuk</strong></p>
            </td>
            <td>
                <p class="total-price">{{ number_format($items->sum('courier_cost')) }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <p class="total-caption"><strong>Total Keluar</strong></p>
            </td>
            <td>
                <p class="total-price">{{ number_format($costOut) }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <p class="total-caption"><strong>Selisih</strong></p>
            </td>
            <td>
                <p class="total-price">{{ number_format($items->sum('courier_cost') - $costOut) }}</p>
            </td>
        </tr>
    </tbody>
  </table>
</body>

</html>
