@extends('admin.master')

@section('title', 'RTL - Produk')

@section('content')
<div class="section-header">
  <h1>Produk</h1>
  <div class="section-header-breadcrumb">
    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></div>
    <div class="breadcrumb-item">Produk</div>
  </div>
</div>

<div class="section-body">
  <h2 class="section-title">Produk</h2>
  <p class="section-lead">
    Daftar produk
  </p>

  <div class="row">
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
        <form>
          <div class="card-header">
            <h4>Produk</h4>
          </div>
          <div class="card-body">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah</a>
            <a href="{{ route('admin.products.trash') }}" class="btn btn-danger">Sampah</a>
            <div class="text-right">
              <label for="filter">Filter Produk</label><br/>
              <select id="filter" onchange="window.location.href=$(this).val();">
                <option value="">-- Pilih Filter --</option>
                @if($filter == "no-price")
                <option value="?filter=no-price" selected>Belum Ada Harga</option>
                @else
                <option value="?filter=no-price">Belum Ada Harga</option>
                @endif
                @if($filter == "no-image")
                <option value="?filter=no-image" selected>Belum Ada Gambar</option>
                @else
                <option value="?filter=no-image">Belum Ada Gambar</option>
                @endif
                @if($filter == "no-publish")
                <option value="?filter=no-publish" selected>Tidak Dipublikasi</option>
                @else
                <option value="?filter=no-publish">Tidak Dipublikasi</option>
                @endif
                @if($filter == "yes-publish")
                <option value="?filter=yes-publish" selected>Dipublikasi</option>
                @else
                <option value="?filter=yes-publish">Dipublikasi</option>
                @endif
              </select>
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
                    <th>Judul</th>
                    <th>Stok</th>
                    <th>Harga Seri</th>
                    <th>Berat</th>
                    <th>Publikasi</th>
                    <th>Pre Order</th>
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
  var filter = '{{ $filter }}';
  var table = $('#table').DataTable({
    ajax: '{{ route("admin.datatables.products", ['filter' => '']) }}/' + filter,
    initComplete:function(settings, json){
      $('select[name=is_published] option[selected]').prop('selected', true);
      $('select[name=is_open_preorder] option[selected]').prop('selected', true);
    },
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
        data: 'title',
        name: 'title'
      },
      {
        data: 'current_total_stock_warehouse',
        name: 'current_total_stock_warehouse',
        searchable: false,
        orderable: false
      },
      {
        data: 'price_sell_seri',
        name: 'price_sell_seri',
        searchable: false,
        orderable: false
      },
      {
        data: 'weight',
        name: 'weight',
        searchable: false,
        orderable: false
      },
      {
        data: 'is_published',
        name: 'is_published',
        searchable: false,
        orderable: false
      },
      {
        data: 'is_open_preorder',
        name: 'is_open_preorder',
        searchable: false,
        orderable: false
      },
    ],
    columnDefs: [
        {
            targets: 3,
            render: function(data, type, row) {
                var stocks = `
                    Gudang (${row.current_total_stock_warehouse})
                    Toko (${row.current_total_stock_store})
                    Shopee (${row.current_total_stock_shopee})
                `;

                return stocks;
            },
        },
        {
            targets: 6,
            render: function(data, type, row) {
                var publishTime = '{{ $publishTime }}';
                var url = "{{ route('admin.products.publish.update', ['id' => 'ID_HERE']) }}";
                url = url.replace(/ID_HERE/g, row.id);

                var statusSelection = `
                    <form action="${url}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <select name="is_published" class="form-control" style="width:96px" data-alias="publikasi">
                            <option value="1" ${data == 1 ? 'selected' : ''}>Ya</option>
                            <option value="0" ${data == 0 ? 'selected' : ''}>Tidak</option>
                            <option value="2" ${data == 2 ? 'selected' : ''}>Jadwal (${publishTime})</option>
                        </select>
                    </form>
                `;

                return statusSelection;
            },
        },
        {
            targets: 7,
            render: function(data, type, row) {
                var url = "{{ route('admin.products.preorder.update', ['id' => 'ID_HERE']) }}";
                url = url.replace(/ID_HERE/g, row.id);

                var statusSelection = `
                    <form action="${url}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <select name="is_open_preorder" class="form-control" style="width:96px" data-alias="preorder">
                            <option value="0" ${data == 0 ? 'selected' : ''}>Tidak</option>
                            <option value="1" ${data == 1 ? 'selected' : ''}>Ya</option>
                        </select>
                    </form>
                `;

                return statusSelection;
            },
        },
        {
            targets: 8,
            render: function(data, type, row) {
                var show = '<a href="{{ route("admin.products.show", ["id" => "ID_HERE"]) }}" class="btn btn-info">Detail</a>';
                var edit = '<a href="{{ route("admin.products.edit", ["id" => "ID_HERE"]) }}" class="btn btn-warning">Ubah</a>';
                var remove = '<button type="button" class="btn btn-danger btn-delete">Hapus</button>';
                var form = '<form action="{{ route("admin.products.destroy", ["id" => "ID_HERE"]) }}" method="POST" class="form-inline"><input type="hidden" name="_method" value="DELETE" />{{ csrf_field() }}' + show + '&nbsp;&nbsp;' + edit + '&nbsp;&nbsp;' + remove + '</form>';

                form = form.replace(/ID_HERE/g, row.id);

                return form;
            },
        },
    ],
    rowCallback: function(row, data) {
        $('td:eq(6)', row).find('select').val(data.is_published);
        $('td:eq(7)', row).find('select').val(data.is_open_preorder);
    }
  });

  $('table').on('change', 'select', function() {
    var url = $(this).parent().attr('action');
      var token = '{{ csrf_token() }}';
      var value = Number($(this).val());
      var alias = $(this).data('alias');
      var name = $(this).attr('name');

      $('#spinner').show();
      $('.table-responsive').hide();

      $.ajax({
          method: "PUT",
          url: url,
          data: {
            _token: token,
            [name]: value
          },
          success: function(response) {
              iziToast.success({
                  title: 'Berhasil!',
                  message: `Berhasil mengubah status ${alias} produk`,
                  position: 'topRight'
              });

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: `Gagal mengubah status ${alias} produk`,
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
                  message: 'Berhasil menghapus produk',
                  position: 'topRight'
              });

              table.ajax.reload();

              $('#spinner').hide();
              $('.table-responsive').show();
          },
          error: function(e) {
              iziToast.error({
                  title: 'Gagal!',
                  message: 'Gagal menghapus produk',
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
