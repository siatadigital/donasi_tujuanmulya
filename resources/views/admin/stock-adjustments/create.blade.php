@extends('admin.master')

@section('title', 'RTL - Tambah Penyesuaian Stok')

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
  <h1>Tambah Penyesuaian Stok</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.stock-adjustments.index') }}">Penyesuaian Stok</a></div>
    <div class="breadcrumb-item">Tambah Penyesuaian Stok</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Tambah Penyesuaian Stok</h2>
  <p class="section-lead">
    Form untuk tambah penyesuaian stok
  </p>

  <form action="{{ route('admin.stock-adjustments.store') }}" method="POST">
    <div class="row">
      {{ csrf_field() }}
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Tambah Penyesuaian Stok</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-6">
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
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Warna</th>
                        <th>Stok Terkini</th>
                        <th>Aktual Stok</th>
                        <th>Penyesuaian</th>
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
var stockType = $('select[name=type]').val();

var resetItemForm = function() {
    $products.val('').trigger('change');
    $colorInputs.empty();
    $colorInputs.hide();
}

var renderItems = function() {
    var total = items.reduce(function(acc, item) {
        return acc + item.adjustmentResult;
    }, 0);

    $itemWrapper.empty();
    $hiddenForm.empty();

    for (var [index, item] of items.entries()) {
        $itemWrapper.append(
            `
                <tr>
                    <td>${item.productName}</td>
                    <td><div style="width:24px;height:24px;background:${item.hexCode}"></div></td>
                    <td>${item.currentStock}</td>
                    <td><input type="number" class="form-control form-actual-stock" style="width:80px" name="actual_stocks[]" value="${item.actualStock}" min="0" /></td>
                    <td>${item.adjustmentResult}</td>
                    <td><button type="button" class="btn btn-danger btn-delete">Hapus</button></td>
                </tr>
            `
        );

        $hiddenForm.append(
            '<div class="hidden-item" data-id="' + item.id + '">' +
            '<input type="hidden" name="items['+ index +'][product_id]" value="' + item.productId + '" /> ' +
            '<input type="hidden" name="items['+ index +'][color_id]" value="' + item.colorId + '" /> ' +
            '<input type="hidden" name="items['+ index +'][price_buy]" value="' + item.priceBuy + '" /> ' +
            '<input type="hidden" name="items['+ index +'][current_stock]" value="' + item.currentStock + '" /> ' +
            '<input type="hidden" name="items['+ index +'][actual_stock]" value="' + item.actualStock + '" /> ' +
            '<input type="hidden" name="items['+ index +'][adjustment_result]" value="' + item.adjustmentResult + '" /> ' +
            '<input type="hidden" name="items['+ index +'][profit]" value="' + item.priceBuy * item.adjustmentResult + '" /> ' +
            '</div>'
        );
    }

    $itemWrapper.append(
        '<tr><th colspan="5"><h6 class="text-right mb-0">Total</h6></th><td>' + toCurrency(total) + '</td></tr>'
    );
}

$products.on('change', function() {
    var productId = $(this).val();
    var stockType = $('select[name=type]').val();

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
                $colorInputs.append(`
                    <div class="col-md-3">
                        <div class="d-flex">
                            <div class="color-item" style="background:${color.hex_code}"></div>
                            <p style="margin-right:8px;">${color.text} (${color.total_stock})</p>
                        </div>
                        <input
                            type="number"
                            name="input_quantities[]"
                            class="form-control"
                            min="0"
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
                productName: product.title,
                priceBuy: product.price_buy,
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
        var currentStock = $(this).find('input').data('stock');
        var actualStock = $(this).find('input').val();
        var item = items.find(item => item.productId == productId && item.colorId == colorId);
        var isQuantityValid = actualStock > 0;

        if (!isQuantityValid) return;

        if (item) {
            items = items.map(item => {
                if (item.productId == productId && item.colorId == colorId) {
                    var newActualStock = parseInt(item.actualStock) + separatedToNumber(actualStock);
                    var adjustmentResult = newActualStock - item.currentStock;

                    return Object.assign(item, {
                        actualStock: newActualStock,
                        adjustmentResult: adjustmentResult,
                    });
                }

                return item;
            });
        } else {
            var adjustmentResult = separatedToNumber(actualStock) - separatedToNumber(currentStock);

            var newItem = Object.assign({
                id: Date.now(),
                colorId: separatedToNumber(colorId),
                currentStock: separatedToNumber(currentStock),
                actualStock: separatedToNumber(actualStock),
                adjustmentResult: adjustmentResult,
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

$itemWrapper.on('change', '.form-actual-stock', function() {
    var $row = $(this).parents('tr');
    var actualStock = $(this).val();
    var rowIndex = $row.index();

    items = items.map((item, index) => {
        if (rowIndex == index) {
            var adjustmentResult = actualStock - item.currentStock;

            return Object.assign(item, {
                actualStock: actualStock,
                adjustmentResult: adjustmentResult
            });
        }

        return item;
    });

    renderItems();
});

$('select[name=type]').on('change', function() {
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
</script>
@endsection
