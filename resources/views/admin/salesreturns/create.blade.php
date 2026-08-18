@extends('admin.master')

@section('title', 'RTL - Tambah Pengembalian')

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
  <h1>Tambah Pengembalian</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.salesreturns.index') }}">Pengembalian</a></div>
    <div class="breadcrumb-item">Tambah Pengembalian</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Pengembalian</h2>
  <p class="section-lead">
    Form untuk tambah pengembalian
  </p>

  <form action="{{ route('admin.salesreturns.store') }}" method="POST" enctype="multipart/form-data">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Pengembalian</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @component('admin.components.form-input', [
                        'label' => 'Customer (Opsional)',
                        'type' => 'select',
                        'name' => 'user_customer_id',
                        'options' => $customers,
                        'value' => old('user_customer_id'),
                        'error' => $errors->first('user_customer_id'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div id="input-sales">
                    @component('admin.components.form-input', [
                        'label' => 'Penjualan',
                        'type' => 'select',
                        'name' => 'sales_id',
                        'required' => TRUE,
                        'options' => ['' => 'Pilih Penjualan'],
                        'value' => old('sales_id'),
                        'error' => $errors->first('sales_id'),
                    ])
                    @endcomponent
                    </div>
                    <div id="spinner-sales" style="display:none;margin-top:16px;">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="width:32px;height:32px;">
                        </div>
                    </div>
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
            @component('admin.components.form-input', [
                'label' => 'Foto Bukti (maks. 5)',
                'type' => 'images',
                'name' => 'photos',
                'required' => TRUE,
                'error' => $errors->first('photos'),
            ])
            @endcomponent
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Produk</h4>
          </div>
          <div class="card-body">
            <div id="spinner-items" style="display:none;">
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading...">
                </div>
            </div>
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
<script>
var $customers = $('select[name=user_customer_id]');
var $sales = $('select[name=sales_id]');
var $hiddenForm = $('#hidden-form');
var $itemWrapper = $('.table tbody');
var items = [];
var sales = [];
var coupon = null;

var renderItems = function() {
    var subtotal = items.reduce(function(acc, item) {
        return acc + _.sumBy(item.colors, 'subtotal');
    }, 0);

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
                                min="0"
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
              ${item.colors.map((color, colorIndex) => `
                <input type="hidden" name="items[${index}][colors][${colorIndex}][color_id]" value="${color.colorId}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][quantity]" value="${color.quantity}" />
                <input type="hidden" name="items[${index}][colors][${colorIndex}][subtotal]" value="${color.subtotal}" />
              `)}
            </div>
        `);
    }

    $itemWrapper.append(
        '<tr><th colspan="7"><h6 class="text-right mb-0">Total</h6></th><td>Rp. ' + toCurrency(subtotal) + '</td></tr>'
    );
}

$customers.on('change', function() {
    var userId = $(this).val();

    $('#spinner-sales').show();
    $('#input-sales').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.sales') }}?user_customer_id=" + userId,
        success: function(response) {
            $('#spinner-sales').hide();
            $('#input-sales').show();

            sales = response.data;

            $sales.empty();
            $sales.append('<option value="">Pilih Penjualan</option>');

            for (const item of sales) {
                $sales.append('<option value="' + item.id + '">' + item.code + '</option>');
            }

            renderItems();
        },
        error: function() {
            $('#spinner-sales').hide();
            $('#input-sales').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar penjualan',
                position: 'topRight'
            });
        }
    });
});

$sales.on('change', function() {
    var salesId = $(this).val();

    var salesItem = sales.find(function(item) {
        return item.id == salesId;
    });

    coupon = salesItem.coupon_id && {
        id: salesItem.coupon_id,
        name: salesItem.coupon_name,
        code: salesItem.coupon_code,
        discount_percent: salesItem.coupon_discount_percent,
    };

    $('#spinner-items').show();
    $('.table-bordered').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.sales-details') }}?sales_id=" + salesId,
        success: function(response) {
            $('#spinner-items').hide();
            $('.table-bordered').show();

            items = response.data;

            renderItems();
        },
        error: function() {
            $('#spinner-items').hide();
            $('.table-bordered').show();

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar produk penjualan',
                position: 'topRight'
            });
        }
    });
});

$itemWrapper.on('click', '.btn-delete', function() {
    var $row = $(this).parents('tr');
    var index = $row.index();

    items.splice(index, 1);
    renderItems();
});

$itemWrapper.on('change', '.form-quantity', function() {
    var data = $(this).data();
    var quantity = Number($(this).val());
    var $row = $(this).parents('tr');
    var rowIndex = $row.index();

    items = items.map((item, index) => {
        if (item.productId == data.productId && rowIndex == index) {
            item.colors = item.colors.map(color => {
                if (color.colorId == data.colorId) {
                    if (quantity > color.maxQuantity) {
                        return color;
                    }

                    color.quantity = quantity;
                    color.subtotal = item.priceUsed * quantity;
                }

                return color;
            });
        }

        return item;
    });

    renderItems();
});

$customers.change();
</script>
@endsection
