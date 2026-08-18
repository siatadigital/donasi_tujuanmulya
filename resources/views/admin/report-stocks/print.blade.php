<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Stok Keluar/Masuk</title>
  <link rel="stylesheet" href="{{ public_path('admin-assets/css/report-print.css') }}" />
  <style>
    .report-table td:nth-child(3),
    .report-table td:nth-child(4),
    .report-table td:nth-child(5),
    .report-table td:nth-child(6),
    .report-table .total-stock {
        text-align: center;
    }

    .color-box {
        margin: auto;
        margin-top: 8px;
        width: 24px;
        height: 24px;
    }

    .color-name {
        font-size: 12px;
        margin-top: 4px;
        margin-bottom: 0px;
    }
  </style>
</head>

<body>
  <h1 class="title">Laporan Stok Keluar/Masuk</h1>
  <br>
  <table class="report-table">
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
      @foreach($items as $index => $item)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->product->title }}</td>
        <td>
            <div class="color-box" style="background:{{ $item->color->hex_code }};"></div>
            <p class="color-name">{{ $item->color->name }}</p>
        </td>
        <td>{{ $item->stock_out ? 'Keluar' : 'Masuk' }}</td>
        <td>{{ $item->created_at->format('Y-m-d') }}</td>
        <td>{{ number_format($item->stock_out ?: $item->stock_in) }}</td>
      </tr>
      @endforeach
      <tr>
        <td colspan="5">
          <p class="total-caption"><strong>Total Stok Keluar</strong></p>
        </td>
        <td><p class="total-stock">{{ number_format($items->sum('stock_out')) }}</p></td>
      </tr>
      <tr>
        <td colspan="5">
          <p class="total-caption"><strong>Total Stok Masuk</strong></p>
        </td>
        <td><p class="total-stock">{{ number_format($items->sum('stock_in')) }}</p></td>
      </tr>
    </tbody>
  </table>
</body>

</html>
