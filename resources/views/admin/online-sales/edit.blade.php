@extends('admin.master')

@section('title', 'RTL - Ubah Penjualan')

@section('css')
<style type="text/css">
.section-header,
.card {
    margin-bottom: 10px !important;
}
.card-header {
    padding: 5px 25px !important;
    min-height: auto !important;
}
.card-body {
    padding: 5px 25px !important;
}
.form-group {
    margin-bottom: 5px !important;
}
.select2-container .select2-selection--multiple, .select2-container .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered,
.select2-container--default .select2-selection--multiple .select2-selection__arrow,
.select2-container--default .select2-selection--single .select2-selection__arrow,
.form-control {
    min-height: 30px !important;
    height: 30px !important;
    line-height: 30px !important;
}
.table td,
.table th {
    height: 30px !important;
}

.table td:nth-child(3),
.table td:nth-child(7) {
    padding: 0px !important;
}
</style>
@endsection

@section('content')
<div class="section-header">
  <h1>Ubah Penjualan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.online-sales.index') }}">Penjualan</a></div>
    <div class="breadcrumb-item">Ubah Penjualan</div>
  </div>
</div>

<div class="section-body">
  <!-- <h2 class="section-title">Ubah Penjualan</h2>
  <p class="section-lead">
    Form untuk ubah penjualan
  </p> -->

  <form action="{{ route('admin.online-sales.update', ['id' => $sales->id]) }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      <div class="col-md-6" id="information-data">
        <div class="card">
          <div class="card-header">
            <h4>(1.) Informasi Penjualan</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Customer (Opsional)</label>
                        <select name="user_customer_id" class="form-control select2" disabled>
                            <option
                                value=""
                                data-fullname=""
                                data-email=""
                                data-phone=""
                                data-address=""
                                @if (!$sales->user_customer_id)
                                selected
                                @endif
                            >
                                Pilih Customer
                            </option>
                            @foreach ($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                data-fullname="{{ $customer->fullname }}"
                                data-email="{{ $customer->email }}"
                                data-phone="{{ $customer->phone }}"
                                data-address="{{ $customer->address }}"
                                @if ($sales->user_customer_id == $customer->id)
                                selected
                                @endif
                            >
                                {{ $customer->username }}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->first('user_customer_id'))
                        <div class="invalid-feedback" style="display: block;">{{ $errors->first('user_customer_id') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Tanggal',
                        'type' => 'text',
                        'name' => 'date',
                        'required' => TRUE,
                        'class' => 'datepicker',
                        'value' => $sales->date,
                        'error' => $errors->first('date'),
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Kupon (Opsional)',
                        'type' => 'select',
                        'name' => 'coupon_id',
                        'options' => $coupons,
                        'value' => $sales->coupon_id,
                        'error' => $errors->first('coupon_id'),
                        'additional' => ['disabled' => 'disabled']
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Jenis Stok',
                        'type' => 'select',
                        'name' => 'stock_type',
                        'options' => $stockTypes,
                        'value' => $sales->stock_type,
                        'error' => $errors->first('stock_type'),
                        'additional' => ['disabled' => 'disabled']
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Dikirim',
                        'type' => 'select',
                        'name' => 'is_deliver',
                        'options' => [
                            0 => 'Tidak',
                            1 => 'Ya',
                        ],
                        'value' => (int) !!$sales->delivery,
                        'error' => $errors->first('is_deliver'),
                        'additional' => ['disabled' => 'disabled']
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Catatan (Opsional)',
                'type' => 'textarea',
                'name' => 'notes',
                'value' => $sales->notes,
                'error' => $errors->first('notes'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
      <div class="col-md-6" id="origin-data">
        <div class="card">
          <div class="card-header">
            <h4>(2.) Data Pembeli</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Nama Lengkap',
                        'type' => 'text',
                        'name' => 'origin_fullname',
                        'value' => $sales->delivery ? $sales->delivery->origin_fullname : '',
                        'error' => $errors->first('origin_fullname'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Email',
                        'type' => 'text',
                        'name' => 'origin_email',
                        'value' => $sales->delivery ? $sales->delivery->origin_email : '',
                        'error' => $errors->first('origin_email'),
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Phone',
                        'type' => 'text',
                        'name' => 'origin_phone',
                        'value' => $sales->delivery ? $sales->delivery->origin_phone : '',
                        'error' => $errors->first('origin_phone'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Kota/Kabupaten',
                        'type' => 'select',
                        'name' => 'origin_subdistrict_id',
                        'options' => [],
                        'value' => $sales->delivery ? $sales->delivery->origin_subdistrict_id : '',
                        'error' => $errors->first('origin_subdistrict_id'),
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Alamat',
                'type' => 'textarea',
                'name' => 'origin_address',
                'value' => $sales->delivery ? $sales->delivery->origin_address : '',
                'error' => $errors->first('origin_address'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
      <div class="col-md-12" id="cart-data">
        <div class="card">
          <div class="card-header">
            <h4>(3.) Keranjang</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Produk',
                        'type' => 'select',
                        'name' => 'product_id',
                        'options' => $products,
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Beli Sebagai',
                        'type' => 'select',
                        'name' => 'buy_type',
                        'options' => [
                            'normal' => 'Ecer',
                            'seri' => 'Seri',
                        ],
                    ])
                    @endcomponent
                </div>
            </div>
            <div id="row-color-quantity">
                <hr>
                <div id="spinner-color" style="display:none;margin-top:16px;">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                    </div>
                </div>
                <div class="row" id="color-inputs"></div>
            </div>
            <div class="row" id="row-shoes-colors" style="display:none;">
                <div class="col-md-12">
                    @component('admin.components.form-input', [
                        'label' => 'Warna',
                        'type' => 'select',
                        'name' => 'shoes_color_id',
                        'options' => ['' => 'Pilih Warna'],
                        'additional' => [
                            'style' => 'width:100%',
                        ]
                    ])
                    @endcomponent
                </div>
            </div>
            <div id="spinner-seri-colors" style="display:none;margin-top:32px;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                </div>
            </div>
            <div id="seri-colors" style="margin-top:12px;display:none;"></div>
            <br>
            <div class="text-right">
                <button type="button" id="btn-add" class="btn btn-primary">Ubahkan</button>
            </div>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Warna</th>
                        <th>Harga Jual</th>
                        <th>Berat (g)</th>
                        <th>Kuantitas</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            @if ($errors->first('items'))
            <p class="text-center invalid-feedback" style="display: block;">{{ $errors->first('items') }}</p>
            @endif
            <div style="display:none;" id="hidden-form"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6" id="destination-data">
        <div class="card">
          <div class="card-header">
            <h4>(4.) Tujuan Pengiriman</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Email',
                        'type' => 'text',
                        'name' => 'destination_email',
                        'value' => $sales->delivery ? $sales->delivery->destination_email : '',
                        'error' => $errors->first('destination_email'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Nama Lengkap',
                        'type' => 'text',
                        'name' => 'destination_fullname',
                        'value' => $sales->delivery ? $sales->delivery->destination_fullname : '',
                        'error' => $errors->first('destination_fullname'),
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Phone',
                        'type' => 'text',
                        'name' => 'destination_phone',
                        'value' => $sales->delivery ? $sales->delivery->destination_phone : '',
                        'error' => $errors->first('destination_phone'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Kota/Kabupaten',
                        'type' => 'select',
                        'name' => 'destination_subdistrict_id',
                        'options' => [],
                        'value' => $sales->delivery ? $sales->delivery->destination_subdistrict_id : '',
                        'error' => $errors->first('destination_subdistrict_id'),
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Alamat',
                'type' => 'textarea',
                'name' => 'destination_address',
                'value' => $sales->delivery ? $sales->delivery->destination_address : '',
                'error' => $errors->first('destination_address'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card" id="courier-data">
          <div class="card-header">
            <h4>(5.) Jasa Ekspedisi</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Ekspedisi Kurir',
                        'type' => 'select',
                        'name' => 'courier_id',
                        'options' => $couriers,
                        'value' => $sales->delivery ? $sales->delivery->courier_id : '',
                        'error' => $errors->first('courier_id'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    <div id="input-service">
                    @component('admin.components.form-input', [
                        'label' => 'Layanan',
                        'type' => 'select',
                        'name' => 'courier_service_id',
                        'options' => [],
                        'value' => $sales->delivery ? $sales->delivery->courier_service_id : '',
                        'error' => $errors->first('courier_service_id'),
                    ])
                    @endcomponent
                    </div>
                    <div id="spinner-service" style="display:none;">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                        </div>
                    </div>
                    <input type="hidden" name="courier_info" value="">
                    <input type="hidden" name="courier_service_name" value="">
                    <input type="hidden" name="courier_service_info" value="">
                    <input type="hidden" name="courier_estd" value="">
                    <input type="hidden" name="courier_cost" value="">
                </div>
            </div>
          </div>
        </div>
        <div class="card" id="payment-data">
          <div class="card-header">
            <h4>(6.) Pembayaran</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Tipe',
                        'type' => 'select',
                        'name' => 'payment_type',
                        'options' => [
                            'cash' => 'Tunai',
                            'transfer' => 'Transfer',
                            'edc' => 'EDC',
                            'deposit' => 'Deposit',
                        ],
                        'value' => $sales->payment->type,
                        'error' => $errors->first('payment_type'),
                        'additional' => ['disabled' => 'disabled']
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6 input-bank-id" style="visibility:hidden;">
                    @component('admin.components.form-input', [
                        'label' => 'Bank',
                        'type' => 'select',
                        'name' => 'payment_bank_id',
                        'options' => $banks,
                        'value' => $sales->payment->bank_id,
                        'error' => $errors->first('payment_bank_id'),
                        'additional' => ['disabled' => 'disabled']
                    ])
                    @endcomponent
                </div>
            </div>
            <br>
            <div id="spinner-otp" style="display:none;margin-top:32px;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                </div>
            </div>
            <div id="input-otp">
                @component('admin.components.form-input', [
                    'label' => 'Kode OTP',
                    'type' => 'text',
                    'name' => 'otp_code',
                    'value' => '',
                    'error' => $errors->first('otp_code'),
                ])
                @endcomponent
            </div>
            <br/>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-danger" id="btn-otp">Dapatkan Kode OTP</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('js')
@if (session()->has('message'))
<script>
  iziToast.error({
    title: 'Gagal!',
    message: '{{ session("message") }}',
    position: 'topRight'
  });
</script>
@endif
<script>
var cleaveOptions = { numeral: true };

var $customers = $('select[name=user_customer_id]');
var $coupons = $('select[name=coupon_id]');
var $products = $('select[name=product_id]');
var $shoesColors = $('select[name=shoes_color_id]');
var $buyType = $('select[name=buy_type]');
var $btnAdd = $('#btn-add');
var $hiddenForm = $('#hidden-form');
var $itemWrapper = $('.table tbody');
var $colorInputs = $('#color-inputs');
var items = {!! json_encode($items) !!};
var seriColors = [];
var isShoes = false;
var isKeepStock = !!{{ $isKeepStock }};
var depositDiscountType = {{ $depositDiscountType ?: 0 }};
var coupon = null;
var productInfo = null;
var stockType = $('select[name=stock_type]').val();

var updateItemsType = function() {
    var totalQuantity = items.reduce((acc, item) => {
        return acc + _.sumBy(item.colors, 'quantity');
    }, 0);

    items = items.map(item => {
        if (item.type === 'seri') return item;

        var price = item.priceSellNormal;
        var type = 'normal';

        if (totalQuantity >= 4) {
            price = item.priceSellReseller;
            type = 'reseller';
        }

        if (totalQuantity >= 50) {
            price = item.priceSellWholesaler50;
            type = 'wholesaler';
        }

        if (totalQuantity >= 100) {
            price = item.priceSellWholesaler100;
            type = 'wholesaler';
        }

        if (totalQuantity >= 200) {
            price = item.priceSellWholesaler200;
            type = 'wholesaler';
        }

        if (totalQuantity >= 400) {
            price = item.priceSellWholesaler400;
            type = 'wholesaler';
        }

        if (totalQuantity >= 600) {
            price = item.priceSellWholesaler600;
            type = 'wholesaler';
        }

        if (isKeepStock && depositDiscountType) {
            price = item['depositDiscountType'];
            type = 'deposit';
        }

        var discount = (price * item.discount) / 100;
        var discountedPrice = discount ? price - discount : price;
        var isValidType = ['normal', 'reseller', 'wholesaler', 'deposit'].includes(item.type);

        if (isValidType) {
            var colors = item.colors.map(color => {
                return Object.assign(color, {
                    subtotal: discountedPrice * color.quantity
                });
            });

            return Object.assign(item, {
                type: type,
                currentPrice: price,
                priceUsed: discountedPrice,
                colors: colors
            });
        }

        return item;
    });
};

var resetItemForm = function() {
    $products.val('').trigger('change');
    $buyType.val('normal').trigger('change');
    $colorInputs.empty();
    $colorInputs.hide();
}

var renderItems = function() {
    var subtotal = items.reduce(function(acc, item) {
        return acc + _.sumBy(item.colors, 'subtotal');
    }, 0);
    var discountPercent = coupon ? coupon.discount_percent : 0;
    var discount = subtotal * discountPercent / 100;
    var courierCost = Number($('input[name=courier_cost]').val());
    var uniqueCode = {{ $uniqueCode }};
    var total = subtotal - discount + courierCost + uniqueCode;

    $itemWrapper.empty();
    $hiddenForm.empty();

    for (var [index, item] of items.entries()) {
        var type = 'Ecer';
        var typeClass = 'badge-cart-normal';

        switch (item.type) {
            case 'normal':
                type = 'Ecer';
                typeClass = 'badge-cart-normal';
                break;

            case 'reseller':
                type = 'Reseller';
                typeClass = 'badge-cart-mass';
                break;

            case 'deposit':
                type = 'Deposit';
                typeClass = 'badge-cart-mass';
                break;

            case 'wholesaler':
                type = 'Grosir';
                typeClass = 'badge-cart-mass';
                break;

            case 'seri':
                type = 'Seri';
                typeClass = 'badge-cart-seri';
                break;

            default:
                break;
        }

        $itemWrapper.append(`
            <tr>
                <td>
                    <p>${item.productName}</p>
                    <span class="badge ${typeClass}">${type}</span>
                </td>
                <td>
                    ${item.colors.map(color => `
                        <div class="cart-item-spacer">
                            <div style="width:24px;height:24px;background:${color.hexCode}"></div>
                        </div>
                    `).join('')}
                </td>
                <td>
                    ${item.colors.map(color => `
                        <div class="cart-item-spacer">
                            Rp. ${toCurrency(item.currentPrice)}
                        </div>
                    `).join('')}
                </td>
                <td>
                    ${item.colors.map(color => `
                        <div class="cart-item-spacer">
                            <p class="text-center">${toCurrency(item.weight)}g</p>
                        </div>
                    `).join('')}
                </td>
                <td>
                    ${item.colors.map(color => {
                        if (item.type === 'seri') {
                            return `
                                <div class="cart-item-spacer">
                                    <p class="text-center">${toCurrency(color.quantity)}</p>
                                    <input
                                        type="hidden"
                                        class="form-control form-quantity"
                                        style="width:80px"
                                        name="quantities[]"
                                        value="${color.quantity}"
                                        min="1"
                                        data-product-id="${item.productId}"
                                        data-color-id="${color.colorId}"
                                    />
                                </div>
                            `;
                        } else {
                            return `
                                <div class="cart-item-spacer">
                                    <input
                                        type="number"
                                        class="form-control form-quantity"
                                        style="width:80px"
                                        name="quantities[]"
                                        value="${color.quantity}"
                                        min="1"
                                        data-product-id="${item.productId}"
                                        data-color-id="${color.colorId}"
                                    />
                                </div>
                            `;
                        }
                    }).join('')}
                </td>
                <td>
                    ${item.colors.map(color => `
                        <div class="cart-item-spacer">
                            <p class="text-center">${item.discount}%</p>
                        </div>
                    `).join('')}
                </td>
                <td>
                    ${item.colors.map(color => `
                        <div class="cart-item-spacer">
                            Rp. ${toCurrency(color.subtotal)}
                        </div>
                    `).join('')}
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-delete">Hapus</button>
                </td>
            </tr>
        `);

        $hiddenForm.append(`
            <div class="hidden-item" data-id="${item.id}">
              <input type="hidden" name="items[${index}][product_id]" value="${item.productId}" />
              <input type="hidden" name="items[${index}][current_price]" value="${item.currentPrice}" />
              <input type="hidden" name="items[${index}][price_used]" value="${item.priceUsed}" />
              <input type="hidden" name="items[${index}][price_buy]" value="${item.priceBuy}" />
              <input type="hidden" name="items[${index}][price_sell_normal]" value="${item.priceSellNormal}" />
              <input type="hidden" name="items[${index}][price_sell_reseller]" value="${item.priceSellReseller}" />
              <input type="hidden" name="items[${index}][price_sell_seri]" value="${item.priceSellSeri}" />
              <input type="hidden" name="items[${index}][price_sell_wholesaler_50]" value="${item.priceSellWholesaler50}" />
              <input type="hidden" name="items[${index}][price_sell_wholesaler_100]" value="${item.priceSellWholesaler100}" />
              <input type="hidden" name="items[${index}][price_sell_wholesaler_200]" value="${item.priceSellWholesaler200}" />
              <input type="hidden" name="items[${index}][price_sell_wholesaler_400]" value="${item.priceSellWholesaler400}" />
              <input type="hidden" name="items[${index}][price_sell_wholesaler_600]" value="${item.priceSellWholesaler600}" />
              <input type="hidden" name="items[${index}][weight]" value="${item.weight}" />
              <input type="hidden" name="items[${index}][discount]" value="${item.discount}" />
              <input type="hidden" name="items[${index}][type]" value="${item.type}" />
              ${item.colors.map((color, colorIndex) => `
                <input type="hidden" name="items[${index}][colors][${colorIndex}][color_id]" value="${color.colorId}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][quantity]" value="${color.quantity}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][subtotal]" value="${color.subtotal}" />
              `)}
            </div>
        `);
    }

    $itemWrapper.append(
        '<tr><th colspan="7"><h6 class="text-right mb-0">Subtotal</h6></th><td>Rp. ' + toCurrency(subtotal) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Kupon (' + discountPercent + '%)</h6></th><td>Rp. ' + toCurrency(discount) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Ongkir</h6></th><td>Rp. ' + toCurrency(courierCost) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Kode Unik</h6></th><td>Rp. ' + toCurrency(uniqueCode) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Total</h6></th><td>Rp. ' + toCurrency(total) + '</td></tr>'
    );
}

updateItemsType();
renderItems();

$customers.on('change', function() {
    var data = $(this).find(':selected').data();

    $('input[name=origin_fullname]').val(data.fullname);
    $('input[name=origin_email]').val(data.email);
    $('input[name=origin_phone]').val(data.phone);
    $('textarea[name=origin_address]').val(data.address);
});

$coupons.on('change', function() {
    var couponId = $(this).val();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.coupons') }}?id=" + couponId,
        success: function(response) {
            coupon = response.data[0];

            updateItemsType();
            renderItems();
        },
        error: function() {
            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar kupon',
                position: 'topRight'
            });
        }
    });
});

$coupons.change();

$products.on('change', function() {
    var productId = $(this).val();
    var product = items.find(item => item.productId == productId);
    var buyType = $buyType.val();
    var stockType = $('select[name=stock_type]').val();

    if (productId == '') {
        return false;
    }

    $('#spinner-color').show();
    $('#color-inputs').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.colors') }}?product_id=" + productId + "&stock_type=" + stockType,
        success: function(response) {
            $('#spinner-color').hide();
            $('#color-inputs').show();

            var colors = response.data.slice(1);

            $colorInputs.empty();

            for (const color of colors) {
                var itemQuantity = 0;

                if (product) {
                    var itemColor = product.colors.find(itemColor => itemColor.colorId == color.id);

                    itemQuantity = itemColor ? itemColor.quantity : 0;
                }

                var max = color.total_stock - itemQuantity;

                $colorInputs.append(`
                    <div class="col-md-3">
                        <div class="d-flex">
                            <div class="color-item" style="background:${color.hex_code}"></div>
                            <p style="margin-right:8px;">${color.text} (<span class="total-stock">${max}</span>)</p>
                        </div>
                        <input
                            type="number"
                            name="input_quantities[]"
                            class="form-control"
                            min="0"
                            max="${max}"
                            value="0"
                            data-id="${color.id}"
                            data-code="${color.hex_code}"
                            data-stock="${color.total_stock}"
                        />
                    </div>
                `);
            }
        },
        error: function() {
            $('#spinner-color').hide();
            $('#color-inputs').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar warna',
                position: 'topRight'
            });
        }
    });

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.products') }}?id=" + productId,
        success: function(response) {
            var product = response.data[0];

            if (!product) {
                iziToast.error({
                    title: 'Gagal!',
                    message: 'Gagal mengambil daftar produk',
                    position: 'topRight'
                });

                return;
            }

            productInfo = {
                productId: productId,
                priceBuy: product.price_buy,
                priceSellNormal: product.price_sell_normal,
                priceSellReseller: product.price_sell_reseller,
                priceSellSeri: product.price_sell_seri,
                priceSellWholesaler50: product.price_sell_wholesaler_50,
                priceSellWholesaler100: product.price_sell_wholesaler_100,
                priceSellWholesaler200: product.price_sell_wholesaler_200,
                priceSellWholesaler400: product.price_sell_wholesaler_400,
                priceSellWholesaler600: product.price_sell_wholesaler_600,
                weight: product.weight,
                discount: product.discount || 0,
                productName: product.title
            };
        },
        error: function() {
            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar produk',
                position: 'topRight'
            });
        }
    });

    if (buyType === 'seri') {
        seriColors = [];

        $('#seri-colors').empty();
        $('#row-shoes-colors').hide();
        $('#spinner-seri-colors').show();

        $.ajax({
            method: "GET",
            url: "{{ route('admin.api.seri-colors') }}?product_id=" + productId + "&stock_type=" + stockType,
            success: function(response) {
                $('#spinner-seri-colors').hide();

                isShoes = response.data.is_shoes;
                seriColors = response.data.colors;

                if (isShoes) {
                    $('#row-shoes-colors').show();
                    $shoesColors.empty();

                    for (var color of seriColors) {
                        $shoesColors.append(`
                            <option value="${color.id}">${color.label}</option>
                        `);
                    }

                    $('#seri-colors').append('<p>Warna Terpilih :</p>');

                    for (var size of seriColors[0].sizes) {
                        $('#seri-colors').append(`
                            <div style="display:flex;align-items:center;">
                                <div style="width:24px;height:24px;margin-right:8px;background:${size.hex_code}" />
                                <p style="margin:0px;">${size.text} x ${size.quantity}</p>
                            </div>
                        `);
                    }
                } else {
                    $('#seri-colors').append('<p>Warna Terpilih :</p>');

                    for (var color of seriColors) {
                        $('#seri-colors').append(`
                            <div style="display:flex;align-items:center;">
                                <div style="width:24px;height:24px;margin-right:8px;background:${color.hex_code}" />
                                <p style="margin:0px;">${color.text} x ${color.quantity}</p>
                            </div>
                        `);
                    }
                }
            },
            error: function(error) {
                $('#spinner-seri-colors').hide();

                seriColors = error.responseJSON.data.colors;

                $('#seri-colors').empty();

                iziToast.error({
                    title: 'Gagal!',
                    message: 'Tidak bisa beli sebagai seri karena stok tidak cukup',
                    position: 'topRight'
                });
            }
        });
    }
});

