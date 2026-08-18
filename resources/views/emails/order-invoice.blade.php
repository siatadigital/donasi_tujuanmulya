<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
  <style>
  h3 {
    font-size: 18px;
  }

  .alert {
    border-radius: 8px;
    border: 1px solid #c3e6cb;
    background: #d4edda;
    color: #155724;
    padding: 12px;
    margin-bottom: 16px;
  }

  .kode-pesanan {
    font-weight: bold;
  }

  .status-pesanan {
    border-radius: 8px;
    background: #E4F8FF;
    color: #3034DB;
    padding: 8px;
    font-size: 10px;
  }

  .table-main {
    width: 100%;
    margin-bottom: 64px;
    border-collapse: collapse;
  }

  .table-main thead {
    background: #E4F8FF;
  }

  .table-main th {
    padding: 8px;
  }

  .table-main .metode-bayar {
    padding: 12px;
  }

  .bank-logo {
    width: 48px;
    margin-bottom: 8px;
  }

  .icon-weight {
    width: 16px;
  }

  .nominal {
    font-size: 14px;
    margin-top: 4px;
  }

  .table-main .label {
    color: #aaa;
    font-size: 10px;
    margin-bottom: 4px;
  }

  .info-box {
    margin-bottom: 8px;
  }

  .info-box .head {
    color: #aaa;
  }

  .info-box .main {
    margin-left: 8px;
    font-size: 14px;
  }

  .table-status {
    width: 100%;
    margin: auto;
    margin-top: 32px;
    border-collapse: collapse;
  }

  .table-status thead {
    background: #E4F8FF;
  }

  .table-status th {
      padding: 8px;
      text-align: left;
  }

  .table-status td {
      padding: 8px;
      border: 1px solid #ddd;
  }

  .row {
  }

  .row .item {
    margin-bottom: 32px;
  }

  .tipe-beli {
    padding: 4px 15px;
    display: inline-block;
    border-radius: 3px;
    margin-bottom: 8px;
  }

  .tipe-beli.ecer,
  .tipe-beli.deposit {
    color: #AF7927;
    background: #FFEFD6;
  }

  .tipe-beli.seri {
    color: #3034DB;
    background: #DDDFF9;
  }

  .tipe-beli.reseller {
    color: #C136A6;
    background: #FDE8F7;
  }

  .img-product {
      width: 96px;
      height: 96px;
      object-fit: cover;
      border: 1px solid #eee;
      margin-bottom: 8px;
  }

  .cart-row {
    border-bottom: 1px solid #eee;
    padding: 14px 0px;
  }

  .nama-produk {
    font-size: 16px;
    margin-bottom: 2px;
  }

  .normal-price {
    text-decoration: line-through;
    color: #aaa;
    font-size: 12px;
    margin-bottom: 0px;
  }

  .price-used {
    margin-top: 0px;
    margin-bottom: 4px;
    font-size: 16px;
  }

  .warna-box {
    margin-bottom: 6px;
    margin-left: 8px;
    font-size: 16px;
  }

  .warna {
    width: 18px;
    height: 18px;
    border-radius: 100%;
    float: left;
    margin-right: 8px;
  }

  .berat {
    color: #aaa;
  }

  .sub-title {
    color: #aaa;
    margin-bottom: 2px;
  }

  .cart-col {
    margin-bottom: 12px;
  }

  .berat-box {
    margin-left: 8px;
    font-size: 16px;
  }

  .harga-box {
    font-size: 16px;
    margin-left: 8px;
  }

  .cart-foot-info {
    font-size: 16px;
    margin-left: 8px;
    margin-bottom: 8px;
  }

  .info-totals .info-box {
    font-size: 16px;
    margin-left: 8px;
  }

  .infos {
    margin-bottom: 8px;
  }
  </style>
</head>

