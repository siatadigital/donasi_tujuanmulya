@extends('admin.master')

@section('title', 'RTL - Ongkos Kirim')

@section('content')
<div class="section-header">
  <h1>Ongkos Kirim</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.couriers.index') }}">Kurir</a></div>
    <div class="breadcrumb-item">Ongkos Kirim</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Ongkos Kirim</h2>
  <p class="section-lead">
    Daftar ongkos kirim milik <strong>{{ $courier->name }}</strong>
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Ongkos Kirim</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.couriers.costs.create', ['id' => $id]) }}" class="btn btn-primary">Tambah</a>
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
                    <th>Provinsi</th>
                    <th>Kota</th>
                    <th>Kecamatan</th>
                    <th>Biaya / kg</th>
                    <th>Berat Min (kg)</th>
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
    ajax: '{{ route("admin.datatables.courier-costs", ["id" => $id]) }}',
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'province',
        name: 'province'
      },
      {
        data: 'city',
        name: 'city'
      },
      {
        data: 'subdistrict',
        name: 'subdistrict'
      },
      {
        data: 'cost',
        name: 'cost'
      },
      {
        data: 'min',
        name: 'min'
      },
    ],
    columnDefs: [{
      targets: 6,
      render: function(data, type, row) {
        var edit = '<a href="{{ route("admin.couriers.costs.edit", ["id" => $id, "costId" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
        var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
        var form = '<form action="{{ route("admin.couriers.costs.delete", ["id" => $id, "costId" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}'+ edit + '&nbsp;&nbsp;' + remove + '</form>';

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
                  message: 'Berhasil menghapus ongkir',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus ongkir',
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
