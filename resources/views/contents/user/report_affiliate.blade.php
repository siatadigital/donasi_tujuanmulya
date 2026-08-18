@extends('layouts.default')
@section('content')
<div class="container-mobile" style="padding: 20px;margin-bottom:100px;">
  <header class="page-header text-center">
    <h2>Laporan Affiliate</h2>
    <p>Sebagai Internal User Yukdonasi dapat melihat laporan affialiate siapa saja donatur yang berhasil diajak untuk melakukan transaksi infak/zakat</p>
  </header>

  <form>
    <input class="form-control dpfrom" required="required" name="from_date" type="date" placeholder="Dari Tanggal" value="{{ request('from_date') }}">
    <input class="form-control dpto" required="required" name="to_date" type="date" placeholder="Sampai Tanggal" value="{{ request('to_date') }}">
    <button type="submit" class="btn btn-primary btn-block">Filter Tanggal</button>
  </form>
  <br>
  <h4>Total Keseluruhan {{ priceFormat($donaturTerakhirSum + $supporterTerakhirSum + $zakatTerakhirSum) }}</h4>
  <br>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#global" data-toggle="tab">Infak</a></li>
    <li role="presentation"><a href="#terikat" data-toggle="tab">Infak tiap Campaign</a></li>
    <li role="presentation"><a href="#zakat" data-toggle="tab">Zakat</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="global">
      <h4>Total Infak Umum {{ priceFormat($donaturTerakhirSum) }}</h4>
      <table class="table table-bordered table-striped table-responsive">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Nominal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($donaturTerakhirList as $item)
          <tr>
            <td>{{ $item->created_at }}</td>
            @if ($item->is_anonim)
            <td>Hamba Allah</td>
            @else
            <td>{{ $item->fullname }}</td>
            @endif
            <td>{{ priceFormat($item->unique_code ? $item->amount + $item->unique_code : $item->amount) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="tab-pane" id="terikat">
      <h4>Total Infak Campaign {{ priceFormat($supporterTerakhirSum) }}</h4>
      <table class="table table-bordered table-striped table-responsive">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Campaign</th>
            <th>Nama</th>
            <th>Nominal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($supporterTerakhirList as $item)
          <tr>
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->project->title }}</td>
            @if ($item->is_anonim)
            <td>Hamba Allah</td>
            @else
            <td>{{ $item->fullname }}</td>
            @endif
            <td>{{ priceFormat($item->unique_code ? $item->money + $item->unique_code : $item->money) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="tab-pane" id="zakat">
      <h4>Total Zakat {{ priceFormat($zakatTerakhirSum) }}</h4>
      <table class="table table-bordered table-striped table-responsive">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Nama</th>
            <th>Nominal</th>
            <th>Tipe</th>
          </tr>
        </thead>
        <tbody>
          @foreach($zakatTerakhirList as $item)
          <tr>
            <td>{{ $item->created_at }}</td>
            @if ($item->is_anonim)
            <td>Hamba Allah</td>
            @else
            <td>{{ $item->fullname }}</td>
            @endif
            <td>{{ priceFormat($item->unique_code ? $item->amount + $item->unique_code : $item->amount) }}</td>
            <td>{{ strtoupper($item->type) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop