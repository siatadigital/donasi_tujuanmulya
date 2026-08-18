@extends('admin.master')

@section('title', 'RTL - Stok Realtime')

@section('content')
<div class="section-header">
  <h1>Stok Realtime</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Stok Realtime</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Stok Realtime</h2>
  <p class="section-lead">
    Daftar stok realtime
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Stok Realtime</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
                @component('admin.components.form-input', [
                    'label' => 'Pencarian',
                    'type' => 'text',
                    'name' => 'keyword',
                    'value' => '',
                    'class' => 'form-control',
                    'additional' => [
                        'id' => 'search-box',
                    ],
                ])
                @endcomponent
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="btn-search" style="margin-top:29px;">Cari</button>
            </div>
          </div>
          <br>
          <div id="spinner" style="display:none;">
            <div class="d-flex justify-content-center">
              <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="margin:48px;">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped" id="table">
              <thead>
                <tr>
                  <th class="text-center" width="32px">
                    #
                  </th>
                  <th>Nama Produk</th>
                  <th>Total Stok Gudang</th>
                  <th>Total Stok Toko</th>
                  <th>Total Stok Shopee</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
var $itemWrapper = $('.table tbody');
var $searchBox = $('#search-box');
var $btnSearch = $('#btn-search');
var interval = 5000; // 5 secs
var products = [];
var keyword = "";

var renderItems = function() {
    var hasProducts = !!products.length;
    var rows = [];
    var limitProductStock = {{ $limitProductStock }};

    var getStockStyle = function (stock) {
        var isSoldOut = stock <= 0;
        var isNearlySoldOut = stock <= limitProductStock;
        var style = '';

        if (isNearlySoldOut) {
            style = 'background:#ebe834;color:#777;';
        }

        if (isSoldOut) {
            style = 'background:#ffcac9;color:#777;';
        }

        return style;
    };

    for (var [index, product] of products.entries()) {
        var warehouseStyle = getStockStyle(product.current_total_stock_warehouse);
        var storeStyle = getStockStyle(product.current_total_stock_store);
        var shopeeStyle = getStockStyle(product.current_total_stock_shopee);

        var row = [
            '<tr>',
            '<td>' + (index + 1) + '</td>',
            '<td><a href="{{ route('admin.products.show', ['id' => '']) }}/' + product.id + '">' + product.title + '</a></td>',
            '<td style="' + warehouseStyle + '">' + toCurrency(product.current_total_stock_warehouse) + '</td>',
            '<td style="' + storeStyle + '">' + toCurrency(product.current_total_stock_store) + '</td>',
            '<td style="' + shopeeStyle + '">' + toCurrency(product.current_total_stock_shopee) + '</td>',
            '</tr>',
        ].join('');

        rows.push(row);
    }

    $itemWrapper.empty();

    if (!hasProducts) {
        $itemWrapper.append('<tr><td colspan="5"><p class="text-center mb-0">Tidak ada data.</p></td></tr>');
        return;
    }

    $itemWrapper.append(rows);
}

var fetchProducts = function(isWithLoading) {
    if (isWithLoading) {
        $('#spinner').show();
        $('.table-responsive').hide();
    }

    $.ajax({
        method: "GET",
        url: "{{ route('admin.api.products') }}?sort=total-stocks:desc&name=" + keyword,
        success: function(response) {
            if (isWithLoading) {
                $('#spinner').hide();
                $('.table-responsive').show();
            }

            products = response.data;

            renderItems();
        },
        error: function() {
            if (isWithLoading) {
                $('#spinner').hide();
                $('.table-responsive').show();
            }

            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengambil daftar produk',
                position: 'topRight'
            });
        }
    });
}

fetchProducts(true);
setInterval(fetchProducts, interval);

$searchBox.on('change', function() {
    keyword = $(this).val();
});

$btnSearch.on('click', function() {
    fetchProducts(true);
});
</script>
@endsection
