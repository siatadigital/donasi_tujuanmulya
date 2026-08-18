@extends('admin.master')

@section('title', 'RTL - Ubah Kupon')

@section('content')
<div class="section-header">
  <h1>Ubah Kupon</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.coupons.index') }}">Kupon</a></div>
    <div class="breadcrumb-item">Ubah Kupon</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ubah Kupon</h2>
  <p class="section-lead">
    Form untuk ubah kupon
  </p>

  <form action="{{ route('admin.coupons.update', ['id' => $coupon->id]) }}" method="POST">
    <div class="card">
        <div class="card-header">
            <h4>Ubah Kupon</h4>
        </div>
        <div class="card-body">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT" />

            @component('admin.components.form-input', [
                'label' => 'Nama',
                'type' => 'text',
                'name' => 'name',
                'required' => TRUE,
                'value' => $coupon->name,
                'error' => $errors->first('name'),
            ])
            @endcomponent
            <div class="row">
                <div class="col-md-4">
                    @component('admin.components.form-input', [
                        'label' => 'Kode',
                        'type' => 'text',
                        'name' => 'code',
                        'required' => TRUE,
                        'value' => $coupon->code,
                        'error' => $errors->first('code'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-3">
                    @component('admin.components.form-input', [
                        'label' => 'Diskon',
                        'type' => 'number',
                        'name' => 'discount',
                        'required' => TRUE,
                        'value' => $coupon->discount,
                        'error' => $errors->first('discount'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-3">
                    @component('admin.components.form-input', [
                        'label' => 'Jenis',
                        'type' => 'select',
                        'name' => 'type',
                        'options' => [
                            'percent' => 'Persen (%)',
                            'amount' => 'Nominal (Rp)',
                        ],
                        'value' => $coupon->type,
                        'error' => $errors->first('type'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-2">
                    @component('admin.components.form-input', [
                        'label' => 'Minimal Pcs',
                        'type' => 'number',
                        'name' => 'min_pcs',
                        'required' => TRUE,
                        'value' => $coupon->min_pcs,
                        'error' => $errors->first('min_pcs'),
                        'additional' => [
                            'min' => 0,
                        ],
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Tanggal Kadaluarsa',
                        'type' => 'text',
                        'name' => 'expired_at',
                        'required' => TRUE,
                        'class' => 'datetimepicker',
                        'value' => $coupon->expired_at,
                        'error' => $errors->first('expired_at'),
                    ])
                    @endcomponent
                </div>
                <div class="col-md-6">
                    @component('admin.components.form-input', [
                        'label' => 'Aktif',
                        'type' => 'select',
                        'name' => 'is_active',
                        'options' => [
                            0 => 'Tidak',
                            1 => 'Ya',
                        ],
                        'value' => $coupon->is_active,
                        'error' => $errors->first('is_active'),
                    ])
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Produk</h4>
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
                        'label' => 'Min Pcs',
                        'type' => 'number',
                        'name' => 'product_min_pcs',
                        'value' => 1,
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="text-right">
                <button type="button" id="btn-add" class="btn btn-primary">Tambahkan</button>
            </div>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Min Pcs</th>
                        <th width="96px">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <div style="display:none;" id="hidden-form"></div>
            <div class="card-footer text-right">
                <button class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
  </form>
</div>
@endsection

@section('js')
<script>
var $products = $('select[name=product_id]');
var $productMinPcs = $('input[name=product_min_pcs]');
var $btnAdd = $('#btn-add');
var $hiddenForm = $('#hidden-form');
var $itemWrapper = $('.table tbody');
var items = JSON.parse(`{!! $coupon->couponProducts->toJson() !!}`);

var resetItemForm = function() {
    $products.val('').trigger('change');
    $productMinPcs.val(1).trigger('change');
}

var renderItems = function() {
    $itemWrapper.empty();
    $hiddenForm.empty();

    for (var [index, item] of items.entries()) {
        $itemWrapper.append(`
            <tr>
                <td>
                    <p>${item.productName}</p>
                </td>
                <td>
                    <input
                        type="number"
                        class="form-control form-quantity"
                        style="width:80px"
                        name="quantities[]"
                        value="${item.minPcs}"
                        min="1"
                        data-product-id="${item.productId}"
                    />
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-delete">Hapus</button>
                </td>
            </tr>
        `);

        $hiddenForm.append(`
            <div class="hidden-item" data-id="${item.id}">
              <input type="hidden" name="items[${index}][product_id]" value="${item.productId}" />
              <input type="hidden" name="items[${index}][product_name]" value="${item.productName}" />
              <input type="hidden" name="items[${index}][min_pcs]" value="${item.minPcs}" />
            </div>
        `);
    }
}

$btnAdd.on('click', function() {
    var productId = $products.val();
    var productName = $products.find(':selected').text();
    var minPcs = $productMinPcs.val();
    var hasProduct = items.find(item => item.productId == productId);

    if (!productId) return;

    if (hasProduct) {
        items = items.map(item => {
            if (item.productId == productId) {
                item.minPcs = minPcs;
            }

            return item;
        });
    } else {
        items.push({
            id: Date.now(),
            productId: productId,
            productName: productName,
            minPcs: minPcs,
        });
    }

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
    var data = $(this).data();
    var minPcs = Number($(this).val());

    items = items.map((item, index) => {
        if (item.productId == data.productId) {
            item.minPcs = minPcs;
        }

        return item;
    });

    renderItems();
});

(function() {
    items = items.map((item, itemIndex) => ({
        id: Date.now() + itemIndex,
        productId: item.product_id,
        productName: item.product.title,
        minPcs: item.min_pcs,
    }));

    renderItems();
})();
</script>
@endsection
