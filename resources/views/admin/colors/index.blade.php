@extends('admin.master')

@section('title', 'RTL - Warna')

@section('content')
<div class="section-header">
  <h1>Warna</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Warna</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Warna</h2>
  <p class="section-lead">
    Daftar warna
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Warna</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">Tambah</a>
            <a href="{{ route('admin.colors.trash') }}" class="btn btn-danger">Sampah</a>
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
                    <th>Kode Hex</th>
                    <th width="48px">Warna</th>
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
    ajax: '{{ route("admin.datatables.colors") }}',
    columns: [{
        data: 'id',
        name: 'id',
        searchable: false,
        orderable: false
      },
      {
        data: 'name',
        name: 'name'
      },
      {
        data: 'hex_code',
        name: 'hex_code',
        searchable: false,
        orderable: false
      },
      {
        data: 'color',
        name: 'color',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [{
      targets: 4,
      render: function(data, type, row) {
        var edit = '<a href="{{ route("admin.colors.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.colors.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + edit + '&nbsp;&nbsp;' + remove + '</form>';

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
                  message: 'Berhasil menghapus warna',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus warna',
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
