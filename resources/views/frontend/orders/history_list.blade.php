@extends('frontend.master')

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      @include('frontend.partials.sidebar')

      <div class="col-md-9">
        <div class="history-list-table">
          <div class="sub-title">
            Riwayat Pemesanan
          </div>
          <div class="table-responsive">
            <ul class="nav nav-tabs" id="tab" role="tablist">
                @foreach ($statusTabs as $index => $tab)
                <li class="nav-item">
                    <a
                        class="nav-link {{ $index ? '' : 'active' }}"
                        id="{{ $tab->slug }}-tab"
                        href="#{{ $tab->slug }}"
                        data-toggle="tab"
                        role="tab"
                        aria-controls="{{ $tab->slug }}"
                        aria-selected="true"
                    >
                        {{ $tab->name }} ({{ $tab->total_sales }})
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="tab-content" id="tab-content">
                @foreach ($statusTabs as $index => $tab)
                <div class="tab-pane fade show {{ $index ? '' : 'active' }}" id="{{ $tab->slug }}" role="tabpanel" aria-labelledby="{{ $tab->slug }}-tab">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th>Kode Pemesanan</th>
                            <th>Metode Pembayaran</th>
                            <th class="text-center">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tab->orders as $order)
                            <tr class="clickable-row" data-href="{{ route('frontend.order.detail', ['id' => $order->id]) }}">
                            <td>
                                <div class="kode-pesanan">{{ $order->code }}</div>
                                <div class="status-pesanan">
                                  {{ $order->status_name }}
                                </div>
                                <div class="tanggal">
                                <div class="text">Tanggal</div>
                                <div class="tgl">{{ $order->date }}</div>
                                </div>
                                <!-- <a href=""class="stretched-link"></a> -->
                            </td>
                            <td>
                                @if ($order->status_id === 1)
                                <div class="text-center">
                                <span class="status-pesanan">
                                    Keep Stock
                                </span>
                                </div>
                                @else
                                  @if ($order->payment)
                                    @if ($order->payment->type === 'transfer' || $order->payment->type === 'edc')
                                    <div class="metode-bayar">
                                      @if ($order->payment->bank)
                                        <img src="{{ $order->payment->bank->getLogo() }}">
                                        <div class="kode">{{ $order->payment->bank->account_number }}</div>
                                        <div class="nama">a.n. {{ $order->payment->bank->account_name }}</div>
                                      @endif
                                    </div>
                                    @endif
                                  @endif
                                @endif
                            </td>
                            <td>
                                <h2 class="nominal">Rp. {{ number_format($order->amount, 0, ',', '.') }}</h2>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