$shoesColors.on('change', function() {
    var colorId = $(this).val();
    var sizes = seriColors.find(color => color.id === colorId).sizes;

    $('#seri-colors').empty();
    $('#seri-colors').append('<p>Warna Terpilih :</p>');

    for (var size of sizes) {
        $('#seri-colors').append(`
            <div style="display:flex;align-items:center;">
                <div style="width:24px;height:24px;margin-right:8px;background:${size.hex_code}" />
                <p style="margin:0px;">${size.text} x ${size.quantity}</p>
            </div>
        `);
    }
});

$buyType.on('change', function() {
    var value = $(this).val();
    var productId = $products.val();
    var stockType = $('select[name=stock_type]').val();

    seriColors = [];

    if (value === 'seri') {
        $('#seri-colors').empty();
        $('#row-shoes-colors').hide();
        $('#spinner-seri-colors').show();

        $.ajax({
            method: "GET",
            url: "{{ route('admin.api.seri-colors') }}?product_id=" + productId + "&stock_type=" + stockType,
            success: function(response) {
                $('#spinner-seri-colors').hide();

                isShoes = response.data.is_shoes;
                seriColors = response.data.colors;

                if (isShoes) {
                    $('#row-shoes-colors').show();
                    $shoesColors.empty();

                    for (var color of seriColors) {
                        $shoesColors.append(`
                            <option value="${color.id}">${color.label}</option>
                        `);
                    }

                    $('#seri-colors').append('<p>Warna Terpilih :</p>');

                    for (var size of seriColors[0].sizes) {
                        $('#seri-colors').append(`
                            <div style="display:flex;align-items:center;">
                                <div style="width:24px;height:24px;margin-right:8px;background:${size.hex_code}" />
                                <p style="margin:0px;">${size.text} x ${size.quantity}</p>
                            </div>
                        `);
                    }
                } else {
                    $('#seri-colors').append('<p>Warna Terpilih :</p>');

                    for (var color of seriColors) {
                        $('#seri-colors').append(`
                            <div style="display:flex;align-items:center;">
                                <div style="width:24px;height:24px;margin-right:8px;background:${color.hex_code}" />
                                <p style="margin:0px;">${color.text} x ${color.quantity}</p>
                            </div>
                        `);
                    }
                }
            },
            error: function(error) {
                $('#spinner-seri-colors').hide();

                seriColors = error.responseJSON.data.colors;

                $('#seri-colors').empty();

                iziToast.error({
                    title: 'Gagal!',
                    message: 'Tidak bisa beli sebagai seri karena stok tidak cukup',
                    position: 'topRight'
                });
            }
        });

        $('#row-color-quantity').hide();
        $('#seri-colors').show();
    } else {
        $('#row-color-quantity').show();
        $('#row-shoes-colors').hide();
        $('#seri-colors').hide();
    }
});

