@extends('admin.master')

@section('title', 'RTL - Halaman')

@section('content')
<div class="section-header">
  <h1>Halaman</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Halaman</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Halaman</h2>
  <p class="section-lead">
    Daftar halaman
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Halaman</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Tambah</a>
            <a href="{{ route('admin.pages.trash') }}" class="btn btn-danger">Sampah</a>
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
                    <th width="32px" class="text-center">
                      #
                    </th>
                    <th width="96px">Foto</th>
                    <th>Judul</th>
                    <th>Publikasi</th>
                    <th width="200px">Aksi</th>
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
    ajax: '{{ route("admin.datatables.pages") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'photo',
        name: 'photo'
      },
      {
        data: 'title',
        name: 'title'
      },
      {
        data: 'published',
        name: 'published',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [{
      targets: 4,
      render: function(data, type, row) {
        var view = '<a href="{{ route("frontend.page.detail", ["slug" => "SLUG_HERE"]) }}" class="btn btn-primary" target="_blank">Lihat</a>';
        var edit = '<a href="{{ route("admin.pages.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.pages.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + view + '&nbsp;&nbsp;' + edit + '&nbsp;&nbsp;' + remove + '</form>';

        form = form.replace(/ID_HERE/g, row.id);
        form = form.replace(/SLUG_HERE/g, row.slug);

        return form;
      },
    }, ]
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
                  message: 'Berhasil menghapus halaman',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus halaman',
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
