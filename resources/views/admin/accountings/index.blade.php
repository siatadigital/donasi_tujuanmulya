@extends('admin.master')

@section('title', 'RTL - Accounting')

@section('content')
<div class="section-header">
  <h1>Accounting</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Accounting</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Accounting</h2>
  <p class="section-lead">
    Daftar accounting
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Accounting</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.accountings.create') }}" class="btn btn-primary">Tambah</a>
            <br><br>
            <div class="table-responsive">
              <table class="table table-striped" id="table">
                <thead>
                  <tr>
                    <th class="text-center" width="32px">
                      #
                    </th>
                    <th>Kategori</th>
                    <th>User</th>
                    <th>Uang Keluar</th>
                    <th>Uang Masuk</th>
                    <!-- <th>Uang Saat Ini</th> -->
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
                        <strong>Kategori</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content category"></p>
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
    ajax: '{{ route("admin.datatables.accountings") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'category',
        name: 'category'
      },
      {
        data: 'user',
        name: 'user'
      },
      {
        data: 'amount_out',
        name: 'amount_out'
      },
      {
        data: 'amount_in',
        name: 'amount_in'
      },
      // {
      //   data: 'current_amount',
      //   name: 'current_amount'
      // },
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

    $.ajax({
        url: "{{ route('admin.accountings.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var item = response.data;

            $('.detail-content.category').text(item.category.name);
            $('.detail-content.user').text(item.user.username);
            $('.detail-content.amount-out').text(`Rp ${toCurrency(item.amount_out)}`);
            $('.detail-content.amount-in').text(`Rp ${toCurrency(item.amount_in)}`);
            // $('.detail-content.current-amount').text(`Rp ${toCurrency(item.current_amount)}`);
            $('.detail-content.description').text(item.description);
            $('.detail-content.date').text(item.created_at);

            $('#modal-detail').modal({ show: true });
        },
        error: function() {
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