$colorInputs.on('keyup', 'input', function() {
    var quantity = $(this).val();
    var colorId = $(this).data('id');
    var totalStock = $(this).data('stock');
    var productId = $products.val();
    var product = items.find(item => item.productId == productId);
    var itemQuantity = 0;

    if (product) {
        var color = product.colors.find(color => color.colorId == colorId);

        itemQuantity = color ? color.quantity : 0;
    }

    var stock = quantity <= totalStock - itemQuantity ? totalStock - itemQuantity - quantity : 0;

    $(this).parent().find('.total-stock').text(stock);
});

$btnAdd.on('click', function() {
    var productId = $products.val();
    var buyType = $buyType.val();

    if (buyType === 'seri') {
        var colors = seriColors;

        if (isShoes) {
            var shoesColorId = $shoesColors.val();
            var sizes = seriColors.find(color => color.id === shoesColorId).sizes;

            colors = sizes;
        }

        var totalQuantity = _.sumBy(colors, 'quantity');
        var isQuantityEnough = totalQuantity === 6;

        if (!isQuantityEnough) {
            iziToast.error({
                title: 'Gagal!',
                message: 'Stok tidak mencukupi untuk membeli sebagai seri',
                position: 'topRight'
            });

            return;
        }

        var discount = productInfo.priceSellSeri * productInfo.discount / 100;
        var discountedPrice = productInfo.priceSellSeri - discount;

        colors = colors.map(color => {
            var subtotal = discountedPrice * color.quantity;

            return {
                id: Date.now(),
                colorId: color.id,
                hexCode: color.hex_code,
                quantity: color.quantity,
                subtotal: subtotal,
            }
        });

        var newItem = Object.assign({
            id: Date.now(),
            type: 'seri',
            currentPrice: productInfo.priceSellSeri,
            priceUsed: discountedPrice,
            colors: colors
        }, productInfo);

        items.push(newItem);
    } else {
        $colorInputs.find('.col-md-3').each(function () {
            var colorId = $(this).find('input').data('id');
            var hexCode = $(this).find('input').data('code');
            var totalStock = $(this).find('input').data('stock');
            var quantity = $(this).find('input').val();
            var isQuantityValid = quantity > 0;

            if (!isQuantityValid) return;

            var product = items.find(item => {
                var isValidType = ['normal', 'reseller', 'wholesaler', 'deposit'].includes(item.type);

                return item.productId == productId && isValidType;
            });

            if (product) {
                var color = product.colors.find(color => color.colorId == colorId);

                if (color) {
                    var hasStock = (color.quantity + separatedToNumber(quantity)) <= totalStock;

                    if (!hasStock) return;

                    items = items.map(item => {
                        if (item.productId == productId) {
                            var discount = item.priceSellNormal * item.discount / 100;
                            var discountedPrice = item.priceSellNormal - discount;

                            item.currentPrice = item.priceSellNormal;
                            item.priceUsed = discountedPrice;

                            item.colors = item.colors.map(color => {
                                if (color.colorId == colorId) {
                                    var subtotal = (color.quantity + separatedToNumber(quantity)) * discountedPrice;

                                    color.quantity += separatedToNumber(quantity);
                                    color.subtotal = subtotal;
                                }

                                return color;
                            });
                        }

                        return item;
                    });
                } else {
                    var hasStock = separatedToNumber(quantity) <= totalStock;

                    if (!hasStock) return;

                    items = items.map(item => {
                        if (item.productId == productId) {
                            var discount = item.priceSellNormal * item.discount / 100;
                            var discountedPrice = item.priceSellNormal - discount;
                            var subtotal = separatedToNumber(quantity) * discountedPrice;

                            item.currentPrice = item.priceSellNormal;
                            item.priceUsed = discountedPrice;

                            item.colors.push({
                                id: Date.now(),
                                colorId: separatedToNumber(colorId),
                                hexCode: hexCode,
                                quantity: separatedToNumber(quantity),
                                subtotal: subtotal,
                            });
                        }

                        return item;
                    });
                }
            } else {
                var hasStock = separatedToNumber(quantity) <= totalStock;

                if (!hasStock) return;

                var discount = productInfo.priceSellNormal * productInfo.discount / 100;
                var discountedPrice = productInfo.priceSellNormal - discount;
                var subtotal = separatedToNumber(quantity) * discountedPrice;

                var newItem = Object.assign({
                    id: Date.now(),
                    type: 'normal',
                    currentPrice: productInfo.priceSellNormal,
                    priceUsed: discountedPrice,
                    colors: [
                        {
                            id: Date.now(),
                            colorId: separatedToNumber(colorId),
                            hexCode: hexCode,
                            quantity: separatedToNumber(quantity),
                            subtotal: subtotal,
                        }
                    ]
                }, productInfo);

                items.push(newItem);
            }
        });
    }

    productInfo = null;

    updateItemsType();
    renderItems();
    resetItemForm();
});

