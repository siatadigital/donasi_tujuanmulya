@extends('admin.master')

@section('title', 'RTL - Penjualan Shopee')

@section('content')
<div class="section-header">
  <h1>Penjualan Shopee</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Penjualan Shopee</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Penjualan Shopee</h2>
  <p class="section-lead">
    Daftar penjualan shopee
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Penjualan Shopee</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.shopee-sales.create') }}" class="btn btn-primary">Tambah</a>
            <br><br>
            <ul class="nav nav-tabs" id="tab" role="tablist">
                @foreach ($statusTabs as $index => $tab)
                <li class="nav-item">
                    <?php
                        $isSelected = FALSE;
                        $params = $tab->id ? "?status=$tab->id" : '';

                        if ($statusId) {
                            $isSelected = $statusId == $tab->id;
                        } else {
                            $isSelected = $index === 0;
                        }
                    ?>
                    <a
                        class="nav-link {{ $isSelected ? 'active' : '' }}"
                        id="{{ $tab->slug }}-tab"
                        href="{{ route('admin.shopee-sales.index') . $params }}"
                        data-index="{{ $index }}"
                        aria-controls="{{ $tab->slug }}"
                        aria-selected="true"
                    >
                        {{ $tab->name }} ({{ $tab->total_sales }})
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="tab-content" id="tab-content">
                @foreach ($statusTabs as $index => $tab)
                <?php
                    $isSelected = FALSE;

                    if ($statusId) {
                        $isSelected = $statusId == $tab->id;
                    } else {
                        $isSelected = $index === 0;
                    }
                ?>
                <div class="tab-pane fade show {{ $isSelected ? 'active' : '' }}" id="{{ $tab->slug }}" role="tabpanel" aria-labelledby="{{ $tab->slug }}-tab">
                    @if ($isSelected)
                    <div class="spinner" style="display:none;">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('admin-assets/img/spinner.gif') }}" alt="Loading..." style="margin:48px;">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-{{ $index + 1 }}">
                            <thead>
                            <tr>
                                <th class="text-center" width="32px">
                                #
                                </th>
                                <th>Kode</th>
                                <th>User</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Diskon</th>
                                <th>Ongkir</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Total Berat</th>
                                <th>Ekspedisi</th>
                                <th>No. Resi</th>
                                <th>Tanggal</th>
                                <th width="128px">Aksi</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    @endif
                </div>
                @endforeach
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
                        <strong>Jenis Stok</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content stock-type"></p>
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
                        <strong>Ongkir</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content courier-cost"></p>
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
                        <form method="POST" id="status-form">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <select name="status_id" class="form-control mb-2"></select>
                            <button class="btn btn-primary btn-block">Ubah</button>
                        </form>
                        <br><br>
                    </div>
                </div>
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
                @if ($isCancelAccessible)
                <a href="#" class="btn btn-danger" id="btn-cancel">Batalkan</a>
                @endif
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
  var statusId = '{{ $statusId }}';
  var tabIndex = $('.nav-link.active').data('index');
  var tableId = '#table-' + (tabIndex + 1);

  var table = $(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.datatables.shopee-sales", ['statusId' => '']) }}/' + statusId,
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
            data: 'status_id',
            name: 'status_id'
        },
        {
            data: 'discount',
            name: 'discount'
        },
        {
            data: 'courier_cost',
            name: 'courier_cost'
        },
        {
            data: 'total_item',
            name: 'total_item'
        },
        {
            data: 'total_price',
            name: 'total_price'
        },
        {
            data: 'total_weight',
            name: 'total_weight'
        },
        {
            data: 'courier',
            name: 'courier'
        },
        {
            data: 'expedition_number',
            name: 'expedition_number'
        },
        {
            data: 'date',
            name: 'date'
        },
        ],
        columnDefs: [
            {
                targets: 4,
                render: function(data, type, row)  {
                    var statuses = JSON.parse(row.statuses);

                    var statusSelection = `
                        <form action="{{ route('admin.shopee-sales.status.update', ['id' => '']) }}/${row.id}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <select name="status_id" class="form-control" style="width:220px">
                                ${statuses.map(status => {
                                    return `<option value="${status.id}">${status.name}</option>`;
                                })}
                            </select>
                        </form>
                    `;

                    return statusSelection;
                },
            },
            {
                targets: 12,
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

                    var print = '<a target="_blank" href="{{ route("admin.shopee-sales.print", ["id" => "ID_HERE"]) }}" class="btn btn-success">Print</a>';

                    print = print.replace(/ID_HERE/g, row.id);

                    var isPrintPermitted = Number("{{ (int) isPermitted('admin.shopee-sales.print') }}");
                    var buttons = show;

                    if (isPrintPermitted) {
                        buttons += '&nbsp;&nbsp;' + print;
                    }

                    return buttons;
                },
            },
        ],
        rowCallback: function(row, data) {
            $('td:eq(4)', row).find('select').val(data.status_id);
        }
  });

  $('table').on('click', '.btn-detail', function() {
    var itemId = $(this).data('item-id');

    $('.detail-content').text('');
    $('#table-detail tbody').empty();
    $('#table-print tbody').empty();
    $('#table-status tbody').empty();
    $('.spinner').show();
    $('.table-responsive').hide();

    $.ajax({
        url: "{{ route('admin.shopee-sales.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            $('.spinner').hide();
            $('.table-responsive').show();

            var item = response.data;
            var action = $('#modal-detail #status-form').attr('action') + '/' + itemId;
            var courierCost = item.delivery ? item.delivery.courier_cost ? item.delivery.courier_cost : 0 : 0;
            var isCancelled = item.status_id == 8;
            
            var totalQuantity = _.sumBy(item.details, 'quantity');
            var statuses = response.additional.statuses;
            var subtotal = item.raw_total_price;
            var discount = item.coupon_discount_amount;
            var detailsByIndex = _.groupBy(item.details, 'item_index');

            $('#modal-detail #status-form').attr('action', "{{ route('admin.shopee-sales.status.update', ['id' => '']) }}" + '/' + itemId);

            $('.detail-content.code').text(item.code);
            $('.detail-content.date').text(item.date);
            $('.detail-content.user').text(item.user ? item.user.username : 'Tidak Ada');
            $('.detail-content.customer').text(item.delivery ? item.delivery.destination_fullname : 'Belum ada nama penerima');
            $('.detail-content.stock-type').text('Shopee');
            $('.detail-content.courier').text('Tidak Ada');
            $('.detail-content.courier-cost').text(`Rp. ${toCurrency(courierCost)}`);
            $('.detail-content.total-weight').text(`${toCurrency(item.total_weight)}g`);
            $('.detail-content.notes').text(item.notes || 'Tidak Ada');
            $('.detail-content.status').text(item.status.name);
            $('#modal-detail #status-form').find('select[name="status_id"]').html('');
            statuses.forEach(function(status, index) {
                $('#modal-detail #status-form').find('select[name="status_id"]').append("<option value='"+status.id+"'>"+status.name+"</option>");
            });
            $('#modal-detail #status-form').find('select').val(item.status_id);

            if (isCancelled) {
                $('#btn-cancel').hide();
            } else {
                $('#btn-cancel').show();
            }

            var cancelUrl = "{{ route('admin.shopee-sales.cancel', ['id' => 'ID_HERE']) }}".replace('ID_HERE', itemId);

            $('#btn-cancel').attr('href', cancelUrl);

            $('.detail-content.discount').text(`Rp ${toCurrency(discount)}`);

            $('.detail-content.subtotal').text(`Rp ${toCurrency(subtotal)}`);
            $('.detail-content.total-price').text(`Rp ${toCurrency(subtotal - discount + courierCost)}`);

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
                        Rp ${toCurrency(color.raw_price)}
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

                var discounts = details.map(color => `
                    <div class="cart-item-spacer">
                        Rp ${toCurrency(color.discount_amount)}
                    </div>
                `).join('');

                var subtotals = details.map(color => `
                    <div class="cart-item-spacer">
                        Rp ${toCurrency(color.subtotal)}
                    </div>
                `).join('');

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
                        <td>${discounts}</td>
                        <td>${subtotals}</td>
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
                    <th>Total Harga</th>
                    <td>Rp ${toCurrency(subtotal - discount + courierCost)}</td>
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
                        <td>${log.user.username}</td>
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

  $('table').on('change', 'select', function() {
      var url = $(this).parent().attr('action');
      var token = '{{ csrf_token() }}';
      var value = Number($(this).val());

      $('.spinner').show();
      $('.table-responsive').hide();

      $.ajax({
          method: "PUT",
          url: url,
          data: {
            _token: token,
            status_id: value
          },
          success: function(response) {
              var tabs = response.data.statusTabs;

              $('#tab').find('li').each(function(index) {
                  var tab = tabs[index];

                  table.ajax.reload();
                  $(this).find('a').text(`${tab.name} (${tab.total_sales})`);
              });

              iziToast.success({
                  title: 'Berhasil!',
                  message: 'Berhasil mengubah status penjualan shopee',
                  position: 'topRight'
              });

              $('.spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal mengubah status penjualan shopee',
                  position: 'topRight'
              });

              $('.spinner').hide();
              $('.table-responsive').show();
          }
      });
  });

    $('#modal-detail').on('submit', '#status-form', function() {
        var url = $(this).attr('action');
        var token = '{{ csrf_token() }}';
        var value = Number($(this).find('select').val());

        $('.spinner').show();
        $('.table-responsive').hide();

        $.ajax({
            method: "PUT",
            url: url,
            data: {
                _token: token,
                status_id: value
            },
            success: function(response) {
                var tabs = response.data.statusTabs;

                $('#tab').find('li').each(function(index) {
                    var tab = tabs[index];

                    table.ajax.reload();
                    if (tab) {
                        $(this).find('a').text(`${tab.name} (${tab.total_sales})`);
                    }
                });

                $('.table-responsive form[action="'+url+'"]').find('select').val(value);

                iziToast.success({
                    title: 'Berhasil!',
                    message: 'Berhasil mengubah status penjualan shopee',
                    position: 'topRight'
                });

                $('.spinner').hide();
                $('.table-responsive').show();
            },
            error: function(e) {
                iziToast.error({
                    title: 'Gagal!',
                    message: 'Gagal mengubah status penjualan shopee',
                    position: 'topRight'
                });

                $('.spinner').hide();
                $('.table-responsive').show();
            }
        });

        return false;
    });
</script>
@endsection
