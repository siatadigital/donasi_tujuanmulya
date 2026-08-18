@extends('admin.master')

@section('title', 'RTL - Kupon')

@section('content')
<div class="section-header">
  <h1>Kupon</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Kupon</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Kupon</h2>
  <p class="section-lead">
    Daftar kupon
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Kupon</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">Tambah</a>
            <a href="{{ route('admin.coupons.trash') }}" class="btn btn-danger">Sampah</a>
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
                    <th>Nama</th>
                    <th>Kode</th>
                    <th>Diskon</th>
                    <th>Kadaluarsa</th>
                    <th width="48px">Aktif</th>
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
    ajax: '{{ route("admin.datatables.coupons") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'name',
        name: 'name'
      },
      {
        data: 'code',
        name: 'code'
      },
      {
        data: 'discount',
        name: 'discount',
        searchable: false,
        orderable: false
      },
      {
        data: 'expired_at',
        name: 'expired_at'
      },
      {
        data: 'active',
        name: 'active',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [{
      targets: 6,
      render: function(data, type, row) {
        var edit = '<a href="{{ route("admin.coupons.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.coupons.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + edit + '&nbsp;&nbsp;' + remove + '</form>';

        form = form.replace(/ID_HERE/g, row.id);

        return form;
      },
    }, ],
  });

  $('table').on('click', '.btn-delete', function() {
      var url = $(this).parent().attr('action');
      var token = '{{ csrf_token() }}';
      var value = Number($(this).val());

      $('#spinner').show();
      $('.table-responsive').hide();

      $.ajax({
          method: "DELETE",
          url: url,
          data: {
            _token: token,
          },
          success: function(response) {
              iziToast.success({
                  title: 'Berhasil!',
                  message: 'Berhasil menghapus kupon',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus kupon',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          }
      });
  });
</script>
@endsection