$itemWrapper.on('click', '.btn-delete', function() {
    var $row = $(this).parents('tr');
    var index = $row.index();

    items.splice(index, 1);
    updateItemsType();
    renderItems();
});

$itemWrapper.on('change', '.form-quantity', function() {
    var data = $(this).data();
    var quantity = Number($(this).val());

    items = items.map((item, index) => {
        var isTypeValid = ['normal', 'reseller', 'wholesaler', 'deposit'].includes(item.type);

        if (item.productId == data.productId && isTypeValid) {
            item.colors = item.colors.map(color => {
                if (color.colorId == data.colorId) {
            var discount = item.priceSellNormal * item.discount / 100;
                    var discountedPrice = item.priceSellNormal - discount;
                    var subtotal = discountedPrice * quantity;

                    color.quantity = quantity;
                    color.subtotal = subtotal;
                }

                return color;
            });
        }

        return item;
    });

    updateItemsType();
    renderItems();
});

$(window).on('load', function(){
    var isDeliver = !!Number($('select[name=is_deliver]').val());
    var command = isDeliver ? 'show' : 'hide';

    $('#origin-data')[command]();
    $('#destination-data')[command]();
    $('#courier-data')[command]();

    if (isDeliver) {
        $('#cart-data h4').html('(3.) Keranjang');
        $('#payment-data h4').html('(6.) Pembayaran');
    }else {
        $('#cart-data h4').html('(2.) Keranjang');
        $('#payment-data h4').html('(3.) Pembayaran');
    }
});

