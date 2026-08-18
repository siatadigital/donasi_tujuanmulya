@extends('admin.master')

@section('title', 'RTL - Transfer Stok')

@section('content')
<div class="section-header">
  <h1>Transfer Stok</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Transfer Stok</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Transfer Stok</h2>
  <p class="section-lead">
    Daftar transfer stok
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Transfer Stok</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.transfer-stocks.create') }}" class="btn btn-primary">Tambah</a>
            <br><br>
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
                    <th>Kode</th>
                    <th>User</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>Tanggal</th>
                    <th width="140px">Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('modal')
<div class="modal fade" role="dialog" id="modal-detail">
    <div class="modal-dialog" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Kode</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content code"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Tanggal</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content date"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>User</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content user"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Dari</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content from-type"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Ke</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content to-type"></p>
                    </div>
                </div>
                <ul class="nav nav-tabs" id="tab" role="tablist">
                    <li class="nav-item">
                        <a
                            class="nav-link active"
                            id="all-tab"
                            href="#all-content"
                            data-toggle="tab"
                            role="tab"
                            aria-controls="all-content"
                            aria-selected="true"
                        >
                            Semua (<span id="tab-amount-all"></span>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            id="out-tab"
                            href="#out-content"
                            data-toggle="tab"
                            role="tab"
                            aria-controls="out-content"
                            aria-selected="true"
                        >
                            Keluar (<span id="tab-amount-out"></span>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            id="in-tab"
                            href="#in-content"
                            data-toggle="tab"
                            role="tab"
                            aria-controls="in-content"
                            aria-selected="true"
                        >
                            Masuk (<span id="tab-amount-in"></span>)
                        </a>
                    </li>
                </ul>
                <div class="tab-content" id="tab-content">
                    <div class="tab-pane fade show active" id="all-content" role="tabpanel" aria-labelledby="all-tab">
                        <table class="table table-bordered" id="table-detail-all">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Warna</th>
                                    <th>Kuantitas</th>
                                    <th>Transaksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="out-content" role="tabpanel" aria-labelledby="out-tab">
                        <table class="table table-bordered" id="table-detail-out">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Warna</th>
                                    <th>Kuantitas</th>
                                    <th>Transaksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade show" id="in-content" role="tabpanel" aria-labelledby="in-tab">
                        <table class="table table-bordered" id="table-detail-in">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Warna</th>
                                    <th>Kuantitas</th>
                                    <th>Transaksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@if (session()->has('message'))
<script>
  iziToast.success({
    title: 'Berhasil!',
    message: '{{ session("message") }}',
    position: 'topRight'
  });
</script>
@endif

<script>
  var table = $('#table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("admin.datatables.transfer-stocks") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'code',
        name: 'code'
      },
      {
        data: 'user',
        name: 'user.username'
      },
      {
        data: 'from_type',
        name: 'from_type'
      },
      {
        data: 'to_type',
        name: 'to_type'
      },
      {
        data: 'created_at',
        name: 'created_at'
      },
    ],
    columnDefs: [{
      targets: 6,
      render: function(data, type, row) {
        var show = `
            <button
                type="button"
                class="btn btn-info btn-detail"
                data-item-id="${row.id}"
            >
                Detail
            </button>
        `;

        return show;
      },
    }, ],
  });

  $('table').on('click', '.btn-detail', function() {
    var itemId = $(this).data('item-id');

    $('.detail-content').text('');
    $('#table-detail-all tbody').empty();
    $('#table-detail-out tbody').empty();
    $('#table-detail-in tbody').empty();
    $('#spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        url: "{{ route('admin.transfer-stocks.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#spinner').hide();
            $('.table-responsive').show();

            var item = response.data;
            var action = $('#modal-detail form').attr('action') + '/' + itemId;
            var fromType = '';
            var toType = '';

            switch (item.from_type) {
                case 'warehouse':
                    fromType = 'Gudang';
                    break;

                case 'store':
                    fromType = 'Toko';
                    break;

                case 'shopee':
                    fromType = 'Shopee';
                    break;

                default:
                    break;
            }

            switch (item.to_type) {
                case 'warehouse':
                    toType = 'Gudang';
                    break;

                case 'store':
                    toType = 'Toko';
                    break;

                case 'shopee':
                    toType = 'Shopee';
                    break;

                default:
                    break;
            }

            $('#modal-detail form').attr('action', action);

            $('.detail-content.code').text(item.code);
            $('.detail-content.date').text(item.created_at);
            $('.detail-content.user').text(item.user ? item.user.username : 'Tidak Ada');
            $('.detail-content.from-type').text(fromType);
            $('.detail-content.to-type').text(toType);

            item.details.forEach(function(detail, index) {
                var type = '';

                switch (detail.type) {
                    case 'warehouse':
                        type = 'Gudang';
                        break;

                    case 'store':
                        type = 'Toko';
                        break;

                    case 'shopee':
                        type = 'Shopee';
                        break;

                    default:
                        break;
                }

                var content = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${detail.product.title}</td>
                        <td>
                            <div
                                title="${detail.color.name}"
                                style="width:24px;height:24px;background:${detail.color.hex_code};"
                            >
                            </div>
                        </td>
                        <td>${detail.stock_out || detail.stock_in}</td>
                        <td>${!!detail.stock_out ? 'Keluar' : 'Masuk'} (${type})</td>
                    </tr>
                `;

                $('#table-detail-all tbody').append(content);
                $('#tab-amount-all').text(index + 1);
            });

            item.details
                .filter(function(detail, index) {
                    return !!detail.stock_out;
                }).forEach(function(detail, index) {
                    var type = '';

                    switch (detail.type) {
                        case 'warehouse':
                            type = 'Gudang';
                            break;

                        case 'store':
                            type = 'Toko';
                            break;

                        case 'shopee':
                            type = 'Shopee';
                            break;

                        default:
                            break;
                    }

                    var content = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${detail.product.title}</td>
                            <td>
                                <div
                                    title="${detail.color.name}"
                                    style="width:24px;height:24px;background:${detail.color.hex_code};"
                                >
                                </div>
                            </td>
                            <td>${detail.stock_out}</td>
                            <td>Keluar (${type})</td>
                        </tr>
                    `;

                    $('#table-detail-out tbody').append(content);
                    $('#tab-amount-out').text(index + 1);
                });

            item.details
                .filter(function(detail, index) {
                    return !!detail.stock_in;
                }).forEach(function(detail, index) {
                    var type = '';

                    switch (detail.type) {
                        case 'warehouse':
                            type = 'Gudang';
                            break;

                        case 'store':
                            type = 'Toko';
                            break;

                        case 'shopee':
                            type = 'Shopee';
                            break;

                        default:
                            break;
                    }

                    var content = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${detail.product.title}</td>
                            <td>
                                <div
                                    title="${detail.color.name}"
                                    style="width:24px;height:24px;background:${detail.color.hex_code};"
                                >
                                </div>
                            </td>
                            <td>${detail.stock_in}</td>
                            <td>Masuk (${type})</td>
                        </tr>
                    `;

                    $('#table-detail-in tbody').append(content);
                    $('#tab-amount-in').text(index + 1);
                });

            $('#modal-detail').modal({ show: true });
        },
        error: function() {
            $('#spinner').hide();
            $('.table-responsive').show();

            swal({
                icon: 'error',
                title: 'Gagal',
                text: 'Maaf, tidak dapat mengambil data !',
            });
        }
    });
  });
</script>
@endsection
