@extends('admin.master')

@section('title', 'RTL - Sampah Bank')

@section('content')
<div class="section-header">
  <h1>Sampah Bank</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item active"><a href="{{ route('admin.banks.index') }}">Bank</a></div>
    <div class="breadcrumb-item">Sampah Bank</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Sampah Bank</h2>
  <p class="section-lead">
    Daftar sampah bank
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4>Sampah Bank</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.banks.trash.deleteAll') }}" method="POST" id="form-delete-all">
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
                  <th>Logo Bank</th>
                  <th>Nama Bank</th>
                  <th>Nama Pemilik</th>
                  <th>No. Rekening</th>
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
  ajax: '{{ route("admin.datatables.trashes", ["type" => "banks"]) }}',
  columns: [{
      data: 'id',
      name: 'id'
    },
    {
      data: 'bank_logo',
      name: 'bank_logo'
    },
    {
      data: 'bank_name',
      name: 'bank_name'
    },
    {
      data: 'account_name',
      name: 'account_name'
    },
    {
      data: 'account_number',
      name: 'account_number'
    },
  ],
  columnDefs: [{
    targets: 5,
    render: function(data, type, row) {
      var edit =
        '<a href="{{ route("admin.banks.trash.restore", ["id" => "ID_HERE"]) }}" class="btn btn-warning btn-restore">Pulihkan</a>';
      var remove = '<button class="btn btn-danger btn-delete">Hapus</button>';
      var form =
        '<form action="{{ route("admin.banks.trash.delete", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline form-delete">{{ method_field("DELETE") }}{{ csrf_field() }}' +
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