$('select[name=is_deliver]').on('change', function() {
    var isDeliver = !!Number($(this).val());
    var command = isDeliver ? 'show' : 'hide';

    $('#origin-data')[command]();
    $('#destination-data')[command]();
    $('#courier-data')[command]();

    if (isDeliver) {
        $('#cart-data h4').html('(3.) Keranjang');
        $('#payment-data h4').html('(6.) Pembayaran');
    }else {
        $('#cart-data h4').html('(2.) Keranjang');
        $('#payment-data h4').html('(3.) Pembayaran');
    }
});

$('select[name=courier_id]').on('change', function() {
    var courierId = $(this).val();
    var courierInfo = $(this).find(':selected').text();
    var subdistrictId = $('select[name=destination_subdistrict_id]').val();

    var totalWeight = items.reduce(function(acc, item) {
        return acc + item.weight * _.sumBy(item.colors, 'quantity');
    }, 0);

    $('input[name=courier_info]').val(courierInfo);

    $('#spinner-service').show();
    $('#input-service').hide();

    if (!totalWeight) {
        swal({
            icon: 'error',
            title: 'Gagal',
            text: 'Minimal wajib memasukan 1 item terlebih dahulu !',
        });

        $(this).val('');

        return;
    }

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.courier-costs') }}",
        data: {
            courier_id: courierId,
            weight: totalWeight,
            destination_subdistrict_id: subdistrictId,
        },
        success: function(response) {
            $('#spinner-service').hide();
            $('#input-service').show();

            var html = "";

            if (response.length) {
                var firstItem = response[0];

                $('input[name=courier_service_name]').val(firstItem.service);
                $('input[name=courier_service_info]').val(firstItem.description);
                $('input[name=courier_estd]').val(firstItem.cost[0].etd);
                $('input[name=courier_cost]').val(firstItem.cost[0].value);

                response.forEach(function(item, index) {
                    html += "<option value='" + item.service + "' data-service='" + item.service + "' data-description='" + item.description + "' data-cost='" + item.cost[0].value + "' data-etd='" + item.cost[0].etd + "'>" + item.service + " | Rp. " + toCurrency(item.cost[0].value) + "</option>";
                });

                $('select[name=courier_service_id]').empty().append(html);
                $('select[name=courier_service_id]').val(firstItem.service);
            } else {
                $('input[name=courier_service_name]').val('');
                $('input[name=courier_service_info]').val('');
                $('input[name=courier_estd]').val('');
                $('input[name=courier_cost]').val('');
                $('select[name=courier_service_id]').empty();
            }

            updateItemsType();
            renderItems();
        },
        error: function() {
            $('#spinner-service').hide();
            $('#input-service').show();

            swal({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal mengambil biaya pengiriman',
            });
        }
    });
});