<body>
  <h1>Pesanan #{{ $order->code }}</h1>

  <div>
    <div>
      <h3>Detail Riwayat Pemesanan</h3>
      @if ($order->point)
      <div class="alert" role="alert">
        Pesanan ini berpotensi mendapatkan {{ $order->point }} poin.
      </div>
      @endif

      <table class="table-main">
        <thead>
          <tr>
            <th></th>
            <th class="text-center">Metode Pembayaran</th>
            <th class="text-center">Nominal</th>
          </tr>
        </thead>
        <tbody>
          <tr class="" data-href='#'>
            <td>
              <div class="kode-pesanan">{{ $order->code }}</div>
              <br>
              <div class="status-pesanan">
                {{ $order->status_name }}
              </div>
            </td>
            @if ($order->payment)
            <td>
              @if ($order->payment->type === 'transfer' || $order->payment->type === 'edc')
              <div class="metode-bayar">
                @if ($order->payment->bank)
                @if ($order->payment->bank->bank_logo)
                <img class="bank-logo"
                  src="{{ $message->embed(public_path('uploads/banks/' . $order->payment->bank->bank_logo)) }}">
                @else
                <img class="bank-logo" src="{{ $message->embed(public_path('uploads/banks/default.png')) }}">
                @endif
                <div class="kode">{{ $order->payment->bank->account_number }}</div>
                <div class="nama">a.n. {{ $order->payment->bank->account_name }}</div>
                @endif
              </div>
              @endif
            </td>
            @endif
            <td>
              <div class="history-detail-sub-title label">Total Pembayaran</div>
              <h2 class="nominal">Rp. {{ number_format($amountTransfer, 0, ',', '.') }}</h2>
            </td>
          </tr>
          <tr class="" data-href='#'>
            <td>
              <div class="tanggal">
                <div class="text label">Tanggal</div>
                <div class="tgl">{{ $date }}</div>
              </div>
            </td>
            <td></td>
            <td>
              <div class="history-detail-sub-title label">Total Berat</div>
              <div class="history-detail-berat">
                <img class="icon-weight" src="{{ $message->embed('img/kilogram.png') }}">
                <span>{{ number_format($order->total_weight, 0, ',', '.') }} gram</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="history-detail-info">
      <div class="row">
        <div class="item">
          @if ($order->delivery && $order->delivery->origin_fullname)
          <div class="checkout-info-pembeli">
            <h3>Informasi Pembeli</h3>
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
                <p style="margin-bottom:0px;margin-top:0px;">{{ $order->delivery->origin_address }}</p>
                <p style="margin-bottom:0px;margin-top:0px;">{{ $order->delivery->originSubdistrict->province->name }},
                  {{ $order->delivery->originSubdistrict->city->name }}, {{ $order->delivery->originSubdistrict->name }}
                </p>
                <p style="margin-top:0px;">{{ $order->delivery->origin_postcode }}</p>
              </div>
            </div>
          </div>
          @endif
        </div>
        <div class="item">
          @if ($order->delivery && $order->delivery->destination_fullname)
          <div class="checkout-info-pembeli">
            <h3>Tujuan Pengiriman</h3>
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
                <p style="margin-bottom:0px;margin-top:0px;">{{ $order->delivery->destination_address }}</p>
                <p style="margin-bottom:0px;margin-top:0px;">
                  {{ $order->delivery->destinationSubdistrict->province->name }},
                  {{ $order->delivery->destinationSubdistrict->city->name }},
                  {{ $order->delivery->destinationSubdistrict->name }}</p>
                <p style="margin-top:0px;">{{ $order->delivery->destination_postcode }}</p>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>

      <table class="table-status">
        <thead>
          <tr>
            <th>Status</th>
            <th>Tanggal</th>
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
      <hr>
      <p>Total Item : <span id="total-pcs">{{ $order->details->sum('quantity') }}</span> pcs</p>
      <hr>
      <div class="cart-table">
        @foreach($items as $item)
        <div class="cart-row">
          <div class="col-product cart-col">
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
              <div class="nama-produk">
                {{ $item->product_name }}
              </div>
              <div class="harga">
                @if ($item->price_sell_normal !== $item->discounted_price)
                <p class="normal-price">Rp.
                  {{ number_format($item->price_sell_normal, 0, ',', '.') }}</p>
                @endif
                <p class="price-used">Rp. {{ number_format($item->discounted_price, 0, ',', '.') }}</p>
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
          <p class="sub-title">Kode Kupon</p>
          <div class="cart-foot-info">
            {{ $order->coupon_code }}
          </div>
          @endif
          @if ($order->delivery && $order->delivery->courier_id)
          <p class="sub-title">Kurir Pengiriman</p>
          <div class="cart-foot-info">
            {{ $order->delivery->courier_info }} - {{ $order->delivery->courier_service_info }}
          </div>
          <p class="sub-title">Estimasi Barang Tiba</p>
          <div class="cart-foot-info estimasi">
            <span>{{ $estd }}</span>
          </div>
          @endif
        </div>
        <div class="info-totals">
          <div class="infos">
            <div class="row">
              <div class="col-7 text-right sub-title">
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
              <div class="col-7 text-right sub-title">
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
              <div class="col-7 text-right sub-title">
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
              <div class="col-7 text-right sub-title">
                <div class="ml-auto">Total Berat</div>
              </div>
              <div class="col-5">
                <div class="info-box">
                  <span class="ml-auto">{{ number_format($order->total_weight, 0, ',', '.') }} gram</span>
                </div>
              </div>
            </div>
          </div>
          @endif
          @if ($uniqueCode)
          <div class="infos">
            <div class="row">
              <div class="col-7 text-right sub-title">
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
              <div class="col-7 text-right sub-title">
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
              <div class="col-7 text-right sub-title">
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
</body>

</html>
