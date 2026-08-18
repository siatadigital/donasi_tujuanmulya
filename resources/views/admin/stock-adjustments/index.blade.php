@extends('admin.master')

@section('title', 'RTL - Penyesuaian Stok')

@section('content')
<div class="section-header">
  <h1>Penyesuaian Stok</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Penyesuaian Stok</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Penyesuaian Stok</h2>
  <p class="section-lead">
    Daftar penyesuaian stok
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Penyesuaian Stok</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.stock-adjustments.create') }}" class="btn btn-primary">Tambah</a>
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
                    <th>Jenis Stok</th>
                    <th>Keuntungan / Kerugian</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
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
                        <strong>TIpe</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content type"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Keuntungan / Kerugian</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content total-profit"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Catatan</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content notes"></p>
                    </div>
                </div>
                <br><br>
                <table class="table table-bordered" id="table-detail">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Warna</th>
                            <th>Harga Beli</th>
                            <th>Stok Terkini</th>
                            <th>Aktual Stok</th>
                            <th>Penyesuaian</th>
                            <th>Keuntungan / Kerugian</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
    ajax: '{{ route("admin.datatables.stock-adjustments") }}',
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
        data: 'type',
        name: 'type'
      },
      {
        data: 'total_profit',
        name: 'total_profit'
      },
      {
        data: 'date',
        name: 'date'
      },
    ],
    columnDefs: [
        {
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
        },
    ]
  });

  $('table').on('click', '.btn-detail', function() {
    var itemId = $(this).data('item-id');

    $('.detail-content').text('');
    $('#table-detail tbody').empty();
    $('#table-print tbody').empty();
    $('#spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        url: "{{ route('admin.stock-adjustments.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#spinner').hide();
            $('.table-responsive').show();

            var item = response.data;
            var type = '';

            switch (item.type) {
                case 'warehouse': type = 'Gudang'; break;
                case 'store': type = 'Toko'; break;
                case 'shopee': type = 'Shopee'; break;
                default: break;
            }

            $('.detail-content.code').text(item.code);
            $('.detail-content.date').text(item.date);
            $('.detail-content.user').text(item.user.username);
            $('.detail-content.type').text(type);
            $('.detail-content.total-profit').text(`Rp ${toCurrency(item.total_profit)}`);
            $('.detail-content.notes').text(item.notes);

            item.details.forEach(function(detail, index) {
                $('#table-detail tbody').append(`
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
                        <td>Rp ${toCurrency(detail.price_buy)}</td>
                        <td>${toCurrency(detail.current_stock)}</td>
                        <td>${toCurrency(detail.actual_stock)}</td>
                        <td>${toCurrency(detail.adjustment_result)}</td>
                        <td>Rp ${toCurrency(detail.profit)}</td>
                    </tr>
                `);
            });

            $('#table-detail tbody').append(`
                <tr>
                    <td colspan="6"></td>
                    <th>Keuntungan / Kerugian</th>
                    <td>Rp ${toCurrency(item.total_profit)}</td>
                </tr>
            `);

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