$('select[name=courier_service_id]').on('change', function() {
    var $current = $(this).find(':selected');
    var service = $current.data('service');
    var description = $current.data('description');
    var etd = $current.data('etd');
    var cost = $current.data('cost');

    $('input[name=courier_service_name]').val(service);
    $('input[name=courier_service_info]').val(description);
    $('input[name=courier_estd]').val(etd);
    $('input[name=courier_cost]').val(cost);

    updateItemsType();
    renderItems();
});

$('select[name=payment_type]').on('change', function() {
    var type = $(this).val();
    var isBankRelated = type === 'edc' || type === 'transfer';
    var isVisible = isBankRelated ? 'visible' : 'hidden';

    $('.input-bank-id').css('visibility', isVisible);
});

$('select[name=stock_type]').on('change', function() {
    var newStockType = $(this).val();
    var hasItems = items.length > 0;
    var isDifferentType = newStockType !== stockType;

    if (hasItems) {
        if (isDifferentType) {
            iziToast.error({
                title: 'Gagal!',
                message: 'Maaf, Tidak boleh memasukan item dari jenis stok yang berbeda !',
                position: 'topRight'
            });

            $(this).val(stockType);
        }
    } else {
        stockType = newStockType;
    }
});

$('#btn-otp').on('click', function() {
    $('#spinner-otp').show();
    $('#input-otp').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.owner-otps') }}",
        success: function(response) {
            $('#spinner-otp').hide();
            $('#input-otp').show();

            swal({
                icon: 'success',
                title: 'Berhasil',
                text: 'Berhasil mendapatkan kode OTP, silahkan cek email owner',
            });
        },
        error: function() {
            $('#spinner-otp').hide();
            $('#input-otp').show();

            swal({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal mendapatkan kode OTP',
            });
        }
    });
});

