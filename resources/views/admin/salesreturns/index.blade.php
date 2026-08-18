@extends('admin.master')

@section('title', 'RTL - Pengembalian')

@section('content')
<div class="section-header">
  <h1>Pengembalian</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Pengembalian</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Pengembalian</h2>
  <p class="section-lead">
    Daftar pengembalian
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Pengembalian</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.salesreturns.create') }}" class="btn btn-primary">Tambah</a>
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
                    <th>Customer</th>
                    <th>Serahkan</th>
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
                        <strong>Customer</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content customer"></p>
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
                        <strong>Serahkan</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content hand-over"></p>
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
                            <th>Harga Jual</th>
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
    ajax: '{{ route("admin.datatables.salesreturns") }}',
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
        name: 'user'
      },
      {
        data: 'customer',
        name: 'customer'
      },
      {
        data: 'is_hand_over',
        name: 'is_hand_over'
      },
      {
        data: 'date',
        name: 'date'
      },
    ],
    columnDefs: [
        {
            targets: 4,
            render: function(data, type, row) {
                var statusSelection = `
                    <form action="{{ route('admin.salesreturns.status.update', ['id' => '']) }}/${row.id}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <select name="is_hand_over" class="form-control" style="width:128px">
                            <option value="0">Belum</option>
                            <option value="1">Sudah</option>
                        </select>
                    </form>
                `;

                return statusSelection;
            },
        },
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

                var print = '<a target="_blank" href="{{ route("admin.salesreturns.print", ["id" => "ID_HERE"]) }}" class="btn btn-success">Print</a>';

                print = print.replace(/ID_HERE/g, row.id);

                var isPrintPermitted = Number("{{ (int) isPermitted('admin.salesreturns.print') }}");
                var buttons = show;

                if (isPrintPermitted) {
                    buttons += '&nbsp;&nbsp;' + print;
                }

                return buttons;
            },
        },
    ],
    rowCallback: function(row, data) {
        $('td:eq(4)', row).find('select').val(data.is_hand_over);
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
        url: "{{ route('admin.salesreturns.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var item = response.data;
            var detailsByIndex = _.groupBy(item.details, 'item_index');

            $('#spinner').hide();
            $('.table-responsive').show();
            $('.detail-content.code').text(item.code);
            $('.detail-content.date').text(item.date);
            $('.detail-content.user').text(item.user ? item.user.username : 'Tidak Ada');
            $('.detail-content.customer').text(item.customer ? item.customer.user.username : 'Tidak Ada');
            $('.detail-content.total-price').text(`Rp ${toCurrency(item.total_price)}`);
            $('.detail-content.total-weight').text(`${toCurrency(item.total_weight)}g`);
            $('.detail-content.hand-over').text(item.is_hand_over ? 'Sudah' : 'Belum');
            $('.detail-content.notes').text(item.notes);

            Object.values(detailsByIndex).forEach(function(details, index) {
                var product = details[0].product;
                var type = 'Ecer';
                var typeClass = 'badge-cart-normal';

                var colors = details.map(color => `
                    <div class="cart-item-spacer">
                        <div
                            title="${color.color.name}"
                            style="width:24px;height:24px;background:${color.color.hex_code};"
                        >
                        </div>
                    </div>
                `).join('');

                var prices = details.map(color => `
                    <div class="cart-item-spacer">
                        Rp ${toCurrency(color.price_used)}
                    </div>
                `).join('');

                var weights = details.map(color => `
                    <div class="cart-item-spacer">
                        ${toCurrency(color.weight)}g
                    </div>
                `).join('');

                var quantities = details.map(color => `
                    <div class="cart-item-spacer">
                        ${toCurrency(color.quantity)}
                    </div>
                `).join('');

                var subtotals = details.map(color => `
                    <div class="cart-item-spacer">
                        Rp ${toCurrency(color.subtotal)}
                    </div>
                `).join('');

                switch (details[0].type) {
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

                $('#table-detail tbody').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <p>${product.title}</p>
                            <span class="badge ${typeClass}">${type}</span>
                        </td>
                        <td>${colors}</td>
                        <td>${prices}</td>
                        <td>${weights}</td>
                        <td>${quantities}</td>
                        <td>${subtotals}</td>
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
            is_hand_over: value
          },
          success: function(response) {
              iziToast.success({
                  title: 'Berhasil!',
                  message: 'Berhasil mengubah status penyerahan pengembalian',
                  position: 'topRight'
              });

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal mengubah status penyerahan pengembalian',
                  position: 'topRight'
              });

              $('#spinner').hide();
              $('.table-responsive').show();
          }
      });
  });
</script>
@endsection
