@extends('admin.master')

@section('title', 'RTL - Transfer Stok')

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
  <h1>Transfer Stok</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.offline-sales.index') }}">Transfer Stok</a></div>
    <div class="breadcrumb-item">Transfer Stok</div>
  </div>
</div>

<div class="section-body">
  <!-- <h2 class="section-title">Transfer Stok</h2>
  <p class="section-lead">
    Form untuk tambah penjualan
  </p> -->

  <form action="{{ route('admin.transfer-stocks.store') }}" method="POST">
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
                    @component('admin.components.form-input', [
                        'label' => 'Dari',
                        'type' => 'select',
                        'name' => 'from_type',
                        'options' => $stockTypes,
                        'value' => old('from_type'),
                        'error' => $errors->first('from_type'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Ke',
                        'type' => 'select',
                        'name' => 'to_type',
                        'options' => $allStockTypes,
                        'value' => old('to_type'),
                        'error' => $errors->first('to_type'),
                    ])
                    @endcomponent
                </div>
            </div>
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
                        <th>Kuantitas</th>
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
          <div class="card-footer text-right">
            <button class="btn btn-primary">Simpan</button>
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

var $products = $('select[name=product_id]');
var $btnAdd = $('#btn-add');
var $hiddenForm = $('#hidden-form');
var $itemWrapper = $('.table tbody');
var $colorInputs = $('#color-inputs');
var items = [];
var productInfo = null;
var fromType = $('select[name=from_type]').val();

var resetItemForm = function() {
    $products.val('').trigger('change');
    $colorInputs.empty();
    $colorInputs.hide();
}

var renderItems = function() {
    $itemWrapper.empty();
    $hiddenForm.empty();

    for (var [index, item] of items.entries()) {
        $itemWrapper.append(`
            <tr>
                <td><p>${item.productName}</p></td>
                <td><p><div style="width:24px;height:24px;background:${item.hexCode}"></div></p></td>
                <td><input type="number" class="form-control form-quantity" style="width:80px" name="quantities[]" value="${item.quantity}" /></td>
                <td><button type="button" class="btn btn-danger btn-delete">Hapus</button></td></tr>
            </tr>
        `);

        $hiddenForm.append(
            '<div class="hidden-item" data-id="' + item.id + '">' +
            '<input type="hidden" name="items['+ index +'][product_id]" value="' + item.productId + '" /> ' +
            '<input type="hidden" name="items['+ index +'][color_id]" value="' + item.colorId + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_buy]" value="' + item.priceBuy + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_normal]" value="' + item.priceSellNormal + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_reseller]" value="' + item.priceSellReseller + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_seri]" value="' + item.priceSellSeri + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_wholesaler_50]" value="' + item.priceSellWholesaler50 + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_wholesaler_100]" value="' + item.priceSellWholesaler100 + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_wholesaler_200]" value="' + item.priceSellWholesaler200 + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_wholesaler_400]" value="' + item.priceSellWholesaler400 + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_sell_wholesaler_600]" value="' + item.priceSellWholesaler600 + '" /> ' +
            '<input type="hidden" name="items['+ index +'][quantity]" value="' + item.quantity + '" /> ' +
            '</div>'
        );
    }
}

$products.on('change', function() {
    var productId = $(this).val();
    var fromType = $('select[name=from_type]').val();

    if (productId == '') {
        return false;
    }

    $('#spinner-color').show();
    $('#color-inputs').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.colors') }}?product_id=" + productId + "&stock_type=" + fromType,
        success: function(response) {
            $('#spinner-color').hide();
            $('#color-inputs').show();

            var colors = response.data.slice(1);

            $colorInputs.empty();

            for (const color of colors) {
                var item = items.find(item => item.productId == productId && item.colorId == color.id);
                var itemQuantity =  item ? item.quantity : 0;
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
                productName: product.title,
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
    var item = items.find(item => item.productId == productId && item.colorId == colorId);
    var itemQuantity = item ? item.quantity : 0;

    var stock = quantity <= totalStock - itemQuantity ? totalStock - itemQuantity - quantity : 0;

    $(this).parent().find('.total-stock').text(stock);
});

$btnAdd.on('click', function() {
    var productId = $products.val();

    $colorInputs.find('.col-md-3').each(function () {
        var colorId = $(this).find('input').data('id');
        var hexCode = $(this).find('input').data('code');
        var totalStock = $(this).find('input').data('stock');
        var quantity = $(this).find('input').val();
        var item = items.find(item => item.productId == productId && item.colorId == colorId);
        var isQuantityValid = quantity > 0;

        if (!isQuantityValid) return;

        if (item) {
            var hasStock = separatedToNumber(quantity) <= (totalStock - item.quantity);

            if (!hasStock) return;

            items = items.map(item => {
                if (item.productId == productId && item.colorId == colorId) {
                    var newQuantity = parseInt(item.quantity) + separatedToNumber(quantity);

                    return Object.assign({
                        colorId: separatedToNumber(colorId),
                        quantity: newQuantity,
                        hexCode: hexCode
                    }, productInfo);
                }

                return item;
            });
        } else {
            var hasStock = separatedToNumber(quantity) <= totalStock;

            if (!hasStock) return;

            var newItem = Object.assign({
                id: Date.now(),
                colorId: separatedToNumber(colorId),
                quantity: separatedToNumber(quantity),
                hexCode: hexCode
            }, productInfo);

            items.push(newItem);
        }
    });

    productInfo = null;

    renderItems();
    resetItemForm();
});

$itemWrapper.on('click', '.btn-delete', function() {
    var $row = $(this).parents('tr');
    var index = $row.index();

    items.splice(index, 1);
    renderItems();
});

$itemWrapper.on('change', '.form-quantity', function() {
    var $row = $(this).parents('tr');
    var quantity = $(this).val();
    var rowIndex = $row.index();

    items = items.map((item, index) => {
        if (rowIndex == index) {
            return Object.assign(item, {
                quantity: quantity,
            });
        }

        return item;
    });

    renderItems();
});

$('select[name=from_type]').on('change', function() {
    var newFromType = $(this).val();
    var hasItems = items.length > 0;
    var isDifferentType = newFromType !== fromType;

    if (hasItems) {
        if (isDifferentType) {
            iziToast.error({
                title: 'Gagal!',
                message: 'Maaf, Tidak boleh memasukan item dari jenis stok yang berbeda !',
                position: 'topRight'
            });

            $(this).val(fromType);
        }
    } else {
        fromType = newFromType;
    }
});
</script>
@endsection
