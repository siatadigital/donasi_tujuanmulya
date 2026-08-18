@extends('admin.master')

@section('title', 'RTL - Pengeluaran Ongkir')

@section('content')
<div class="section-header">
  <h1>Pengeluaran Ongkir</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Pengeluaran Ongkir</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Pengeluaran Ongkir</h2>
  <p class="section-lead">
    Daftar Pengeluaran Ongkir
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Pengeluaran Ongkir</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.courier-cost-expenses.create') }}" class="btn btn-primary">Tambah</a>
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
                    <th>Ongkir</th>
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
                        <strong>Ongkir</strong>
                    </div>
                    <div class="col-md-8">
                        <p class="detail-content amount-out"></p>
                    </div>
                </div>
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
    ajax: '{{ route("admin.datatables.courier-cost-expenses") }}',
    columns: [{
        data: 'id',
        name: 'id'
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
        data: 'created_at',
        name: 'created_at'
      },
    ],
    columnDefs: [{
      targets: 4,
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
        url: "{{ route('admin.courier-cost-expenses.show', ['id' => '']) }}/" + itemId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            var item = response.data;

            $('#spinner').hide();
            $('.table-responsive').show();
            $('.detail-content.user').text(item.user.username);
            $('.detail-content.amount-out').text(`Rp ${toCurrency(item.amount_out)}`);
            $('.detail-content.description').text(item.description);
            $('.detail-content.date').text(item.created_at);

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
