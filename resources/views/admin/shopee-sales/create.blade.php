@extends('admin.master')

@section('title', 'RTL - Tambah Penjualan')

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
</style>
@endsection

@section('content')
<div class="section-header">
  <h1>Tambah Penjualan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.shopee-sales.index') }}">Penjualan</a></div>
    <div class="breadcrumb-item">Tambah Penjualan</div>
  </div>
</div>

<div class="section-body">
  <!-- <h2 class="section-title">Tambah Penjualan</h2>
  <p class="section-lead">
    Form untuk tambah penjualan
  </p> -->

  <form action="{{ route('admin.shopee-sales.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12" id="information-data">
        <div class="card">
          <div class="card-header">
            <h4>(1.) Informasi Penjualan</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Customer (Opsional)</label>
                        <select name="user_customer_id" class="form-control select2">
                            <option
                                value=""
                                data-fullname=""
                                data-email=""
                                data-phone=""
                                data-address=""
                                @if (!old('user_customer_id'))
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
                                @if (old('user_customer_id') == $customer->id)
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
                        'value' => old('date'),
                        'error' => $errors->first('date'),
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Catatan (Opsional)',
                'type' => 'textarea',
                'name' => 'notes',
                'value' => old('notes'),
                'error' => $errors->first('notes'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
      <div class="col-md-12" id="cart-data">
        <div class="card">
          <div class="card-header">
            <h4>(2.) Keranjang</h4>
          </div>
          <div class="card-body">
            @component('admin.components.form-input', [
                'label' => 'Produk',
                'type' => 'select',
                'name' => 'product_id',
                'options' => $products,
            ])
            @endcomponent
            <hr>
            <div id="spinner-color" style="display:none;margin-top:16px;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                </div>
            </div>
            <div class="row" id="color-inputs"></div>
            <div class="text-right">
                <button type="button" id="btn-add" class="btn btn-primary">Tambahkan</button>
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
            <div id="spinner-coupons" style="display:none;margin-top:32px;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                </div>
            </div>
            <div id="coupon-input">
                @component('admin.components.form-input', [
                    'label' => 'Kupon (Opsional)',
                    'type' => 'select',
                    'name' => 'coupon_id',
                    'options' => ['' => 'Pilih Kupon'],
                    'value' => old('coupon_id'),
                    'error' => $errors->first('coupon_id'),
                ])
                @endcomponent
                <input type="hidden" name="coupon_name" value="{{ old('coupon_name') }}">
                <input type="hidden" name="coupon_code" value="{{ old('coupon_code') }}">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card" id="payment-data">
          <div class="card-header">
            <h4>(3.) Pembayaran</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Tipe',
                        'type' => 'select',
                        'name' => 'payment_type',
                        'options' => ['transfer' => 'Transfer'],
                        'value' => old('payment_type'),
                        'error' => $errors->first('payment_type'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Bank',
                        'type' => 'select',
                        'name' => 'payment_bank_id',
                        'options' => $banks,
                        'value' => old('payment_bank_id'),
                        'error' => $errors->first('payment_bank_id'),
                    ])
                    @endcomponent
                </div>
            </div>
            <br/>
            <div class="card-footer text-right">
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
var $btnAdd = $('#btn-add');
var $hiddenForm = $('#hidden-form');
var $itemWrapper = $('.table tbody');
var $colorInputs = $('#color-inputs');
var items = JSON.parse(`{!! collect(old("items"))->toJson() !!}`);
var coupon = null;
var productInfo = null;
var stockType = $('select[name=stock_type]').val();

var fetchCoupons = function() {
    coupon = null;

    $('#spinner-coupons').show();
    $('#coupon-input').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.coupons') }}",
        data: {
            bought_items: items,
        },
        success: function(response) {
            $('#spinner-coupons').hide();
            $('#coupon-input').show();

            var coupons = response.data;
            var defaultOptions = [`<option value="" selected>Pilih Kupon</option>`];

            var couponOptions = coupons.map((coupon) => (`
                <option value="${coupon.id}">
                    ${coupon.name} - ${coupon.code}
                </option>
            `));

            var options = defaultOptions.concat(couponOptions).join('');

            $coupons.empty().append(options);
            updateItemsType();
            renderItems();
        },
        error: function() {
            $('#spinner-coupons').hide();
            $('#coupon-input').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil data kupon',
                position: 'topRight'
            });
        }
    });
};