$(document).ready(function() {
    var configSelect2 = {
        minimumInputLength: 3,
        placeholder: 'Cari Kecamatan',
        ajax: {
            url: '{{ route("ajax.subdistricts") }}',
            dataType: 'json',
            delay: 250,
            processResults: function (response) {
                return {
                    results: response.data
                };
            }
        }
    };

    $('select[name=origin_subdistrict_id]').select2(configSelect2);
    $('select[name=destination_subdistrict_id]').select2(configSelect2);

    @if (!!$sales->delivery)
    $('select[name=origin_subdistrict_id]').append(`
        <option value="{{ $sales->delivery->origin_subdistrict_id }}" selected>
        {{ $sales->delivery->originSubdistrict->province->name }} - {{ $sales->delivery->originSubdistrict->city->name }} - {{ $sales->delivery->originSubdistrict->name }}
        </option>
    `);

    $('select[name=destination_subdistrict_id]').append(`
        <option value="{{ $sales->delivery->destination_subdistrict_id }}" selected>
        {{ $sales->delivery->destinationSubdistrict->province->name }} - {{ $sales->delivery->destinationSubdistrict->city->name }} - {{ $sales->delivery->destinationSubdistrict->name }}
        </option>
    `);

    $('select[name=courier_id]').change();
    @endif

    $('select[name=payment_type]').change();
});
</script>
@endsection
