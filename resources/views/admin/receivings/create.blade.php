@extends('admin.master')

@section('title', 'RTL - Tambah Penerimaan')

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
  <h1>Tambah Penerimaan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.receivings.index') }}">Penerimaan</a></div>
    <div class="breadcrumb-item">Tambah Penerimaan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Penerimaan</h2>
  <p class="section-lead">
    Form untuk tambah penerimaan
  </p>

  <form action="{{ route('admin.receivings.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Penerimaan</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @component('admin.components.form-input', [
                        'label' => 'Supplier',
                        'type' => 'select',
                        'name' => 'supplier_id',
                        'required' => TRUE,
                        'options' => $suppliers,
                        'value' => old('supplier_id'),
                        'error' => $errors->first('supplier_id'),
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
                    @component('admin.components.form-input', [
                        'label' => 'Jenis Stok',
                        'type' => 'select',
                        'name' => 'type',
                        'required' => TRUE,
                        'options' => $stockTypes,
                        'value' => old('type'),
                        'error' => $errors->first('type'),
                    ])
                    @endcomponent
                </div>
            </div>
            @component('admin.components.form-input', [
                'label' => 'Catatan',
                'type' => 'textarea',
                'name' => 'notes',
                'value' => old('notes'),
                'error' => $errors->first('notes'),
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
            <hr style="margin:8px 0px;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Warna</th>
                        <th>Harga Beli</th>
                        <th>Berat (g)</th>
                        <th>Kuantitas</th>
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
var cleaveOptions = { numeral: true };

var $products = $('select[name=product_id]');
var $btnAdd = $('#btn-add');
var $hiddenForm = $('#hidden-form');
var $itemWrapper = $('.table tbody');
var $colorInputs = $('#color-inputs');
var items = [];
var productInfo = null;

var resetItemForm = function() {
    $products.val('').trigger('change');
    $colorInputs.empty();
    $colorInputs.hide();
}

var renderItems = function() {
    var total = items.reduce(function(acc, item) {
        return acc + item.subtotal;
    }, 0);

    $itemWrapper.empty();
    $hiddenForm.empty();

    for (var [index, item] of items.entries()) {
        $itemWrapper.append(
            '<tr><td><p>' +
            item.productName +
            '</p></td><td><div style="width:24px;height:24px;background:' +
            item.hexCode +
            '"></div></td><td>Rp. ' +
            toCurrency(item.priceBuy) +
            '</td><td>' +
            toCurrency(item.weight) + 'g' +
            '</td><td><input type="number" class="form-control form-quantity" style="width:80px" name="quantities[]" value="' +
            item.quantity +
            '" min="1" />' +
            '<td>Rp. ' + toCurrency(item.subtotal) + '</td>' +
            '</td><td><button type="button" class="btn btn-danger btn-delete">Hapus</button></td></tr>'
        );

        $hiddenForm.append(
            '<div class="hidden-item" data-id="' + item.id + '">' +
            '<input type="hidden" name="items['+ index +'][product_id]" value="' + item.productId + '" /> ' +
            '<input type="hidden" name="items['+ index +'][color_id]" value="' + item.colorId + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_buy]" value="' + item.priceBuy + '" /> ' +
            '<input type="hidden" name="items['+ index +'][weight]" value="' + item.weight + '" /> ' +
            '<input type="hidden" name="items['+ index +'][quantity]" value="' + item.quantity + '" /> ' +
            '</div>'
        );
    }

    $itemWrapper.append(
        '<tr><th colspan="6"><h6 class="text-right mb-0">Total</h6></th><td>Rp. ' + toCurrency(total) + '</td></tr>'
    );
}

$products.on('change', function() {
    var productId = $(this).val();

    if (productId == '') {
        return false;
    }

    $('#spinner-color').show();
    $('#color-inputs').hide();

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.colors') }}?product_id=" + productId,
        success: function(response) {
            $('#spinner-color').hide();
            $('#color-inputs').show();

            var colors = response.data.slice(1);

            $colorInputs.empty();

            for (const color of colors) {
                $colorInputs.append(`
                    <div class="col-md-3">
                        <div class="d-flex">
                            <div class="color-item" style="background:${color.hex_code}"></div>
                            <p style="margin-right:8px;">${color.text}</p>
                        </div>
                        <input
                            type="number"
                            name="input_quantities[]"
                            class="form-control"
                            min="0"
                            value="0"
                            data-id="${color.id}"
                            data-code="${color.hex_code}"
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

$btnAdd.on('click', function() {
    var productId = $products.val();
    var productName = $products.find('option:selected').text();

    $colorInputs.find('.col-md-3').each(function () {
        var colorId = $(this).find('input').data('id');
        var hexCode = $(this).find('input').data('code');
        var quantity = $(this).find('input').val();
        var isQuantityValid = quantity > 0;

        if (!isQuantityValid) return;

        var hasThisItem = !!items.find(item => item.productId == productId && item.colorId == colorId);

        if (hasThisItem) {
            items = items.map(item => {
                if (item.productId == productId && item.colorId == colorId) {
                    var newQuantity = parseInt(item.quantity) + separatedToNumber(quantity);
                    var subtotal = newQuantity * item.priceBuy;

                    return Object.assign(item, {
                        quantity: newQuantity,
                        subtotal: subtotal,
                    });
                }

                return item;
            });
        } else {
            var subtotal = separatedToNumber(quantity) * productInfo.priceBuy;

            var newItem = Object.assign({
                id: Date.now(),
                colorId: separatedToNumber(colorId),
                quantity: separatedToNumber(quantity),
                subtotal: subtotal,
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
            var subtotal = quantity * item.priceBuy;

            return Object.assign(item, {
                quantity: quantity,
                subtotal: subtotal
            });
        }

        return item;
    });

    renderItems();
});
</script>
@endsection