var updateItemsType = function() {
    var paymentType = $('select[name=payment_type]').val();
    var dataCustomer = $customers.find(':selected').data();
    var isReseller = dataCustomer.reseller ? dataCustomer.reseller : false;

    var totalQuantity = items.reduce((acc, item) => {
        return acc + _.sumBy(item.colors, 'quantity');
    }, 0);

    items = items.map(item => {
        if (item.type === 'seri') return item;

        var price = item.priceSellNormal;
        var type = 'normal';
        if (totalQuantity >= 4 || isReseller) {
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
    $colorInputs.empty();
    $colorInputs.hide();
}

var renderItems = function() {
    var totalQuantity = items.reduce(function(acc, item) {
        return acc + _.sumBy(item.colors, 'quantity');
    }, 0);
    var subtotal = items.reduce(function(acc, item) {
        return acc + _.sumBy(item.colors, 'subtotal');
    }, 0);

    var courierCost = Number($('input[name=courier_cost]').val());
    var isPercent = coupon ? coupon.type === 'percent' : null;
    var discountValue = coupon ? coupon.discount : 0;
    var discount = isPercent ? subtotal * discountValue / 100 : discountValue;
    var total = subtotal - discount + courierCost;

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
                    ${item.colors.map(color => `
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
                    `).join('')}
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
              <input type="hidden" name="items[${index}][product_name]" value="${item.productName}" />
              ${item.colors.map((color, colorIndex) => `
                <input type="hidden" name="items[${index}][colors][${colorIndex}][color_id]" value="${color.colorId}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][hex_code]" value="${color.hexCode}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][quantity]" value="${color.quantity}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][subtotal]" value="${color.subtotal}" />
              `)}
            </div>
        `);
    }

    $itemWrapper.append(
        '<tr><th colspan="7"><h6 class="text-right mb-0">Total Item</h6></th><td>' + totalQuantity + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Subtotal</h6></th><td>Rp. ' + toCurrency(subtotal) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Kupon</h6></th><td>Rp. ' + toCurrency(discount) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Ongkir</h6></th><td>Rp. ' + toCurrency(0) + '</td></tr>' +
        '<tr><th colspan="7"><h6 class="text-right mb-0">Total</h6></th><td>Rp. ' + toCurrency(total) + '</td></tr>'
    );
}

$customers.on('change', function() {
    var data = $(this).find(':selected').data();
    var userId = $(this).val();

    $('input[name=origin_fullname]').val(data.fullname);
    $('input[name=origin_email]').val(data.email);
    $('input[name=origin_phone]').val(data.phone);
    $('textarea[name=origin_address]').val(data.address);
});

$coupons.on('change', function() {
    var couponId = $(this).val();

    if (!couponId) return;

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.coupons') }}?id=" + couponId,
        success: function(response) {
            coupon = response.data[0];

            if (coupon) {
                $('input[name=coupon_name]').val(coupon.name);
                $('input[name=coupon_code]').val(coupon.code);
            }

            updateItemsType();
            renderItems();
        },
        error: function() {
            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil data kupon',
                position: 'topRight'
            });
        }
    });
});

$products.on('change', function() {
    var productId = $(this).val();
    var product = items.find(item => item.productId == productId);

    if (productId == '') {
        return false;
    }

    $('#spinner-color').show();
    $('#color-inputs').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.colors') }}?product_id=" + productId + "&stock_type=shopee",
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
    var productName = $products.find('option:selected').text();

    $colorInputs.find('.col-md-3').each(function () {
        var colorId = $(this).find('input').data('id');
        var hexCode = $(this).find('input').data('code');
        var totalStock = $(this).find('input').data('stock');
        var quantity = $(this).find('input').val();
        var isQuantityValid = quantity > 0;

        if (!isQuantityValid) return;

        var product = items.find(item => item.productId == productId);

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

    productInfo = null;

    fetchCoupons();
    updateItemsType();
    renderItems();
    resetItemForm();
});

$itemWrapper.on('click', '.btn-delete', function() {
    var $row = $(this).parents('tr');
    var index = $row.index();

    items.splice(index, 1)
    fetchCoupons();
    updateItemsType();
    renderItems();
});

$itemWrapper.on('change', '.form-quantity', function() {
    var data = $(this).data();
    var quantity = Number($(this).val());

    items = items.map((item, index) => {
        if (item.productId == data.productId) {
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

    fetchCoupons();
    updateItemsType();
    renderItems();
});

(function() {
    items = items.map((item, itemIndex) => ({
        id: 'item-' + (Date.now() + itemIndex),
        productId: item.product_id,
        currentPrice: Number(item.current_price),
        priceUsed: Number(item.price_used),
        priceBuy: Number(item.price_buy),
        priceSellNormal: Number(item.price_sell_normal),
        priceSellReseller: Number(item.price_sell_reseller),
        priceSellSeri: Number(item.price_sell_seri),
        priceSellWholesaler50: Number(item.price_sell_wholesaler_50),
        priceSellWholesaler100: Number(item.price_sell_wholesaler_100),
        priceSellWholesaler200: Number(item.price_sell_wholesaler_200),
        priceSellWholesaler400: Number(item.price_sell_wholesaler_400),
        priceSellWholesaler600: Number(item.price_sell_wholesaler_600),
        weight: Number(item.weight),
        discount: Number(item.discount),
        type: item.type,
        productName: item.product_name,
        colors: item.colors.map((color, colorIndex) => ({
            id: 'color-' + (Date.now() + colorIndex),
            colorId: color.color_id,
            hexCode: color.hex_code,
            quantity: Number(color.quantity),
            subtotal: Number(color.subtotal),
        }))
    }));

    var couponId = "{{ old('coupon_id') }}";

    if (couponId) {
        $coupons.append(`
            <option value="{{ old('coupon_id') }}" selected>
                {{ old('coupon_name') }} - {{ old('coupon_code') }}
            </option>
        `);
    }

    $('select[name=payment_type]').change();
    $coupons.change();
    renderItems();
})();

</script>
@endsection
