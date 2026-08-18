<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Pengeluaran</title>
  <link rel="stylesheet" href="{{ public_path('admin-assets/css/report-print.css') }}" />
</head>

<body>
  <h1 class="title">Laporan Pengeluaran</h1>
  <br>
  <table class="report-table">
    <thead>
      <tr>
        <th class="text-center" width="32px">
          #
        </th>
        <th>Kategori</th>
        <th>Pengeluaran</th>
      </tr>
    </thead>
    <tbody>
        @foreach($items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ number_format($item->total_expense) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2">
                <p class="total-caption"><strong>Total Semua</strong></p>
            </td>
            <td>
                <p class="total-price">{{ number_format($items->sum('total_expense')) }}</p>
            </td>
        </tr>
    </tbody>
  </table>
</body>

</html>
