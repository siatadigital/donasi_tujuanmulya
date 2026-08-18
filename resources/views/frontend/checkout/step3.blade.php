@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
    <div class="container">
      <div class="top-nav with-border solo-top">
        <a href="#" class="back-link" onclick="window.history.back(); return false;">
          <i class="fas fa-chevron-left"></i>
          Ubah Informasi Tujuan Pengiriman
        </a>
        <a href="{{ route('frontend.home') }}" class="web-logo mx-auto">
          <img src="{{ url('img/RTL_Logo.png') }}">
        </a>
        @if ($deposit_amount)
        <p class="deposit-amount">
            Deposit: Rp. {{ number_format($deposit_amount) }}
        </p>
        @endif
      </div>
    </div>
  </header>
@endsection

@section('content')
<div class="container">
  <div class="grafis-checkout">
    <div class="grafis-checkout-lines">
      <div class="row mx-0">
        <div class="col-4 px-0">
          <div class="lines active"></div>
        </div>
        <div class="col-4 px-0">
          <div class="lines active"></div>
        </div>
        <div class="col-4 px-0">
          <div class="lines"></div>
        </div>
      </div>
    </div>
    <div class="grafis-checkout-circle">
      <div class="row mx-0">
        <div class="col-3 px-0">
          <div class="info-box active">
            <div class="circle"></div>
            <div class="text">
              Informasi Pembeli
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box active">
            <div class="circle"></div>
            <div class="text">
              Tujuan Pengiriman
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box active">
            <div class="circle"></div>
            <div class="text">
              Checkout
            </div>
          </div>
        </div>
        <div class="col-3 px-0">
          <div class="info-box">
            <div class="circle"></div>
            <div class="text">
              Pembayaran
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <form method="post" action="{{ route('frontend.checkout.step3.post') }}" class="checkout-checkout-main">
    {{ csrf_field() }}
    <div class="row">
      <div class="col-md-6">
        <div class="checkout-info-pembeli">
          <h1>Informasi Pembeli</h1>
          <div class="info-box">
            <div class="head">
              Nama Lengkap
            </div>
            <div class="main nama">{{ $delivery ? $delivery->origin_fullname : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              Alamat Email
            </div>
            <div class="main email">{{ $delivery ? $delivery->origin_email : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              No. Hp
            </div>
            <div class="main noHp">{{ $delivery ? $delivery->origin_phone : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              Alamat
            </div>
            <div class="main">
              {!! $delivery ? $delivery->origin_address . '<br />' . $delivery->originSubdistrict->province->name . ', ' . $delivery->originSubdistrict->city->name . ', ' . $delivery->originSubdistrict->name : '' !!}
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
            <div class="main nama">{{ $delivery ? $delivery->destination_fullname : '' }}</div>
          </div>
          <!-- <div class="info-box">
            <div class="head">
              Alamat Email
            </div>
            <div class="main email">{{ $delivery ? $delivery->destination_email : '' }}</div>
          </div> -->
          <div class="info-box">
            <div class="head">
              No. Hp
            </div>
            <div class="main noHp">{{ $delivery ? $delivery->destination_phone : '' }}</div>
          </div>
          <div class="info-box">
            <div class="head">
              Alamat
            </div>
            <div class="main">
              {!! $delivery ? $delivery->destination_address . '<br />' . $delivery->destinationSubdistrict->province->name . ', ' . $delivery->destinationSubdistrict->city->name . ', ' . $delivery->destinationSubdistrict->name : '' !!}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="cart-table static">
      <div class="table-responsive">
        <p class="text-right">Total Item : <span id="total-pcs">{{ $cart_details_count }}</span> pcs</p>
        <table class="table">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Warna</th>
              <th>Kuantitas</th>
              <th>Berat</th>
              <th>Harga Satuan</th>
              <th>Total harga</th>
            </tr>
          </thead>
          <tbody>
            @if(isset($items))
              @foreach($items as $item)
                <tr class="main-row-without-calculate">
                  <td>
                    <div class="col-product">
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
                            <a href="{{ route('frontend.product.detail', array('slug' => $item->product_slug)) }}">{{ $item->product_name }}</a>
                        </div>
                        <div class="berat">
                          Berat /pcs : <span class="nilai-berat">{{ $item->weight }}</span> gram
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="col-warna">
                    @foreach($item->colors as $color)
                    <div class="warna-box">
                        <div class="warna" style="background-color: {{ $color->code }}"></div>
                        <span>{{ $color->name }}</span>
                    </div>
                    @endforeach
                  </td>
                  <td class="col-qty">
                    @foreach($item->colors as $color)
                    <div class="qty-box text-center">
                      {{ $color->quantity }}
                    </div>
                    @endforeach
                  </td>
                  <td class="col-berat">
                    @foreach($item->colors as $color)
                    <div class="berat-box"><span class="jumlah-berat">{{ $color->quantity * $item->weight }}</span> gram</div>
                    @endforeach
                  </td>
                  <td class="col-harga-satuan">
                    @foreach($item->colors as $color)
                    <div class="harga-box">
                        <div>
                            @if ($item->discounted_price !== $item->price_sell_normal)
                            <p class="small mb-0" style="text-decoration:line-through;color:#aaa;">Rp. {{ $item->price_sell_normal }}</p>
                            @endif
                            <span>Rp.</span>
                            <span class="ml-auto harga-satuan">{{ $item->discounted_price }}</span>
                        </div>
                    </div>
                    @endforeach
                  </td>
                  <td class="col-total-harga">
                    @foreach($item->colors as $color)
                    <div class="harga-box">
                        <span>Rp.</span>
                        <span class="ml-auto harga-satuan">{{ $color->quantity * $item->discounted_price }}</span>
                    </div>
                    @endforeach
                  </td>
                </tr>
              @endforeach
              @if(count($cart->details) <= 0)
                <tr class="main-row-without-calculate">
                  <td colspan="7" class="text-center">
                    Belum ada produk yang ditambahkan dikeranjang ini
                  </td>
                </tr>
              @endif
            @else
              <tr class="main-row-without-calculate">
                <td colspan="7" class="text-center">
                  Belum ada produk yang ditambahkan dikeranjang ini
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
    <div class="cart-foot">
      <div class="voucher">
        <h5>Catatan (opsional)</h5>
        <div class="form-group">
          <textarea class="form-control" name="notes" placeholder="Ketik catatan disini..." data-url="{{ route('ajax.coupon.check') }}">{{ $cart ? $cart->notes ? $cart->notes : '' : '' }}</textarea>
        </div>
        <h5>Anda memiliki Kupon Belanja?</h5>
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Kode Kupon" value="{{ $cart ? $cart->coupon_code ? $cart->coupon_code : '' : '' }}" data-url="{{ route('ajax.coupon.check') }}">
          <div class="voucher-info valid {{ $cart ? $cart->coupon_id ? 'visible' : '' : '' }}">
            <i class="fas fa-check"></i>
            <span>Kupon Berhasil Ditambahkan</span>
          </div>
          <div class="voucher-info invalid">
            <i class="fas fa-times"></i>
            <span>Kupon Tidak Valid</span>
          </div>
        </div>
        <h5>Pilih Kurir Pengiriman</h5>
        <div class="form-group kurir">
          <select class="form-control" name="courier_id" id="courier-select" required>
            <option selected>Pilih Kurir</option>
            @foreach($couriers as $item)
              @if($delivery and $delivery->courier_id == $item->id)
                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
              @else
                <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endif
            @endforeach
          </select>
          <i class="select-icon fas fa-angle-down"></i>
        </div>
        <div class="courier_service_box" @if($delivery and $delivery->courier_id) style="display: block;" @endif>
          <h5>Pilih Layanan Kurir</h5>
          <div id="spinner-courier" style="display:none;">
            <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
          </div>
          <div id="input-courier" class="form-group kurir">
            <select class="form-control" name="courier_service_name" id="courier-service-select" required>
            </select>
            <i class="select-icon fas fa-angle-down"></i>
          </div>
          <input type="hidden" name="courier_service_name" id="courier_service_name" value="{{ $delivery ? $delivery->courier_service_name : '' }}" required>
          <input type="hidden" name="courier_service_info" id="courier_service_info" value="{{ $delivery ? $delivery->courier_service_info : '' }}" required>
          <input type="hidden" name="courier_estd" id="courier_estd" value="{{ $delivery ? $delivery->courier_estd : '' }}" required>
          <input type="hidden" name="courier_cost" id="courier_cost" value="{{ $delivery ? $delivery->courier_cost : '' }}" required>
        </div>
        <div class="courier_service_estimated" @if($delivery and $delivery->courier_id) style="display: block;" @endif>
          <h5>Estimasi Barang Tiba</h5>
          <div class="form-group estimasi">
            <i class="far fa-calendar-alt"></i>
            <span>{{ $delivery ? $delivery->courier_estd : '' }} Hari</span>
          </div>
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
                <span class="sub-total sub-total2 ml-auto">{{ $totalPrice }}</span>
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
                <span>Rp.</span>
                <span class="ml-auto discount discount2">{{ $cart ? $discount : 0 }}</span>
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
                <span class="ml-auto biaya-kirim biaya-kirim2">{{ $delivery ? $delivery->courier_cost ? $delivery->courier_cost : 0 : 0 }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="infos info-berat">
          <div class="row">
            <div class="col-7 text-right">
              <div class="ml-auto">Total Berat : <span class="total-berat">{{ $cart ? $cart->total_weight : 0 }}</span> gram</div>
            </div>
            <div class="col-5">
            </div>
          </div>
        </div>
        <div class="infos total-main">
          <div class="row">
            <div class="col-7 text-right">
              Grand Total
            </div>
            <div class="col-5">
              <div class="info-box">
                <span>Rp.</span>
                <span class="ml-auto total-belanja total-belanja2">{{ $cart ? $totalPrice - $discount + $delivery->courier_cost : 0 }}</span>
              </div>
            </div>
          </div>
        </div>
        @if ($deposit_amount)
        <div class="infos total-main">
          <div class="row">
            <div class="col-7 text-right">
              Deposit
            </div>
            <div class="col-5">
              <div class="info-box">
                <span>Rp.</span>
                <span class="ml-auto">{{ $deposit_amount }}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="infos total-main">
          <div class="row">
            <div class="col-7 text-right">
              Sisa Deposit
            </div>
            <div class="col-5">
              <div class="info-box">
                <span>Rp.</span>
                <span class="ml-auto">{{ $cart ? $deposit_amount - ($totalPrice - $discount) : 0 }}</span>
              </div>
            </div>
          </div>
        </div>
        @endif
        <input type="submit" class="btn" value="Checkout">
      </div>
    </div>
  </form>
</div>
@endsection

@section('js')
  <script type="text/javascript">
    $(document).ready(function(){

      function getServiceCourier() {
        $('#spinner-courier').show();
        $('#input-courier').hide();

        $.ajax({
            method: "GET",
            url: "{{ url('/api/courier/cost') }}",
            data: {
              courier_id: $('#courier-select').val(),
              sales_id: "{{ $cart->id }}",
              destination_subdistrict_id: "{{ $delivery->destination_subdistrict_id }}",
              destination_type: "{{ $delivery->destination_type }}",
            },
            success: function(response) {
              $('#spinner-courier').hide();
              $('#input-courier').show();

              var html = "<option selected>Pilih Layanan</option>";
              var courierServiceName = "{{ $delivery ? $delivery->courier_service_name : '' }}";

              response.forEach(function(item, index) {
                if (courierServiceName == "") {
                  html += "<option value='" + item.service + "' data-service='" + item.service + "' data-description='" + item.description + "' data-cost='" + item.cost[0].value + "' data-etd='" + item.cost[0].etd + "'>" + item.service + " | Rp. " + item.cost[0].value + "</option>";
                }else {
                  if (courierServiceName == item.service) {
                    html += "<option value='" + item.service + "' data-service='" + item.service + "' data-description='" + item.description + "' data-cost='" + item.cost[0].value + "' data-etd='" + item.cost[0].etd + "' selected>" + item.service + " | Rp. " + item.cost[0].value + "</option>";
                  }else {
                    html += "<option value='" + item.service + "' data-service='" + item.service + "' data-description='" + item.description + "' data-cost='" + item.cost[0].value + "' data-etd='" + item.cost[0].etd + "'>" + item.service + " | Rp. " + item.cost[0].value + "</option>";
                  }
                }
              });

              $('#courier-service-select').append(html);
            },
            error: function() {
                $('#spinner-courier').hide();
                $('#input-courier').show();

                $('.courier_service_box').hide();
                swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal mengambil biaya pengiriman',
                });
            }
        });
      }

      @if($delivery and $delivery->courier_id)
        getServiceCourier();
      @endif
      $('#courier-select').change(function(){
        $('.courier_service_box').fadeIn();
        $('.courier_service_estimated').hide();
        $('#courier-service-select').html("");
        $('#courier-service-select').append("<option selected></option>");
        var subTotal = parseFloat($('.sub-total2').html());
        var diskon = parseFloat($('.discount2').html());
        var grandTotal = subTotal - diskon;

        $('#courier_service_name').val("");
        $('#courier_service_info').val("");
        $('#courier_estd').val("");
        $('#courier_cost').val("");
        $('.biaya-kirim2').html(0);
        $('.total-belanja2').html(grandTotal);

        getServiceCourier();
        return false;
      });

      $('#courier-service-select').change(function(){
        $('.courier_service_estimated').fadeIn();
        var serviceName = $(this).find('option:selected').attr('data-service');
        var serviceInfo = $(this).find('option:selected').attr('data-description');
        var etd = $(this).find('option:selected').attr('data-etd');
        var cost = parseFloat($(this).find('option:selected').attr('data-cost'));
        var subTotal = parseFloat($('.sub-total2').html());
        var diskon = parseFloat($('.discount2').html());
        var grandTotal = subTotal - diskon;
        if (etd == "") {
          etd = "Tidak diketahui berapa";
        }

        $('#courier_service_name').val(serviceName);
        $('#courier_service_info').val(serviceInfo);
        $('#courier_estd').val(etd);
        $('#courier_cost').val(cost);

        $('.biaya-kirim2').html(cost);
        $('.total-belanja2').html(grandTotal + cost);
        $('.courier_service_estimated .estimasi span').html(etd + ' Hari');
        return false;
      });
    });
  </script>
@endsection
