@extends('admin.master')

@section('title', 'RTL - Customer')

@section('content')
<div class="section-header">
  <h1>Customer</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Customer</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Customer</h2>
  <p class="section-lead">
    Daftar customer
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Customer</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Tambah</a>
            <a href="{{ route('admin.customers.trash') }}" class="btn btn-danger">Sampah</a>
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
                    <th>Username</th>
                    <th>Aktif</th>
                    <th>Reseller</th>
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
    processing: true,
    serverSide: true,
    ajax: '{{ route("admin.datatables.customers") }}',
    initComplete:function(settings, json){
      $('select[name=is_active] option[selected]').prop('selected', true);
    },
    columns: [{
        data: 'id',
        name: 'id'
      },
      {
        data: 'fullname',
        name: 'fullname'
      },
      {
        data: 'username',
        name: 'username'
      },
      {
        data: 'active',
        name: 'active',
        searchable: false,
        orderable: false
      },
      {
        data: 'reseller',
        name: 'reseller',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [
        {
            targets: 3,
            render: function(data, type, row) {
                var url = "{{ route('admin.customers.active.update', ['id' => 'ID_HERE']) }}";
                url = url.replace(/ID_HERE/g, row.id);

                var statusSelection = `
                    <form action="${url}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <select name="is_active" class="form-control" style="width:96px">
                            <option value="0" ${data == 0 ? 'selected' : ''}>Tidak</option>
                            <option value="1" ${data == 1 ? 'selected' : ''}>Ya</option>
                        </select>
                    </form>
                `;

                return statusSelection;
            },
        },
        {
            targets: 5,
            render: function(data, type, row) {
                var show = '<a href="{{ route("admin.customers.show", ["id" => "ID_HERE"]) }}" class="btn btn-info">Detail</a>';
                var edit = '<a href="{{ route("admin.customers.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
                var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
                var form = '<form action="{{ route("admin.customers.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + show + '&nbsp;&nbsp;' + edit + '&nbsp;&nbsp;' + remove + '</form>';

                form = form.replace(/ID_HERE/g, row.id);

                return form;
            },
        },
    ],
    rowCallback: function(row, data) {
        $('td:eq(3)', row).find('select').val(data.is_active);
    }
  });

  $('table').on('change', 'select', function() {
      var url = $(this).parent().attr('action');
      var token = '{{ csrf_token() }}';
      var value = Number($(this).val());

      $('#spinner').show();
      $('.table-responsive').hide();

      $.ajax({
          method: "PUT",
          url: url,
          data: {
            _token: token,
            is_active: value
          },
          success: function(response) {
              iziToast.success({
                  title: 'Berhasil!',
                  message: 'Berhasil mengubah status aktif customer',
                  position: 'topRight'
              });

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal mengubah status aktif customer',
                  position: 'topRight'
              });

              $('#spinner').hide();
              $('.table-responsive').show();
          }
      });
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
                  message: 'Berhasil menghapus customer',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus customer',
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
