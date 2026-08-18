@extends('frontend.master')

@section('content')
<div class="container">
  <div class="tracking-order-head">
    <h1>Periksa Status Pemesanan</h1>
    <p>
      Menampilkan status pesanan dengan kode <b>TDR3000OMEGA3</b>
    </p>
  </div>

  <div class="tracking-order-result history-main">
    <div class="row">
      <div class="col-md-12">
        <div class="history-list-table history-detail">
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
                <tr class="" data-href='#'>
                  <td>
                    <div class="kode-pesanan">TDR3000OMEGA3</div>
                    <div class="status-pesanan">
                      Belum Bayar
                    </div>
                    <div class="tanggal">
                      <div class="text">Tanggal</div>
                      <div class="tgl">24 September 2014</div>
                    </div>
                    <!-- <a href=""class="stretched-link"></a> -->
                  </td>
                  <td>
                    <div class="metode-bayar">
                      <img src="{{ url('img/bri.png') }}">
                      <div class="kode">0504 01 000240 30 1</div>
                      <div class="nama">a.n. Yatmi</div>
                    </div>
                  </td>
                  <td>
                    <div class="history-detail-sub-title">Total Pembayaran</div>
                    <h2 class="nominal">Rp. 920.000</h2>
                    <div class="history-detail-sub-title">Total Berat</div>
                    <div class="history-detail-berat">
                      <img src="{{ url('img/kilogram.png') }}">
                      <span>5,5 gram</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="history-detail-info">
          <div class="row">
            <div class="col-md-6">
              <div class="checkout-info-pembeli">
                <h1>Informasi Pembeli</h1>
                <div class="info-box">
                  <div class="head">
                    Nama Lengkap
                  </div>
                  <div class="main nama">Nana Nina</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat Email
                  </div>
                  <div class="main email">nananina@gmail.com</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    No. Hp
                  </div>
                  <div class="main noHp">087675593398</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat
                  </div>
                  <div class="main">
                    Jl Belimbing No. 31 Blok C RT.01 RW.02 Ds. Segerwaras Kec. Ngaglik Kab. Ngawi Jawa Timur
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="checkout-info-pembeli">
                <h1>Tujuan Pengiriman</h1>
                <div class="info-box">
                  <div class="head">
                    Nama Lengkap
                  </div>
                  <div class="main nama">Nana Nina</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat Email
                  </div>
                  <div class="main email">nananina@gmail.com</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    No. Hp
                  </div>
                  <div class="main noHp">087675593398</div>
                </div>
                <div class="info-box">
                  <div class="head">
                    Alamat
                  </div>
                  <div class="main">
                    Jl Belimbing No. 31 Blok C RT.01 RW.02 Ds. Segerwaras Kec. Ngaglik Kab. Ngawi Jawa Timur
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="cart-table">
            <div class="cart-row">
              <div class="col-product cart-col">
                <div class="img-box">
                  <img src="{{ url('img/image.png') }}">
                </div>
                <div class="text-box">
                  <div class="tipe-beli ecer">
                    Ecer
                  </div>
                  <div class="nama">
                    MINI KELLY SUNKIST+ GEMBOK
                  </div>
                  <div class="harga">
                    Rp. 800.000
                  </div>
                  <div class="berat">
                    Berat /pcs : <span class="nilai-berat">0.5</span> gram
                  </div>
                </div>
              </div>
              <div class="col-warna cart-col">
                <div class="sub-title">Warna</div>

                <div class="warna-box">
                  <div class="warna ungu"></div>
                  <div>Ungu Maghrib <span>x</span> 4</div>
                </div>
              </div>
              <div class="col-berat cart-col">
                <div class="sub-title">Berat</div>
                <div class="berat-box">2 gram</div>
              </div>
              <div class="col-total-harga cart-col">
                <div class="sub-title">Sub Title</div>
                <div class="harga-box">
                  Rp. 3.200.000
                </div>
              </div>
            </div>
            <div class="cart-row">
              <div class="col-product cart-col">
                <div class="img-box">
                  <img src="{{ url('img/image.png') }}">
                </div>
                <div class="text-box">
                  <div class="tipe-beli seri">
                    Seri
                  </div>
                  <div class="nama">
                    MINI KELLY SUNKIST+ GEMBOK
                  </div>
                  <div class="harga">
                    Rp. 800.000
                  </div>
                  <div class="berat">
                    Berat /pcs : <span class="nilai-berat">0.5</span> gram
                  </div>
                </div>
              </div>
              <div class="col-warna cart-col">
                <div class="sub-title">Warna</div>

                <div class="warna-box">
                  <div class="warna ungu"></div>
                  <div>Ungu Maghrib <span>x</span> 4</div>
                </div>
                <div class="warna-box">
                  <div class="warna ungu"></div>
                  <div>Ungu Maghrib <span>x</span> 2</div>
                </div>
                <div class="warna-box">
                  <div class="warna merah"></div>
                  <div>Ungu Maghrib <span>x</span> 1</div>
                </div>
              </div>
              <div class="col-berat cart-col">
                <div class="sub-title">Berat</div>
                <div class="berat-box">2 gram</div>
                <div class="berat-box">1 gram</div>
                <div class="berat-box">0.5 gram</div>
              </div>
              <div class="col-total-harga cart-col">
                <div class="sub-title">Sub Title</div>
                <div class="harga-box">
                  Rp. 3.200.000
                </div>
                <div class="harga-box">
                  Rp. 1.600.000
                </div>
                <div class="harga-box">
                  Rp. 800.000
                </div>
              </div>
            </div>
          </div>
          <div class="cart-foot">
            <div class="voucher">
              <h5>Kode Kupon</h5>
              <div class="cart-foot-info">
                TDR3000
              </div>
              <h5>Pilih Kurir Pengiriman</h5>
              <div class="cart-foot-info">
                JNE - Reguler
              </div>
              <h5>Estimasi Barang Tiba</h5>
              <div class="cart-foot-info estimasi">
                <i class="far fa-calendar-alt"></i>
                <span>19 - 20 September 2019</span>
              </div>
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
                      <span class="ml-auto">9.600.000</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="infos diskon">
                <div class="row">
                  <div class="col-7 text-right">
                    Diskon
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span class="ml-auto">-600.000</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="infos mb-0">
                <div class="row">
                  <div class="col-7 text-right">
                    Estimasi Biaya Kirim
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">20.000</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="infos info-berat">
                <div class="row">
                  <div class="col-7 text-right">
                    <div class="ml-auto">Total Berat : <span class="">5.5</span> gram</div>
                  </div>
                  <div class="col-5">
                  </div>
                </div>
              </div>
              <div class="infos total-main">
                <div class="row">
                  <div class="col-7 text-right">
                    Total
                  </div>
                  <div class="col-5">
                    <div class="info-box">
                      <span>Rp.</span>
                      <span class="ml-auto">9.000.000</span>
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
