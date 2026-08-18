@extends('admin.master')

@section('title', 'RTL - Penerimaan')

@section('content')
<div class="section-header">
  <h1>Penerimaan</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Penerimaan</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Penerimaan</h2>
  <p class="section-lead">
    Daftar penerimaan
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Penerimaan</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.receivings.create') }}" class="btn btn-primary">Tambah</a>
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
                    <th>Supplier</th>
                    <th>Tipe</th>
                    <th>Transfer</th>
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
                        <strong>Supplier</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content supplier"></p>
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
                        <strong>Total Harga</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content total-price"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Total Berat</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content total-weight"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Transfer</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content transfer"></p>
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
                            <th>Berat (g)</th>
                            <th>Kuantitas</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <hr>
                <table class="table table-bordered" id="table-print">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pencetak</th>
                            <th>Salinan</th>
                            <th>Tanggal</th>
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
    ajax: '{{ route("admin.datatables.receivings") }}',
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
        data: 'supplier',
        name: 'supplier.name'
      },
      {
        data: 'type',
        name: 'type'
      },
      {
        data: 'is_paid',
        name: 'is_paid'
      },
      {
        data: 'date',
        name: 'date'
      },
    ],
    columnDefs: [
        {
            targets: 5,
            render: function(data, type, row) {
                var statusSelection = `
                    <form action="{{ route('admin.receivings.status.update', ['id' => '']) }}/${row.id}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <select name="is_paid" class="form-control" style="width:128px">
                            <option value="0">Belum</option>
                            <option value="1">Sudah</option>
                        </select>
                    </form>
                `;

                return statusSelection;
            },
        },
        {
            targets: 7,
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

                var print = '<a target="_blank" href="{{ route("admin.receivings.print", ["id" => "ID_HERE"]) }}" class="btn btn-success">Print</a>';

                print = print.replace(/ID_HERE/g, row.id);

                var isPrintPermitted = Number("{{ (int) isPermitted('admin.receivings.print') }}");
                var buttons = show;

                if (isPrintPermitted) {
                    buttons += '&nbsp;&nbsp;' + print;
                }

                return buttons;
            },
        },
    ],
    rowCallback: function(row, data) {
        $('td:eq(5)', row).find('select').val(data.is_paid);
    }
  });

  $('table').on('click', '.btn-detail', function() {
    var itemId = $(this).data('item-id');

    $('.detail-content').text('');
    $('#table-detail tbody').empty();
    $('#table-print tbody').empty();
    $('#spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        url: "{{ route('admin.receivings.show', ['id' => '']) }}/" + itemId,
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
            $('.detail-content.supplier').text(item.supplier.name);
            $('.detail-content.transfer').text(item.is_paid ? 'Sudah' : 'Belum');
            $('.detail-content.type').text(type);
            $('.detail-content.total-price').text(`Rp ${toCurrency(item.total_price)}`);
            $('.detail-content.total-weight').text(`${toCurrency(item.total_weight)}g`);
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
                        <td>${toCurrency(detail.weight)}g</td>
                        <td>${toCurrency(detail.quantity)}</td>
                        <td>Rp ${toCurrency(detail.price_buy * detail.quantity)}</td>
                    </tr>
                `);
            });

            $('#table-detail tbody').append(`
                <tr>
                    <td colspan="5"></td>
                    <th>Total Harga</th>
                    <td>Rp ${toCurrency(item.total_price)}</td>
                </tr>
            `);

            item.prints.forEach(function(print, index) {
                $('#table-print tbody').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${print.user.username}</td>
                        <td>${print.is_copy ? 'Ya' : 'Tidak'}</td>
                        <td>${print.created_at}</td>
                    </tr>
                `);
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

  $('table').on('change', 'select', function() {
    var url = $(this).parent().attr('action');
    var token = '{{ csrf_token() }}';
    var value = Number($(this).val());

    $('#spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        method: "PUT",
        url: url,
        data: {
            _token: token,
            is_paid: value
        },
        success: function(response) {
            iziToast.success({
                title: 'Berhasil!',
                message: 'Berhasil mengubah status transfer kulakan',
                position: 'topRight'
            });

            $('#spinner').hide();
            $('.table-responsive').show();
        },
        error: function(e) {
            iziToast.error({
                title: 'Gagal!',
                message: 'Gagal mengubah status transfer kulakan',
                position: 'topRight'
            });

            $('#spinner').hide();
            $('.table-responsive').show();
        }
    });
  });
</script>
@endsection
