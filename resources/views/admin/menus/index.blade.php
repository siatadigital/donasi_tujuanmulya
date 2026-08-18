@extends('admin.master')

@section('title', 'RTL - Menu')

@section('content')
<div class="section-header">
  <h1>Menu</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Menu</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Menu</h2>
  <p class="section-lead">
    Daftar menu
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Menu</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Tambah</a>
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
                    <th>Judul</th>
                    <th>Menu Induk</th>
                    <th>Level</th>
                    <th>Posisi</th>
                    <th>Tipe</th>
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
    ajax: '{{ route("admin.datatables.menus") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'title',
        name: 'title'
      },
      {
        data: 'parent',
        name: 'parent',
        searchable: false,
        orderable: false
      },
      {
        data: 'level',
        name: 'level'
      },
      {
        data: 'position',
        name: 'position'
      },
      {
        data: 'type',
        name: 'type'
      },
    ],
    columnDefs: [{
      targets: 6,
      render: function(data, type, row) {
        var edit = '<a href="{{ route("admin.menus.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.menus.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + edit + '&nbsp;&nbsp;' + remove + '</form>';

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
                  message: 'Berhasil menghapus menu',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus menu',
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
