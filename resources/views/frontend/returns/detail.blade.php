@extends('frontend.master')

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      @include('frontend.partials.sidebar')

      <div class="col-md-9">
        <div class="history-list-table history-detail">
          <div class="sub-title">
            Detail Riwayat Pengembalian
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Kode Pengembalian</th>
                  <th>Status</th>
                  <th class="text-center">Total Berat</th>
                </tr>
              </thead>
              <tbody>
                <tr class="" data-href='#'>
                  <td>
                    <div class="kode-pesanan">{{ $return->code }}</div>
                    <div class="tanggal">
                      <div class="text">Tanggal</div>
                      <div class="tgl">{{ $date }}</div>
                    </div>
                  </td>
                  <td>
                    <div class="text-center" style="margin-top: 24px">
                        <div class="status-pesanan">
                            {{ $return->is_accept === NULL ? 'Menunggu' : ($return->is_accept ? 'Diterima' : 'Ditolak') }}
                        </div>
                    </div>
                  </td>
                  <td>
                    <div class="history-detail-berat text-center" style="padding-left:0px;margin-top:24px;">
                      <img src="{{ url('img/kilogram.png') }}">
                      <span>{{ $return->total_weight }} gram</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <p style="font-size:18px;">Catatan</p>
            <p style="font-size:18px;">{{ $return->notes }}</p>
            <br>
            <p style="font-size:18px;">Bukti Foto</p>
            <div class="d-flex">
                @foreach($return->getPhotos() as $photo)
                <img style="width:160px;height:160px;object-fit:cover;border:1px solid #eee;margin-right:16px;" src="{{ $photo }}" />
                @endforeach
            </div>
            <br><br>
          </div>
        </div>

        <div class="history-detail-info">
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
                @foreach($item->colors as $color)
                <div class="berat-box">{{ $color->weight * $color->quantity }} gram</div>
                @endforeach
              </div>
              <div class="col-total-harga cart-col">
                <div class="sub-title">Subtotal</div>
                @foreach($item->colors as $color)
                <div class="harga-box">
                  Rp. {{ number_format($color->subtotal, 0, ',', '.') }}
                </div>
                @endforeach
              </div>
            </div>
            @endforeach
          </div>
          <div class="cart-foot">
            <div class="info-totals">
              <div class="infos total-main">
                <div class="row">
                  <div class="col-7 text-right">
                    Total
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">{{ number_format($return->total_price, 0, ',', '.') }}</span>
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
