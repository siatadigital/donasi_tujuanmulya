@extends('admin.master')

@section('title', 'RTL - Deposit')

@section('content')
<div class="section-header">
  <h1>Deposit</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Deposit</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Deposit</h2>
  <p class="section-lead">
    Daftar deposit
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Deposit</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.deposits.create') }}" class="btn btn-primary">Tambah</a>
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
                    <th>User</th>
                    <th>Customer</th>
                    <th>Uang Keluar</th>
                    <th>Uang Masuk</th>
                    <th>Uang Saat Itu</th>
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
    <div class="modal-dialog">
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
                        <strong>Bank Tujuan</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content bank"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Uang Keluar</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content amount-out"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Uang Masuk</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content amount-in"></p>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-md-4">
                        <strong>Uang Saat Ini</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content current-amount"></p>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-md-4">
                        <strong>Deskripsi</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content description"></p>
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
                <div class="row row-detail-content sales-detail">
                    <div class="col-md-12">
                      <button type="button" class="btn btn-primary btn-sales-detail">Lihat Detail Penjualan</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" role="dialog" id="modal-sales-detail">
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
                        <p class="detail-sales-content code"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Tanggal</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content date"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>User</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content user"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Customer</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content customer"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Subtotal</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content subtotal"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Diskon <span></span></strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content discount"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Ekspedisi</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content courier"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Tujuan Pengiriman</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content delivery-destination"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Ongkir</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content courier-cost"></p>
                    </div>
                </div>
                <div class="row paid-deposit">
                    <div class="col-md-4">
                        <strong>Terbayar Deposit</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content paid-deposit"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Kode Unik</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content unique-code"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Total Harga</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content total-price"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Total Berat</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content total-weight"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Poin</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content point"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Metode Pembayaran</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content payment-method"></p>
                    </div>
                </div>
                <div class="row keep-stock"">
                    <div class="col-md-4">
                        <strong>Keep Stock</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content keep-stock"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Catatan</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-sales-content notes"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Status</strong>
                    </div>
                    <div class="col-md-4">
                        <p class="detail-sales-content status"></p>
                    </div>
                </div>
                <hr>
                <div id="payment-detail">
                    <h6>Bukti Pembayaran</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Tanggal</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-date"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Dari</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-from-bank"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Pada</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-to-bank"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Nominal</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-amount"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Terkonfirmasi</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-confirmed"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Terbayar</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-paid"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Bukti Foto</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-sales-content payment-proof"></p>
                        </div>
                    </div>
                    <hr>
                </div>
                <h6>Log Status Pesanan</h6>
                <table class="table table-bordered" id="table-status">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Status</th>
                            <th>Pengubah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <hr>
                <h6>Item Produk</h6>
                <table class="table table-bordered" id="table-detail">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Warna</th>
                            <th>Harga Jual</th>
                            <th>Berat (g)</th>
                            <th>Kuantitas</th>
                            <th>Diskon</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <hr>
                <h6>Log Print</h6>
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
    ajax: '{{ route("admin.datatables.deposits") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'user',
        name: 'user.username'
      },
      {
        data: 'customer',
        name: 'customer_deposit.user.fullname'
      },
      {
        data: 'amount_out',
        name: 'amount_out'
      },
      {
        data: 'amount_in',
        name: 'amount_in'
      },
      {
        data: 'current_amount',
        name: 'current_amount'
      },
      {
        data: 'date',
        name: 'date'
      },
    ],
    columnDefs: [{
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

        return show;
      },
    }, ],
  });

  $('table').on('click', '.btn-detail', function() {
    var itemId = $(this).data('item-id');

    $('.detail-content').text('');
    $('#spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        url: "{{ route('admin.deposits.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var item = response.data;

            $('#spinner').hide();
            $('.table-responsive').show();
            $('.detail-content.user').text(item.user ? item.user.username : 'Tidak Ada');
            $('.detail-content.customer').text(item.customer_deposit.user.fullname || item.customer_deposit.user.username);
            $('.detail-content.bank').text(item.customer_deposit.deposit_type !== 'cash' ? item.customer_deposit.bank ? item.customer_deposit.bank.bank_name + ' - ' + item.customer_deposit.bank.account_number + ' a.n. ' + item.customer_deposit.bank.account_name : 'Tunai' : 'Tunai');
            $('.detail-content.amount-out').text(`Rp ${toCurrency(item.amount_out)}`);
            $('.detail-content.amount-in').text(`Rp ${toCurrency(item.amount_in)}`);
            // $('.detail-content.current-amount').text(`Rp ${toCurrency(item.customer_deposit.current_amount)}`);
            $('.detail-content.description').text(item.description);
            $('.detail-content.date').text(item.created_at);
            
            if (parseInt(item.transaction_category_id) == 2) {
              $('.row-detail-content.sales-detail').show();
              $('.row-detail-content .btn-sales-detail').attr('data-item-id', item.transaction_id);
            }else {
              $('.row-detail-content.sales-detail').hide();
            }

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

    $('.row-detail-content').on('click', '.btn-sales-detail', function() {
      var itemId = $(this).data('item-id');

      $('.detail-sales-content').text('');
      $('#table-detail tbody').empty();
      $('#table-print tbody').empty();
      $('#table-status tbody').empty();
      $('.spinner').show();
      $('.table-responsive').hide();

      $.ajax({
          url: "{{ route('admin.online-sales.show', ['id' => '']) }}/" + itemId,
          method: 'GET',
          dataType: 'json',
          success: function(response) {
              $('.spinner').hide();
              $('.table-responsive').show();

              var item = response.data;
              var totalQty = response.additional.total_quantity;
              var paidByDeposit = response.additional.paid_by_deposit;
              var uniqueCode = item.payment ? item.payment.unique_code : 0;
              var action = $('#modal-sales-detail form').attr('action') + '/' + itemId;
              var courierName = item.delivery ? item.delivery.courier_info + ' - ' + item.delivery.courier_service_info : 'Walk In';
              var courierCost = item.delivery ? item.delivery.courier_cost ? item.delivery.courier_cost : 0 : 0;
              var isCancelled = item.status_id == 8;

              $('select[name=status_id]').val(item.status_id);
              $('#modal-sales-detail form').attr('action', action);

              $('.detail-sales-content.code').text(item.code);
              $('.detail-sales-content.date').text(item.date);
              $('.detail-sales-content.user').text(item.user ? item.user.fullname + '(' + item.user.phone + ')' : (item.delivery ? item.delivery.origin_fullname + '(' + item.delivery.origin_phone + ')' : 'Tidak diketahui'));
              $('.detail-sales-content.customer').text(item.delivery ? item.delivery.destination_fullname + '(' + item.delivery.destination_phone + ')' : 'Belum ada nama penerima');
              $('.detail-sales-content.courier').text(courierName);
              $('.detail-sales-content.courier-cost').text(`Rp. ${toCurrency(courierCost)}`);
              $('.detail-sales-content.delivery-destination').html(item.delivery ? item.delivery.destination_address + '<br>' + item.delivery.destination_city.province.name + ', ' + item.delivery.destination_city.name + ', ' + (item.delivery.destination_subdistrict ? item.delivery.destination_subdistrict.name : '-') + '<br>' + item.delivery.destination_postcode : '-')
              $('.detail-sales-content.total-weight').text(`${toCurrency(item.total_weight)}g`);
              $('.detail-sales-content.point').text(`${toCurrency(Number(item.point))}`);
              $('.detail-sales-content.notes').text(item.notes || 'Tidak Ada');
              $('.detail-sales-content.status').text(item.status.name);
              $('.detail-sales-content.paid-deposit').text(`Rp. ${toCurrency(paidByDeposit)}`);
              $('.detail-sales-content.keep-stock').text(parseInt(item.is_keep_stock) === 1 ? 'Ya' : 'Tidak');
              $('.detail-sales-content.payment-method').text(item.payment.type[0].toUpperCase() + item.payment.type.slice(1));

              if (item.status_id === 3) {
                  $('#payment-detail').show();
                  $('.detail-sales-content.payment-date').text(item.payment.date);
                  $('.detail-sales-content.payment-from-bank').text(`${item.payment.from_bank_name} a.n. ${item.payment.from_account_name}`);
                  $('.detail-sales-content.payment-to-bank').text(item.payment.bank ? `${item.payment.bank.bank_name} - ${item.payment.bank.account_number} a.n. ${item.payment.bank.account_name}` : 'Tunai');
                  $('.detail-sales-content.payment-amount').text(item.payment.from_amount_transfer);
                  $('.detail-sales-content.payment-confirmed').text(item.payment.is_confirm ? `Ya, dikonfirmasi pada tanggal ${item.payment.confirmed_at}` : "Tidak");
                  $('.detail-sales-content.payment-paid').text(item.payment.is_paid ? `Ya, dibayar pada tanggal ${item.payment.paid_at}` : "Tidak");
                  $('.detail-sales-content.payment-proof').html(`<img style="width:480px;480px;" src="{{ asset('uploads/${item.payment.photo_transfer}') }}" />`);
              } else {
                  $('#payment-detail').hide();
              }

              if (isCancelled) {
                  $('#btn-cancel').hide();
              } else {
                  $('#btn-cancel').show();
              }

              if (parseInt(item.is_keep_stock)) {
                  $('.row.keep-stock').show();
                  $('.row.paid-deposit').show();
              } else {
                  $('.row.keep-stock').hide();
                  $('.row.paid-deposit').hide();
              }

              var totalQuantity = _.sumBy(item.details, 'quantity');
              var subtotal = item.raw_total_price;
              var discount = item.coupon_discount_amount;

              $('.detail-sales-content.discount').text(`Rp ${toCurrency(discount)}`);
              $('.detail-sales-content.subtotal').text(`Rp ${toCurrency(subtotal)}`);
              $('.detail-sales-content.unique-code').text(uniqueCode ? `Rp ${toCurrency(uniqueCode)}` : 'Tidak Ada');
              $('.detail-sales-content.total-price').text(`Rp ${toCurrency(subtotal - discount + courierCost + uniqueCode)}`);

              var cancelUrl = "{{ route('admin.online-sales.cancel', ['id' => 'ID_HERE']) }}".replace('ID_HERE', itemId);

              $('#btn-cancel').attr('href', cancelUrl);

              item.details_by_index.forEach(function(colors, index) {
                  var detail = colors[0];
                  var type = detail.type[0].toUpperCase() + detail.type.slice(1);

                  if (detail.type === 'normal') type = 'Ecer';
                  if (detail.type === 'wholesaler') type = 'Grosir';

                  var colColors = colors.map(function(color) {
                      return `
                          <div
                              title="${color.color.name}"
                              style="margin-bottom: 8px;width:24px;height:24px;background:${color.color.hex_code};"
                          >
                          </div>
                      `;
                  }).join('');

                  var colPrices = colors.map(function(color) {
                      return `<p>Rp ${toCurrency(color.raw_price)}</p>`;
                  }).join('');

                  var colWeights = colors.map(function(color) {
                      return `<p>${toCurrency(color.weight)}g</p>`;
                  }).join('');

                  var colQuantities = colors.map(function(color) {
                      return `<p>${toCurrency(color.quantity)}</p>`;
                  }).join('');

                  var colDiscounts = colors.map(function(color) {
                      return `<p>Rp ${toCurrency(color.discount_amount)}</p>`;
                  }).join('');

                  var colTotal = colors.map(function(color) {
                      return `<p>Rp ${toCurrency(color.subtotal)}</p>`;
                  }).join('');

                  $('#table-detail tbody').append(`
                      <tr>
                          <td>${index + 1}</td>
                          <td>
                              <p style="color:#333;">${detail.product.title}</p>
                              <p class="badge badge-primary">${type}</p>
                          </td>
                          <td>${colColors}</td>
                          <td>${colPrices}</td>
                          <td>${colWeights}</td>
                          <td>${colQuantities}</td>
                          <td>${colDiscounts}</td>
                          <td>${colTotal}</td>
                      </tr>
                  `);
              });

            $('#table-detail tbody').append(`
                <tr>
                    <td colspan="6"></td>
                    <th>Total Item</th>
                    <td>${totalQuantity}</td>
                </tr>
            `);

              $('#table-detail tbody').append(`
                  <tr>
                      <td colspan="6"></td>
                      <th>Subtotal</th>
                      <td>Rp ${toCurrency(subtotal)}</td>
                  </tr>
              `);

              $('#table-detail tbody').append(`
                  <tr>
                      <td colspan="6"></td>
                      <th>Diskon</th>
                      <td>Rp ${toCurrency(discount)}</td>
                  </tr>
              `);

              $('#table-detail tbody').append(`
                  <tr>
                      <td colspan="6"></td>
                      <th>Ongkir</th>
                      <td>Rp ${toCurrency(courierCost)}</td>
                  </tr>
              `);

              if (parseInt(item.is_keep_stock) === 1) {
                  $('#table-detail tbody').append(`
                      <tr>
                          <td colspan="6"></td>
                          <th>Terbayar Deposit</th>
                          <td>Rp ${toCurrency(paidByDeposit)}</td>
                      </tr>
                  `);
              }

              if (item.status_id >= 2) {
                  $('#table-detail tbody').append(`
                      <tr>
                          <td colspan="6"></td>
                          <th>Kode Unik</th>
                          <td>Rp ${toCurrency(uniqueCode)}</td>
                      </tr>
                  `);
              }

              $('#table-detail tbody').append(`
                  <tr>
                      <td colspan="6"></td>
                      <th>Total Harga</th>
                      <td>Rp ${toCurrency(subtotal - discount + courierCost + uniqueCode)}</td>
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

              item.status_logs.forEach(function(log, index) {
                  $('#table-status tbody').append(`
                      <tr>
                          <td>${index + 1}</td>
                          <td>${log.status.name}</td>
                          <td>${log.user ? log.user.username : 'guest'}</td>
                          <td>${log.created_at}</td>
                      </tr>
                  `);
              });

              $('#modal-sales-detail').modal({ show: true });
          },
          error: function() {
              $('.spinner').hide();
              $('.table-responsive').show();

              swal({
                  icon: 'error',
                  title: 'Gagal',
                  text: 'Maaf, tidak dapat mengambil data !',
              });
          }
      });
    });
  });
</script>
@endsection
