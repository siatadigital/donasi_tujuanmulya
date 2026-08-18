@extends('admin.master')

@section('title', 'RTL - Sampah Kupon')

@section('content')
<div class="section-header">
  <h1>Sampah Kupon</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.coupons.index') }}">Kupon</a></div>
    <div class="breadcrumb-item">Sampah Kupon</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Sampah Kupon</h2>
  <p class="section-lead">
    Daftar sampah kupon
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Sampah Kupon</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.coupons.trash.deleteAll') }}" method="POST" id="form-delete-all">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-danger" id="btn-delete-all">Hapus Semua</button>
          </form>
          <br><br>
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
                  <th width="160px">Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
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
  ajax: '{{ route("admin.datatables.trashes", ["type" => "coupons"]) }}',
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
      var edit =
        '<a href="{{ route("admin.coupons.trash.restore", ["id" => "ID_HERE"]) }}" class="btn btn-warning btn-restore">Pulihkan</a>';
      var remove = '<button class="btn btn-danger btn-delete">Hapus</button>';
      var form =
        '<form action="{{ route("admin.coupons.trash.delete", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline form-delete">{{ method_field("DELETE") }}{{ csrf_field() }}' +
        edit + '&nbsp;&nbsp;' + remove + '</form>';

      form = form.replace(/ID_HERE/g, row.id);

      return form;
    },
  }, ],
});

$('#table').on('click', '.btn-restore', function(event) {
  event.preventDefault();

  var link = $(this).prop('href');

  swal({
      title: 'Apa anda yakin?',
      text: 'Item ini akan dipulihkan.',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then(function(willRestore) {
      if (willRestore) {
        window.location.href = link;
      }
    });
});

$('#table').on('click', '.btn-delete', function(event) {
  event.preventDefault();

  var $form = $(this).parents('.form-delete');

  swal({
      title: 'Apa anda yakin?',
      text: 'Item ini akan dihapus secara permanen.',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then(function(willDelete) {
      if (willDelete) {
        $form.submit();
      }
    });
});

$('#btn-delete-all').on('click', function(event) {
  event.preventDefault();

  var $form = $(this).parents('#form-delete-all');

  console.log($(this).parents());

  swal({
      title: 'Apa anda yakin?',
      text: 'Semua item ini akan dihapus secara permanen.',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then(function(willDelete) {
      if (willDelete) {
        $form.get(0).submit();
      }
    });
});
</script>
@endsection
