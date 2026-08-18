@extends('admin.master')

@section('title', 'RTL - Poin Customer')

@section('content')
<div class="section-header">
  <h1>Poin Customer</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Poin Customer</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Poin Customer</h2>
  <p class="section-lead">
    Daftar poin customer
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Poin Customer</h4>
          </div>
          <div class="card-body">
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
                    <th>Jumlah Poin</th>
                    <th width="140px">Tanggal Berlaku</th>
                    <th width="140px">Tanggal Kadaluarsa</th>
                    <th>Aksi</th>
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
    ajax: '{{ route("admin.datatables.customer-points") }}',
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
        data: 'total_points',
        name: 'total_points',
        searchable: false,
        orderable: false
      },
      {
        data: 'valid_from',
        name: 'valid_from',
        searchable: false,
        orderable: false
      },
      {
        data: 'expired_at',
        name: 'expired_at',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [
        {
            targets: 6,
            render: function(data, type, row) {
                var show = '<a href="{{ route("admin.customer-points.show", ["id" => "ID_HERE"]) }}" class="btn btn-info">Detail</a>';

                show = show.replace(/ID_HERE/g, row.id);

                return show;
            },
        },
    ]
  });
</script>
@endsection
