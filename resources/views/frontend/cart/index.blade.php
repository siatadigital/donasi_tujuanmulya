@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
  <div class="container">
    <div class="top-nav with-border solo-top">
      <a href="#" class="back-link" onclick="window.history.back(); return false;">
        <i class="fas fa-chevron-left"></i>
        Lanjutkan Belanja
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
    @if ($cart && $cart->is_keep_stock)
    <div class="alert alert-warning text-center" role="alert" style="border-radius:0px;padding:4px;">
        Anda telah melakukan keepstock.
    </div>
    @endif
  </div>
</header>
@endsection

@section('content')
<div class="container">
  <div class="cart">
    <h1>Keranjang Belanja</h1>
  </div>

  <div class="cart-table">
    <div id="spinner" style="display:none;">
        <div class="d-flex justify-content-center">
            <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="margin:32px;">
        </div>
    </div>
    @if ($cart && $cart->is_keep_stock)
    <div class="alert alert-warning" role="alert">
        Anda dapat melakukan keep stock sesuai dengan total deposit yang Anda miliki.
    </div>
    @endif
    <p class="text-right">Total Item : <span id="total-pcs">{{ $cart_details_count }}</span> pcs</p>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th><input type="checkbox" class="check-all-item" name="check_all" {{ $isCheckedAll ? 'checked' : '' }} /></th>
            <th>Produk</th>
            <th>Warna</th>
            <th>Kuantitas</th>
            <th>Berat</th>
            <th>Harga Satuan</th>
            <th>Total harga</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($items))
            @foreach($items as $item)
            <tr class="main-row cart-item">
              <td>
                  <input
                    type="checkbox"
                    class="check-item"
                    name="is_checked[{{ $item->item_index }}]"
                    data-type="{{ $item->type }}"
                    data-product-id="{{ $item->product_id }}"
                    data-color-ids="{{ $item->colors->pluck('id')->implode(',') }}"
                    {{ $item->is_checked ? 'checked' : '' }}
                  />
              </td>
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
                  <div>
                    <span>{{ $color->name }}</span><br>
                    <span style="font-size: 14px;color: #888;">Tersisa <span class="current-total-stock">{{ $color->current_total_stock_warehouse }}</span> stok</span>
                  </div>
                </div>
                @endforeach
              </td>
              <td class="col-qty">
                @foreach($item->colors as $color)
                <div class="qty-box">
                  @if ($item->type === 'seri')
                  <div class="pt_Quantity disabled" data-current-total-stock="{{ $color->current_total_stock_warehouse }}">
                    <input class="no-spinner" type="number" min="1" step="1" value="{{ $color->quantity }}" data-oldValue="{{ $color->quantity }}" data-inc="1" data-url="{{ route('ajax.cart.item.update') }}" data-productID="{{ $item->product_id }}" data-colorID="{{ $color->id }}" disabled>
                  </div>
                  @else
                  <div class="pt_Quantity" data-current-total-stock="{{ $color->current_total_stock_warehouse }}">
                    <input class="no-spinner" type="number" min="1" step="1" value="{{ $color->quantity }}" data-oldValue="{{ $color->quantity }}" data-inc="1" data-url="{{ route('ajax.cart.item.update') }}" data-productID="{{ $item->product_id }}" data-colorID="{{ $color->id }}">
                  </div>
                  @endif
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
                  <span class="ml-auto total-jumlah">{{ $color->quantity * $item->discounted_price }}</span>
                </div>
                @endforeach
              </td>
              <td class="col-action">
                @if ($item->type === 'seri')
                <a class="hapus-action" href="" data-type="seri" data-url="{{ route('ajax.cart.item.delete', array('salesDetailID' => $item->colors[0]->item_id)) }}">Hapus</a>
                @else
                @foreach($item->colors as $index => $color)
                <div class="action-box">
                    <a class="hapus-action" href="" data-index="{{ $index }}" data-type="normal" data-url="{{ route('ajax.cart.item.delete', array('salesDetailID' => $color->item_id)) }}">Hapus</a>
                </div>
                @endforeach
                @endif
              </td>
            </tr>
            @endforeach
            @if(count($items) <= 0)
              <tr class="main-row">
                <td colspan="8" class="text-center">
                  Belum ada produk yang ditambahkan dikeranjang ini
                </td>
              </tr>
            @endif
          @else
            <tr class="main-row">
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
      <h5>Anda memiliki Kupon Belanja?</h5>
      <div class="form-group">
        <input id="input-voucher" type="text" class="form-control" placeholder="Kode Kupon" value="{{ $cart ? $cart->coupon_code ? $cart->coupon_code : '' : '' }}" data-url="{{ route('ajax.coupon.check') }}">
        <div id="spinner-voucher" style="display:none;">
            <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
        </div>
        <div class="voucher-info valid {{ $cart ? $cart->coupon_id ? 'visible' : '' : '' }}">
          <i class="fas fa-check"></i>
          <span>Kupon Berhasil Ditambahkan</span>
        </div>
        <div class="voucher-info invalid">
          <i class="fas fa-times"></i>
          <span>Kupon Tidak Valid</span>
        </div>
      </div>
    </div>
    <div class="info-totals">
      <div class="infos">
        <div class="row">
          <div class="col-6 text-right">
            Sub Total
          </div>
          <div class="col-6">
            <div class="info-box">
              <span>Rp.</span>
              <span class="sub-total ml-auto">{{ $totalPrice }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="infos">
        <div class="row">
          <div class="col-6 text-right">
            Diskon
          </div>
          <div class="col-6">
            <div class="info-box">
              <span>Rp.</span>
              <span class="ml-auto discount">{{ $cart ? $discount : 0 }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="infos">
        <div class="row">
          <div class="col-6 text-right">
            Total Belanja
          </div>
          <div class="col-6">
            <div class="info-box">
              <span>Rp.</span>
              <span class="ml-auto total-belanja">{{ $cart ? $totalPrice - $discount : 0 }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="infos">
        <div class="row">
          <div class="col-6 text-right">
            Total Berat
          </div>
          <div class="col-6">
            <div class="info-box">
              <div class="ml-auto"><span class="total-berat">{{ $cart ? $cart->total_weight : 0 }}</span> gram</div>
            </div>
          </div>
        </div>
      </div>
      <a href="{{ route('frontend.checkout.step1') }}" class="btn">Checkout</a>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('.check-item').on('click', function() {
        var productId = String($(this).data('product-id'));
        var colorIds = String($(this).data('color-ids')).split(',');
        var type = $(this).data('type');
        var isChecked = Number($(this).prop('checked'));
        var index = $(this).parents('tr').index();
        var self = $(this);

        var reqItem = colorIds.map(function(colorId) {
            return $.ajax({
                url: "{{ route('ajax.cart.item.update') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    product_id: productId,
                    color_id: colorId,
                    type: type,
                    item_index: index,
                    is_checked: isChecked
                },
            });
        });

        // $('#spinner').show();
        // $('.table-responsive').hide();

        Promise.all(reqItem)
            .then(function() {
                // $('#spinner').hide();
                // $('.table-responsive').show();
                window.location.reload();
            })
            .catch(function(error) {
                // $('#spinner').hide();
                // $('.table-responsive').show();
                self.prop('checked', !isChecked);

                const message = error.status === 400 ? error.responseJSON.error : 'Terjadi kesalahan saat pemilihan item';

                swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            });
    });

    $('.check-all-item').on('click', function() {
        var isChecked = Number($(this).prop('checked'));
        var totalItems = $('.check-item').length;

        var reqItems = $('.check-item').map(function(index) {
            var productId = String($(this).data('product-id'));
            var colorIds = String($(this).data('color-ids')).split(',');
            var type = $(this).data('type');
            var index = $(this).parents('tr').index();

            var reqItem = colorIds.map(function(colorId) {
                return $.ajax({
                    url: "{{ route('ajax.cart.item.update') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        product_id: productId,
                        color_id: colorId,
                        type: type,
                        item_index: index,
                        is_checked: isChecked
                    },
                });
            });

            return reqItem;
        })
        .get();

        reqItems = [].concat.apply([], reqItems);

        // $('#spinner').show();
        // $('.table-responsive').hide();

        Promise.all(reqItems)
            .then(function() {
                // $('#spinner').hide();
                // $('.table-responsive').show();
                window.location.reload();
            })
            .catch(function() {
                // $('#spinner').hide();
                // $('.table-responsive').show();
                swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat pemilihan item'
                });
            });
    });
});
</script>
@endsection
