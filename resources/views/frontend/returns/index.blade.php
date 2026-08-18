@extends('frontend.master')

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      @include('frontend.partials.sidebar')

      <div class="col-md-9">
        <div class="history-list-table">
          <div class="d-flex justify-content-between align-items-center" style="margin-bottom:20px;">
            <div class="sub-title m-0">
                Riwayat Pengembalian
            </div>
            <a href="{{ route('frontend.return.create') }}">Buat</a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Kode Pengembalian</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($returns as $return)
                <tr class="clickable-row" data-href="{{ route('frontend.return.detail', ['id' => $return->id]) }}">
                  <td>
                    <div class="kode-pesanan">{{ $return->code }}</div>
                    <div class="tanggal">
                      <div class="text">Tanggal</div>
                      <div class="tgl">{{ $return->date }}</div>
                    </div>
                  </td>
                  <td>
                    <div class="text-center">
                      <span class="status-pesanan">
                        {{ $return->is_accept }}
                      </span>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
