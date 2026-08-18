@extends('admin.master')

@section('title', 'RTL - Penjualan Global')

@section('content')
<div class="section-header">
  <h1>Penjualan Global</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Penjualan Global</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Penjualan Global</h2>
  <p class="section-lead">
    Daftar penjualan global
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Penjualan Global</h4>
        </div>
        <div class="card-body">
          <form id="form-filter" action="{{ route('admin.reports.global-sales') }}">
            <div class="row">
              <div class="col-md-3">
                @component('admin.components.form-input', [
                    'label' => 'Periode',
                    'type' => 'text',
                    'name' => 'period',
                    'value' => $period,
                    'error' => $errors->first('period'),
                    ])
                @endcomponent
              </div>
              <div class="col-md-2">
                <button class="btn btn-primary" style="margin-top:29px;">Filter</button>
                <button type="button" id="btn-reset" class="btn btn-danger" style="margin-top:29px;">Reset</button>
              </div>
            </div>
          </form>
          @if (isset($items))
          <a target="_blank" href="{{ route('admin.reports.global-sales.print') }}" class="btn btn-primary">Print</a>
          <br><br>
          <div class="spinner" style="display:none;">
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
                  <th>Kode Penjualan</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                  <th>Total Harga</th>
                  <th width="25">Aksi</th>
                </tr>
              </thead>
              <tbody>
              @if ($items->count())
              @foreach($items as $index => $item)
              <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $item->code }}</td>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->date }}</td>
                  <td>{{ number_format($item->amount_transfer) }}</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-sales-detail" data-item-id="{{ $item->id }}" data-type="{{ $item->type }}">Detail</button>
                  </td>
              </tr>
              @endforeach
              <tr>
                  <td colspan="4">
                    <p class="text-right mb-0"><strong>Total Semua</strong></p>
                  </td>
                  <td>{{ number_format($items->sum('amount_transfer')) }}</td>
              </tr>
              @else
              <tr><td colspan="6"><p class="text-center mb-0">Tidak ada data</p></td></tr>
              @endif
              </tbody>
            </table>
          </div>
          @endif
        </div>
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
                        <strong>Subtotal</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content subtotal"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Diskon <span></span></strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content discount"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Ekspedisi</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content courier"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Tujuan Pengiriman</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content delivery-destination"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Ongkir</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content courier-cost"></p>
                    </div>
                </div>
                <div class="row paid-deposit">
                    <div class="col-md-4">
                        <strong>Terbayar Deposit</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content paid-deposit"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Kode Unik</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content unique-code"></p>
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
                        <strong>Poin</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content point"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <strong>Metode Pembayaran</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content payment-method"></p>
                    </div>
                </div>
                <div class="row keep-stock"">
                    <div class="col-md-4">
                        <strong>Keep Stock</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content keep-stock"></p>
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
                <div class="row">
                    <div class="col-md-4">
                        <strong>Status</strong>
                    </div>
                    <div class="col-md-4">
                        <p class="detail-content status"></p>
                        <br><br>
                    </div>
                </div>
                <hr>
                <div id="upload-payment-detail">
                    <h6>Bukti Pembayaran</h6>
                    <a href="{{ route('frontend.order.payment_confirm', ['id' => '']) }}" target="_blank" class="btn btn-warning">Upload Bukti Pembayaran</a>
                    <hr>
                </div>
                <div id="payment-detail">
                    <h6>Bukti Pembayaran</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Tanggal</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-date"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Dari</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-from-bank"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Pada</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-to-bank"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Nominal</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-amount"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Terkonfirmasi</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-confirmed"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Terbayar</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-paid"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Bukti Foto</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="detail-content payment-proof"></p>
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
<script>
var $period = $('input[name="period"]');
var $btnReset = $('#btn-reset');
var $formFilter = $('#form-filter');

$period.daterangepicker({
  locale: {
    format: 'DD/MM/YYYY'
  }
});

$period.val('{{ $period }}');

