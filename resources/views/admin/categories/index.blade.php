@extends('admin.master')

@section('title', 'RTL - Kategori')

@section('content')
<div class="section-header">
  <h1>Kategori</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Kategori</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Kategori</h2>
  <p class="section-lead">
    Daftar kategori
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Kategori</h4>
          </div>
          <div class="card-body">
            <div class="d-flex">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Tambah</a>
                <a href="{{ route('admin.categories.trash') }}" class="btn btn-danger" style="margin-left:8px;">Sampah</a>
                <a href="{{ route('admin.categories.orders') }}" class="btn btn-primary" style="margin-left:auto;">Urutkan</a>
            </div>
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
                    <th>Kategori Induk</th>
                    <th>Nama</th>
                    <th>Tampilkan di Homepage</th>
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
    ajax: '{{ route("admin.datatables.categories") }}',
    columns: [{
        data: 'id',
        name: 'id',
        searchable: false,
        orderable: false
      },
      {
        data: 'photo',
        name: 'photo',
        searchable: false,
        orderable: false
      },
      {
        data: 'parent',
        name: 'parent',
        searchable: false,
        orderable: false
      },
      {
        data: 'name',
        name: 'name'
      },
      {
        data: 'is_featured_home',
        name: 'is_featured_home',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [{
      targets: 5,
      render: function(data, type, row) {
        var view = '<a href="{{ route("frontend.product.list.category", ["slug" => "SLUG_HERE"]) }}" class="btn btn-primary" target="_blank">Lihat</a>';
        var edit = '<a href="{{ route("admin.categories.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.categories.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + view + '&nbsp;&nbsp;' + edit + '&nbsp;&nbsp;' + remove + '</form>';

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
                  message: 'Berhasil menghapus kategori',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus kategori',
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
