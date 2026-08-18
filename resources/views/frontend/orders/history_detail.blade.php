@extends('frontend.master')

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      @include('frontend.partials.sidebar')

      <div class="col-md-9">
        <div class="history-list-table history-detail">
          <div class="sub-title">
            Detail Riwayat Pemesanan
          </div>
          @if ($order->point)
          <div class="alert alert-success" role="alert">
            Pesanan ini berpotensi mendapatkan {{ $order->point }} poin.
          </div>
          @endif
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th></th>
                  <th>Metode Pembayaran</th>
                  <th class="text-center">Nominal</th>
                </tr>
              </thead>
              <tbody>
                @if ($order->status_id !== 1)
                <tr class="" data-href='#'>
                  <td>
                    <div class="kode-pesanan">{{ $order->code }}</div>
                    <a href="#" class="copy-link" id="copy-code">Salin Kode Pemesanan</a>
                    <br><br>
                    <div class="status-pesanan">
                        {{ $order->status_name }}
                    </div>
                  </td>
                  @if ($order->payment)
                  <td>
                    @if ($order->payment->type === 'transfer' || $order->payment->type === 'edc')
                    <div class="metode-bayar">
                      @if ($order->payment->bank)
                        <img src="{{ $order->payment->bank->getLogo() }}">
                        <div class="kode">{{ $order->payment->bank->account_number }}</div>
                        <div class="nama">a.n. {{ $order->payment->bank->account_name }}</div>
                        <a href="#" class="copy-link" id="copy-account-number">Salin Nomor Rekening</a>
                      @endif
                    </div>
                    @endif
                  </td>
                  @endif
                  <td>
                    <div class="history-detail-sub-title">Total Pembayaran</div>
                    <h2 class="nominal">Rp. {{ number_format($amountTransfer, 0, ',', '.') }}</h2>
                    @if ($order->payment)
                      @if ($order->payment->bank)
                        <div class="history-detail-link">
                          <a href="{{ route('frontend.order.payment', array('id' => $order->id)) }}">Lihat Detail</a>
                        </div>
                      @endif
                    @endif
                    <a href="#" class="copy-link" id="copy-amount" style="margin-left:18px;">Salin Nominal</a>
                  </td>
                </tr>
                <tr class="" data-href='#'>
                  <td>
                    <div class="tanggal">
                      <div class="text">Tanggal</div>
                      <div class="tgl">{{ $date }}</div>
                    </div>
                    <!-- <a href=""class="stretched-link"></a> -->
                  </td>
                  <td>
                    @if ($amountTransfer)
                    <div class="tanggal">
                      <div class="text">Sisa Waktu Pembayaran</div>
                      <div class="pembayaran-waktu history-detail">
                        <span id="interval" style="display : none;">{{ $minutes }}:{{ $seconds }}</span>
                        <span id="total-seconds" style="display: none;">{{ $totalSeconds }}</span>
                        <div class="time">
                          <div id="menit" class="numbers">{{ $minutes }}</div>
                          <span>menit</span>
                        </div>
                        <div class="dots">:</div>
                        <div class="time">
                          <div id="detik" class="numbers">{{ $seconds }}</div>
                          <span>detik</span>
                        </div>
                      </div>
                      @if ($order->payment)
                        @if ($order->status_id === 2 && !$order->payment->is_confirm)
                        <a href="{{ route('frontend.order.payment_confirm', ['id' => $order->id]) }}" class="btn pembayaran-btn">Saya sudah membayar</a>
                        @endif
                      @endif
                    </div>
                    @endif
                  </td>
                  <td>
                    <div class="history-detail-sub-title">Total Berat</div>
                    <div class="history-detail-berat">
                      <img src="{{ url('img/kilogram.png') }}">
                      <span>{{ number_format($order->total_weight, 0, ',', '.') }} gram</span>
                    </div>
                  </td>
                </tr>
                @else
                <tr class="" data-href='#'>
                  <td>
                    <div class="kode-pesanan">{{ $order->code }}</div>
                    <div class="tanggal">
                      <div class="text">Tanggal</div>
                      <div class="tgl">{{ $date }}</div>
                    </div>
                  </td>
                  <td>
                    <div class="text-center" style="margin-top: 24px">
                        <div class="status-pesanan">
                            {{ $order->status_name }}
                        </div>
                    </div>
                  </td>
                  <td>
                    <div class="history-detail-sub-title">Total Pembayaran</div>
                    <h2 class="nominal" style="margin-bottom: 16px;">Rp. {{ number_format($amountTransfer, 0, ',', '.') }}</h2>
                    <div class="history-detail-sub-title">Total Berat</div>
                    <div class="history-detail-berat">
                      <img src="{{ url('img/kilogram.png') }}">
                      <span>{{ number_format($order->total_weight, 0, ',', '.') }} gram</span>
                    </div>
                  </td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
          @if ($order->payment)
            @if ($order->status_id == 2 && $order->payment->is_confirm)
              @if ($order->payment->bank)
                <hr>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <img src="{{ $order->payment->getProofImage() }}" style="width:320px;height:320px;object-fit:cover;">
                    </div>
                    <div class="col-md-7">
                        <p>Nama Pen-transfer: {{ $order->payment->from_account_name }}</p>
                        <p>Nominal Transfer: {{ $order->payment->from_amount_transfer }}</p>
                        <p>Tujuan Bank Transfer: {{ $order->payment->bank->bank_name . ' - ' . $order->payment->bank->account_number . ' a.n. ' . $order->payment->bank->account_name }}</p>
                        <p>Tanggal Transfer: {{ $order->payment->date->format('d-m-Y') }}</p>
                        <a
                            style="margin:0px;margin-top:32px;"
                            href="{{ route('frontend.order.payment_confirm', ['id' => $order->id]) }}"
                            class="btn pembayaran-btn"
                        >
                            Ubah Konfirmasi Pembayaran
                        </a>
                    </div>
                </div>
                <br>
                <hr>
                <br>
              @endif
            @endif
          @endif
        </div>

        <div class="history-detail-info">
          <div class="row">
            <div class="col-lg-6">
              @if ($order->delivery && $order->delivery->origin_fullname)
              <div class="checkout-info-pembeli">
                <h1>Informasi Pembeli</h1>
                <div class="info-box">
                  <div class="head">
                    Nama Lengkap
                  </div>
                  <div class="main nama">{{ $order->delivery->origin_fullname }}</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat Email
                  </div>
                  <div class="main email">{{ $order->delivery->origin_email }}</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    No. Hp
                  </div>
                  <div class="main noHp">{{ $order->delivery->origin_phone }}</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat
                  </div>
                  <div class="main">
                    <p style="margin-bottom: 0px">{{ $order->delivery->origin_address }}</p>
                    <p style="margin-bottom: 0px">{{ $order->delivery->originSubdistrict->province->name }}, {{ $order->delivery->originSubdistrict->city->name }}, {{ $order->delivery->originSubdistrict->name }}</p>
                    <p>{{ $order->delivery->origin_postcode }}</p>
                  </div>
                </div>
              </div>
              @endif
            </div>
            <div class="col-lg-6">
              @if ($order->delivery && $order->delivery->destination_fullname)
              <div class="checkout-info-pembeli">
                <h1>Tujuan Pengiriman</h1>
                <div class="info-box">
                  <div class="head">
                    Nama Lengkap
                  </div>
                  <div class="main nama">{{ $order->delivery->destination_fullname }}</div>
                </div>
                <!-- <div class="info-box">
                  <div class="head">
                    Alamat Email
                  </div>
                  <div class="main email">{{ $order->delivery->destination_email }}</div>
                </div> -->
                <div class="info-box">
                  <div class="head">
                    No. Hp
                  </div>
                  <div class="main noHp">{{ $order->delivery->destination_phone }}</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat
                  </div>
                  <div class="main">
                    <p style="margin-bottom: 0px">{{ $order->delivery->destination_address }}</p>
                    <p style="margin-bottom: 0px">{{ $order->delivery->destinationSubdistrict->province->name }}, {{ $order->delivery->destinationSubdistrict->city->name }}, {{ $order->delivery->destinationSubdistrict->name }}</p>
                    <p>{{ $order->delivery->destination_postcode }}</p>
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>
          <div>
            <table class="table table-bordered" style="width:400px;margin:auto;">
                <thead>
                    <tr>
                        <th width="200px">Status</th>
                        <th width="200px">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $index => $log)
                    <tr style="font-weight: {{ $index === 0 ? 'bold' : 'normal' }}">
                        <td>{{ $log->status_name }}</td>
                        <td>{{ $log->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
          </div>
          <hr>
          <p>Total Item : <span id="total-pcs">{{ $order->details->sum('quantity') }}</span> pcs</p>
          <hr>
          <div class="cart-table">
            @foreach($items as $item)
            <div class="cart-row">
              <div class="col-product cart-col">
                <div class="img-box">
                  <img src="{{ $item->product_photo }}">
                </div>
                <div class="text-box">
                <?php
                    $classCss = $item->type;
                    $typeLabel = ucfirst($item->type);

                    if ($item->type === 'normal') {
                        $classCss = 'ecer';
                        $typeLabel = 'Ecer';
                    } else if ($item->type === 'wholesaler') {
                        $classCss = 'reseller';
                        $typeLabel = 'Grosir';
                    }
                ?>
                  <div class="tipe-beli {{ $classCss }}">
                    {{ $typeLabel }}
                  </div>
                  <div class="nama">
                    {{ $item->product_name }}
                  </div>
                  <div class="harga">
                    @if ($item->price_sell_normal !== $item->discounted_price)
                    <p class="mb-0 small" style="text-decoration:line-through;color:#aaa;">Rp. {{ number_format($item->price_sell_normal, 0, ',', '.') }}</p>
                    @endif
                    <p class="mb-0">Rp. {{ number_format($item->discounted_price, 0, ',', '.') }}</p>
                  </div>
                  <div class="berat">
                    Berat /pcs : <span class="nilai-berat">{{ number_format($item->weight, 0, ',', '.') }}</span> gram
                  </div>
                </div>
              </div>
              <div class="col-warna cart-col">
                <div class="sub-title">Warna</div>

                @foreach($item->colors as $color)
                <div class="warna-box">
                  <div class="warna" style="background-color:{{ $color->code }};"></div>
                  <div>{{ $color->name }} <span>x</span> {{ $color->quantity }}</div>
                </div>
                @endforeach
              </div>
              <div class="col-berat cart-col">
                <div class="sub-title">Berat</div>
                <div class="berat-box">{{ $item->weight * $item->colors->sum('quantity') }} gram</div>
              </div>
              <div class="col-total-harga cart-col">
                <div class="sub-title">Subtotal</div>
                <div class="harga-box">
                  Rp. {{ number_format($item->discounted_price * $item->colors->sum('quantity'), 0, ',', '.') }}
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="cart-foot">
            <div class="voucher">
              @if ($order->coupon_id)
              <h5>Kode Kupon</h5>
              <div class="cart-foot-info">
                {{ $order->coupon_code }}
              </div>
              @endif
              @if ($order->delivery && $order->delivery->courier_id)
              <h5>Pilih Kurir Pengiriman</h5>
              <div class="cart-foot-info">
                {{ $order->delivery->courier_info }} - {{ $order->delivery->courier_service_info }}
              </div>
              <h5>Estimasi Barang Tiba</h5>
              <div class="cart-foot-info estimasi">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $estd }}</span>
              </div>
              @endif
            </div>
            <div class="info-totals">
              <div class="infos">
                <div class="row">
                  <div class="col-7 text-right">
                    Total Belanja
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">{{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                  </div>
                </div>
              </div>
              @if ($order->coupon_id)
              <div class="infos diskon">
                <div class="row">
                  <div class="col-7 text-right">
                    Diskon
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">-{{ number_format($couponDiscount, 0, ',', '.') }}</span>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              @if ($order->delivery && $order->delivery->courier_id)
              <div class="infos mb-0">
                <div class="row">
                  <div class="col-7 text-right">
                    Estimasi Biaya Kirim
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">{{ number_format($courierCost, 0, ',', '.') }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="infos info-berat">
                <div class="row">
                  <div class="col-7 text-right">
                    <div class="ml-auto">Total Berat : <span class="">{{ number_format($order->total_weight, 0, ',', '.') }}</span> gram</div>
                  </div>
                  <div class="col-5">
                  </div>
                </div>
              </div>
              @endif
              @if ($uniqueCode)
              <div class="infos">
                <div class="row">
                  <div class="col-7 text-right">
                    Kode Unik
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">{{ number_format($uniqueCode) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              @if ($order->is_keep_stock)
              <div class="infos">
                <div class="row">
                  <div class="col-7 text-right">
                    Terbayar Deposit
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">-{{ number_format($paidByDeposit) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              @endif
              <div class="infos total-main">
                <div class="row">
                  <div class="col-7 text-right">
                    Total
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">{{ number_format($amountTransfer, 0, ',', '.') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$('#copy-code').on('click', function() {
    navigator.permissions.query({name: "clipboard-write"}).then(result => {
        if (result.state == "granted" || result.state == "prompt") {
            navigator.clipboard.writeText('{{ $order->code }}');
            alert("Berhasil salin kode pemesanan");
        }
    });

    return false;
});

$('#copy-amount').on('click', function() {
    navigator.permissions.query({name: "clipboard-write"}).then(result => {
        if (result.state == "granted" || result.state == "prompt") {
            navigator.clipboard.writeText('{{ $amountTransfer }}');
            alert("Berhasil salin nominal transfer");
        }
    });

    return false;
});

@if ($order->payment)
  @if ($order->payment->type === 'transfer' || $order->payment->type === 'edc')
    @if ($order->payment->bank)
      $('#copy-account-number').on('click', function() {
          navigator.permissions.query({name: "clipboard-write"}).then(result => {
              if (result.state == "granted" || result.state == "prompt") {
                  navigator.clipboard.writeText('{{ $order->payment->bank->account_number }}');
                  alert("Berhasil salin nomor rekening");
              }
          });

          return false;
      });
    @endif
  @endif
@endif
</script>
@endsection
