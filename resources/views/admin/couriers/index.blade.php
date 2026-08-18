@extends('admin.master')

@section('title', 'RTL - Kurir')

@section('content')
<div class="section-header">
  <h1>Kurir</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Kurir</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Kurir</h2>
  <p class="section-lead">
    Daftar kurir
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Kurir</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.couriers.create') }}" class="btn btn-primary">Tambah</a>
            <a href="{{ route('admin.couriers.trash') }}" class="btn btn-danger">Sampah</a>
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Aktif</th>
                    <th width="225px">Aksi</th>
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
    ajax: '{{ route("admin.datatables.couriers") }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'code',
        name: 'code'
      },
      {
        data: 'name',
        name: 'name'
      },
      {
        data: 'active',
        name: 'active',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [{
      targets: 4,
      render: function(data, type, row) {
        var show = '<a href="{{ route("admin.couriers.costs.index", ["id" => "ID_HERE"]) }}" class="btn btn-info">Ongkos</a>';
        var edit = '<a href="{{ route("admin.couriers.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.couriers.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}'+ show + '&nbsp;&nbsp;' + edit + '&nbsp;&nbsp;' + remove + '</form>';

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
                  message: 'Berhasil menghapus kurir',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus kurir',
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