$btnReset.on('click', function() {
    $period.val('');
    $formFilter.submit();
});
$('table').on('click', '.btn-sales-detail', function() {
  var itemId = $(this).data('item-id');
  var url = "{{ route('admin.online-sales.show', ['id' => '']) }}/" + itemId;
  var type = $(this).data('type');
  url = url.replace("online", type);

  $('.detail-content').text('');
  $('#table-detail tbody').empty();
  $('#table-print tbody').empty();
  $('#table-status tbody').empty();
  $('.spinner').show();
  $('.table-responsive').hide();

  $.ajax({
      url: url,
      method: 'GET',
      dataType: 'json',
      success: function(response) {
          $('.spinner').hide();
          $('.table-responsive').show();

          var item = response.data;
          var totalQty = response.additional.total_quantity;
          var paidByDeposit = response.additional.paid_by_deposit;
          var statuses = response.additional.statuses;
          var uniqueCode = item.payment ? item.payment.unique_code : 0;
          var courierName = item.delivery ? item.delivery.courier_info + ' - ' + item.delivery.courier_service_info : 'Walk In';
          var courierCost = item.delivery ? item.delivery.courier_cost ? item.delivery.courier_cost : 0 : 0;

          $('.detail-content.code').text(item.code);
          $('.detail-content.date').text(item.date);
          $('.detail-content.user').text(item.user ? item.user.fullname + '(' + item.user.phone + ')' : (item.delivery ? item.delivery.origin_fullname + '(' + item.delivery.origin_phone + ')' : 'Tidak diketahui'));
          $('.detail-content.customer').text(item.delivery ? item.delivery.destination_fullname + '(' + item.delivery.destination_phone + ')' : 'Belum ada nama penerima');
          $('.detail-content.courier').text(courierName);
          $('.detail-content.courier-cost').text(`Rp. ${toCurrency(courierCost)}`);
          $('.detail-content.delivery-destination').html(item.delivery ? item.delivery.destination_address + '<br>' + item.delivery.destination_city.province.name + ', ' + item.delivery.destination_city.name + ', ' + (item.delivery.destination_subdistrict ? item.delivery.destination_subdistrict.name : '-') + '<br>' + item.delivery.destination_postcode : '-')
          $('.detail-content.total-weight').text(`${toCurrency(item.total_weight)}g`);
          $('.detail-content.point').text(`${toCurrency(Number(item.point))}`);
          $('.detail-content.notes').text(item.notes || 'Tidak Ada');
          $('.detail-content.status').text(item.status.name);
          $('.detail-content.paid-deposit').text(`Rp. ${toCurrency(paidByDeposit)}`);
          $('.detail-content.keep-stock').text(parseInt(item.is_keep_stock) === 1 ? 'Ya' : 'Tidak');
          $('.detail-content.payment-method').text(item.payment.type[0].toUpperCase() + item.payment.type.slice(1));

          if (item.status_id === 3) {
              $('#upload-payment-detail').hide();
              $('#payment-detail').show();
              $('.detail-content.payment-date').text(item.payment.date);
              $('.detail-content.payment-from-bank').text(`${item.payment.from_bank_name} a.n. ${item.payment.from_account_name}`);
              $('.detail-content.payment-to-bank').text(item.payment.bank ? `${item.payment.bank.bank_name} - ${item.payment.bank.account_number} a.n. ${item.payment.bank.account_name}` : 'Tunai');
              $('.detail-content.payment-amount').text(item.payment.from_amount_transfer);
              $('.detail-content.payment-confirmed').text(item.payment.is_confirm ? `Ya, dikonfirmasi pada tanggal ${item.payment.confirmed_at}` : "Tidak");
              $('.detail-content.payment-paid').text(item.payment.is_paid ? `Ya, dibayar pada tanggal ${item.payment.paid_at}` : "Tidak");
              $('.detail-content.payment-proof').html(`<img style="width:480px;480px;" src="{{ asset('uploads/${item.payment.photo_transfer}') }}" />`);
          }else if (item.status_id === 2) {
              $('#upload-payment-detail').show();
              $('#upload-payment-detail a').attr("href", "/order/payment/" + itemId + "/confirm/");
              $('#payment-detail').hide();
          }else {
              $('#upload-payment-detail').hide();
              $('#payment-detail').hide();
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

          $('.detail-content.discount').text(`Rp ${toCurrency(discount)}`);
          $('.detail-content.subtotal').text(`Rp ${toCurrency(subtotal)}`);
          $('.detail-content.unique-code').text(uniqueCode ? `Rp ${toCurrency(uniqueCode)}` : 'Tidak Ada');
          $('.detail-content.total-price').text(`Rp ${toCurrency(subtotal - discount + courierCost + uniqueCode)}`);

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

          $('#modal-detail').modal({ show: true });
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
</script>
@endsection
