@extends('admin.master')

@section('title', 'RTL - Pengiriman')

@section('content')
<div class="section-header">
  <h1>Pengiriman</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Pengiriman</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Pengiriman</h2>
  <p class="section-lead">
    Daftar pengiriman
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Pengiriman</h4>
          </div>
          <div class="card-body">
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
                    <th>Tanggal</th>
                    <th width="128px">Aksi</th>
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
                <h5>Info Utama</h5>
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
                        <strong>Kode Pemesanan</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content sales-code"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>No. Resi</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content receipt"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Nama Kurir</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content courier-name"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Layanan Kurir</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content courier-service"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Estimasi</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content estimated"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Ongkos Kirim</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content delivery-cost"></p>
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
                        <strong>Catatan</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content notes"></p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Info Pembeli</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Nama Lengkap</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content origin-fullname"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Email</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content origin-email"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>No. HP</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content origin-phone"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Alamat</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content origin-address"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Tujuan Pengiriman</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Nama Lengkap</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content destination-fullname"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Email</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content destination-email"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>No. HP</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content destination-phone"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Alamat</strong>
                            </div>
                            <div class="col-md-8">
                                <p class="detail-content destination-address"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered" id="table-detail">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Warna</th>
                            <th>Berat (g)</th>
                            <th>Kuantitas</th>
                            <th>Total Berat</th>
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
    ajax: '{{ route("admin.datatables.deliveries") }}',
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
        data: 'origin_fullname',
        name: 'origin_fullname'
      },
      {
        data: 'date',
        name: 'date'
      },
    ],
    columnDefs: [{
      targets: 5,
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

        var print = '<a target="_blank" href="{{ route("admin.deliveries.print", ["id" => "ID_HERE"]) }}" class="btn btn-success">Print</a>';

        print = print.replace(/ID_HERE/g, row.id);

        var isPrintPermitted = Number("{{ (int) isPermitted('admin.deliveries.print') }}");
        var buttons = show;

        if (isPrintPermitted) {
            buttons += '&nbsp;&nbsp;' + print;
        }

        return buttons;
      },
    }, ],
  });

  $('table').on('click', '.btn-detail', function() {
    var itemId = $(this).data('item-id');

    $('.detail-content').text('');
    $('#table-detail tbody').empty();
    $('#table-print tbody').empty();
    $('#spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        url: "{{ route('admin.deliveries.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var item = response.data;
            var action = $('#modal-detail form').attr('action') + '/' + itemId;

            $('#spinner').hide();
            $('.table-responsive').show();

            $('select[name=status_id]').val(item.status_id);
            $('#modal-detail form').attr('action', action);

            $('.detail-content.code').text(item.code);
            $('.detail-content.date').text(item.date);
            $('.detail-content.user').text(item.user ? item.user.username : 'Tidak Ada');
            $('.detail-content.sales-code').text(item.sales.code);
            $('.detail-content.receipt').text(item.expedition_number || 'Tidak Ada');
            $('.detail-content.courier-name').text(item.courier_info || 'Tidak Ada');
            $('.detail-content.courier-service').text(item.courier_service_info || 'Tidak Ada');
            $('.detail-content.estimated').text(item.courier_estd + ' hari');
            $('.detail-content.delivery-cost').text(`Rp ${toCurrency(item.courier_cost || 0)}`);
            $('.detail-content.total-weight').text(`${toCurrency(item.total_weight)}g`);
            $('.detail-content.notes').text(item.notes || 'Tidak Ada');

            $('.detail-content.origin-fullname').text(item.origin_fullname || 'Tidak Ada');
            $('.detail-content.origin-email').text(item.origin_email || 'Tidak Ada');
            $('.detail-content.origin-phone').text(item.origin_phone || 'Tidak Ada');
            $('.detail-content.origin-address').html(
                `${item.origin_address}
                <br/>${item.origin_subdistrict.province.name}, ${item.origin_subdistrict.city.name}, ${item.origin_subdistrict.name}
                <br/>${item.origin_subdistrict.city.postcode}
                ` || 'Tidak Ada'
            );

            $('.detail-content.destination-fullname').text(item.destination_fullname || 'Tidak Ada');
            // $('.detail-content.destination-email').text(item.destination_email || 'Tidak Ada');
            $('.detail-content.destination-phone').text(item.destination_phone || 'Tidak Ada');
            $('.detail-content.destination-address').html(
                `${item.destination_address}
                <br/>${item.destination_subdistrict.province.name}, ${item.destination_subdistrict.city.name}, ${item.destination_subdistrict.name}
                <br/>${item.destination_subdistrict.city.postcode}
                ` || 'Tidak Ada'
            );

            item.sales.details.forEach(function(detail, index) {
                var price = detail[`price_sell_${detail.type}`];
                var discount = price * detail.discount / 100;

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
                        <td>${toCurrency(detail.weight)}g</td>
                        <td>${toCurrency(detail.quantity)}</td>
                        <td>${toCurrency(detail.weight * detail.quantity)}g</td>
                    </tr>
                `);
            });

            $('#table-detail tbody').append(`
                <tr>
                    <td colspan="4"></td>
                    <th>Total Berat</th>
                    <td>${toCurrency(item.total_weight)}g</td>
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
</script>
@endsection
