@extends('admin::layouts.default')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
@stop

@section('content')

<div class="nav-tabs-custom">

  <div class="tab-content">
    <br />
    @if(Route::current()->getName() == "admin.crm.getSuccessTransaksi")
    <form action="{{ route("admin.crm.getSuccessTransaksiExport") }}" method="post">
      @elseif(Route::current()->getName() == "admin.crm.getPendingTransaksi")
      <form action="{{ route("admin.crm.getPendingTransaksiExport") }}" method="post">
        @elseif(Route::current()->getName() == "admin.crm.getExpiredTransaksi")
        <form action="{{ route("admin.crm.getExpiredTransaksiExport") }}" method="post">
          @endif
          {{ csrf_field() }}
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="projects">Tanggal</label>
								<div class="input-daterange row">
									<div class="col-md-6">
										<input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date"
											readonly />
									</div>
									<div class="col-md-6">
										<input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="projects">Pencarian</label>
								<div class="row">
									<div class="col-md-6">
										<input type="text" name="cari" id="cari" class="form-control" placeholder="Search" />
									</div>
									<div class="col-md-6">
										<select name="type_cari" class="form-control" id="type_cari">
											<option value="Pilih Tipe Cari">Pilih Tipe Cari</option>
											<option value="Nama Pemberi Infak">Nama Pemberi Infak</option>
											<option value="No. WhatsApp">No. WhatsApp</option>
											<option value="Bank Tujuan">Bank Tujuan</option>
											<option value="Nominal/Kode Unik">Nominal/Kode Unik</option>
											<option value="Email">Email</option>
											<option value="Kota">Kota</option>
										</select>
									</div>
								</div>
							</div>
						</div>
            <div class="col-md-2">
              <label for="type_akad">&nbsp;</label>
              <select name="type_akad" class="form-control" id="type_akad">
                <option value="Pilih Tipe Akad">Pilih Tipe Akad</option>
                <option value="Infak Terikat" @if(request('type_akad') == 'Infak Terikat') selected @endif>
                  Infak Terikat
                </option>
                <option value="Infak Umum" @if(request('type_akad') == 'Infak Umum') selected @endif>
                  Infak Umum
                </option>
                <option value="Zakat" @if(request('type_akad') == 'Zakat') selected @endif>
                  Zakat
                </option>
              </select>
            </div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="projects">Nama Campaign</label>
								<select name="project_id" id="projects" class="form-control">
									<option value="">Semua</option>
                  @foreach ($projects as $project)
                  <option value="{{ $project->id }}">
                    {{ $project->title }}
                  </option>
                  @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="age">Usia</label>
								<input type="number" id="age" name="age" class="form-control" placeholder="Usia" min="1" />
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="member-statuses">Status Member</label>
								<select name="member_status" id="member-statuses" class="form-control">
									<option value="">Semua</option>
									<option value="member">Member</option>
									<option value="non-member">Non Member</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="genders">Jenis Kelamin</label>
								<select name="gender" id="genders" class="form-control">
									<option value="">Semua</option>
									<option value="male">Pria</option>
									<option value="female">Wanita</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group">
								<label for="genders">Fundraiser</label>
								<select name="fundraiser_id" id="fundraisers" class="form-control">
									<option value="">Semua</option>
                  @foreach ($fundraisers as $fundraiser)
                  <option value="{{ $fundraiser->id }}">
                    {{ $fundraiser->title }}
                  </option>
                  @endforeach
								</select>
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="genders">Kategori Campaign</label>
                <select name="category_ids" class="form-control select2" multiple="multiple" data-placeholder="Pilih kategori" id="categories">
                  @foreach($categories as $category)
                    <option value="{{ $category['id'] }}" @if(!empty(request('category_ids')) && in_array($category['id'], request('category_ids'))) selected @endif>
                      {{ $category['category_name'] }}
                    </option>
                  @endforeach
                </select>
							</div>
						</div>
					</div>
					<div style="margin-top:8px;">
						<a name="filter" id="filter" class="btn btn-primary">Filter</a>
						@if (Route::current()->getName() == "admin.crm.getSuccessTransaksi")
						@if (isPermitted('admin.crm.getSuccessTransaksiExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
						@elseif (Route::current()->getName() == "admin.crm.getPendingTransaksi")
						@if (isPermitted('admin.crm.getPendingTransaksiExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
						@elseif (Route::current()->getName() == "admin.crm.getExpiredTransaksi")
						@if (isPermitted('admin.crm.getExpiredTransaksiExport'))
						<input type="submit" id="export" value="Export Excel" class="btn btn-success">
						@endif
						@endif
						<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
					</div>
        </form>
        <br><br>
        <h2 class="pull-left">{{ $total }}</h2>
        <br><br><br>
        <hr>
        <p class="text-right"><span id="count">0</span> transaksi ditemukan</p>
        <table id="datatable" class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th style="width: 15%;">Nama Pemberi Infak</th>
              <th style="width: 30%;">Details</th>
              <th>Status</th>
              <th>Akad</th>
              <th>Kode Unik</th>
              <th style="width: 10%;">Tanggal</th>
            </tr>
          </thead>
        </table>

      @if (isPermitted('admin.crm.sendMessage'))
      <a href="#" id="btn-send-message" class="btn-send-message" data-toggle="modal" data-target="#modal-send-message">
        <i class="fa fa-send"></i>
      </a>
      @endif
  </div><!-- /.tab-content -->
</div><!-- /.nav-tabs-custom -->


<div class="modal fade" id="modal-send-message" tabindex="-1" role="dialog" aria-labelledby="modalSendMessage">
  <form method="POST" action="{{ route('admin.crm.sendMessage') }}">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="updateTerakhirLabel">Kirim Pesan</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="message">Pesan</label>
            <textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Ketik pesan disini..."></textarea>
          </div>
          <div class="form-group">
            <label for="send-via">Kirim Melalui</label>
            <select name="send_via" id="send-via" class="form-control">
              <option value="email">Email</option>
              <option value="whatsapp">Whatsapp</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btn-send">Kirim</button>
        </div>
      </div>
    </div>
  </form>
</div>
@stop

@section('scripts')
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script>
$(document).ready(function() {
  var selectedItems = [];

  // $(window).keydown(function(event) {
  //   if (event.keyCode == 13) {
  //     event.preventDefault();
  //     $('#filter').click();
  //   }
  // });

  $('.input-daterange').datepicker({
    todayBtn: 'linked',
    format: 'yyyy-mm-dd',
    autoclose: true
  });

  load_data();

  function load_data(criteria = {}) {
    var table = $('#datatable').DataTable({
      "ordering": false,
      "searching": false,
      processing: true,
      serverSide: true,
      ajax: {
        url: '@if(Route::current()->getName() == "admin.crm.getSuccessTransaksi"){{ route("admin.crm.getJsonSuccessTransaksi") }}@elseif(Route::current()->getName() == "admin.crm.getPendingTransaksi") {{ route("admin.crm.getJsonPendingTransaksi") }}@elseif(Route::current()->getName() == "admin.crm.getExpiredTransaksi") {{ route("admin.crm.getJsonExpiredTransaksi") }}@endif',
        data: criteria
      },
      columns: [{
          data: 'id',
          name: 'id',
          render: function() {
            return null;
          },
        },
        {
          data: 'fullname',
          name: 'fullname'
        },
        {
          data: 'details'
        },
        {
          data: 'status_donation'
        },
        {
          data: 'akad'
        },
        {
          data: 'kode_unik'
        },
        {
          data: 'tanggal'
        }
      ],
      columnDefs: [
        {
          orderable: false,
          targets: 0,
          className: 'select-checkbox',
        }
      ],
      select: {
        style: 'multi',
        selector: 'td:first-child',
      },
      drawCallback : function() {
        var info = this.api().page.info();

	      $('#count').text(info.recordsTotal);
      }
    });

    selectedItems = [];
    $('#btn-send-message').css('display', 'none');

    var selectCallback = function(event, dt, type, indexes) {
      if (type !== 'row') return;

      selectedItems = table.rows({selected: true}).data().toArray();

      var isEmpty = !selectedItems.length;
      var displayButton = !isEmpty ? 'flex' : 'none';

      $('#btn-send-message').css('display', displayButton);
    };

    table.on('select', selectCallback);
    table.on('deselect', selectCallback);

    table.on("click", "th.select-checkbox", function() {
        if ($("th.select-checkbox").hasClass("selected")) {
            table.rows().deselect();
            $("th.select-checkbox").removeClass("selected");
        } else {
            table.rows().select();
            $("th.select-checkbox").addClass("selected");
        }
    }).on("select deselect", function() {
        ("Some selection or deselection going on")
        if (table.rows({
                selected: true
            }).count() !== table.rows().count()) {
            $("th.select-checkbox").removeClass("selected");
        } else {
            $("th.select-checkbox").addClass("selected");
        }
    });
  }

  $('#filter').click(function() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var cari = $('#cari').val();
    var type_cari = $('#type_cari').val();
    var type_akad = $('#type_akad').val();
    var projectId = $('#projects').val();
    var age = $('#age').val();
    var area = $('#area').val();
    var memberStatus = $('#member-statuses').val();
    var gender = $('#genders').val();
    var fundraiserId = $('#fundraisers').val();
    var categories = $('#categories').val();

    if (cari && type_cari == 'Pilih Tipe Cari') {
      alert('Pilih Tipe Cari Harus Diisi');
      return;
    }

    $('#datatable').DataTable().destroy();

    load_data({
      from_date: from_date,
      to_date: to_date,
      cari: cari,
      type_cari: type_cari,
      type_akad: type_akad,
      project_id: projectId,
      age: age,
      area: area,
      member_status: memberStatus,
      gender: gender,
      fundraiser_id: fundraiserId,
      category_ids: categories,
    });
  });

  $('#export').click(function() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var cari = $('#cari').val();
    var type_cari = $('#type_cari').val();
    var type_akad = $('#type_akad').val();

    if (from_date == '' && to_date == '' && cari != '' && type_cari == 'Pilih Tipe Cari') {
      alert('Pilih Tipe Cari Harus Diisi');
      return false;
    } else if (from_date != '' && to_date != '' && cari != '' && type_cari == 'Pilih Tipe Cari') {
      alert('Pilih Tipe Cari Harus Diisi');
      return false;
    }
  });

  $('#refresh').click(function() {
    $('#from_date').val('');
    $('#to_date').val('');
    $('#cari').val('');
    $('#type_cari').val('Pilih Tipe Cari');
    $('#type_akad').val('Pilih Tipe Akad');
    $('#projects').val('');
    $('#age').val('');
    $('#area').val('');
    $('#member-statuses').val('');
    $('#genders').val('');
    $('#fundraisers').val('');
    $('#datatable').DataTable().destroy();
    load_data();
  });

  $('#btn-send').click(function() {
    var url = $(this).parents('form').attr('action');
    var message = $('#message').val();
    var sendVia = $('#send-via').val();
    var $btnSend = $(this);

    var fullnames = selectedItems.map(item => item.fullname);

    var contacts = selectedItems.map(item => {
      return sendVia === 'email' ? item.email : item.phone;
    });

    $btnSend.prop('disabled', true);

    $.ajax({
			type: "POST",
      url: url,
      data: {
        _token: '{{ csrf_token() }}',
        fullnames: fullnames,
        contacts: contacts,
        message: message,
        send_via: sendVia,
      },
			success: function() {
        $btnSend.prop('disabled', false);
        $('#modal-send-message').modal('hide');
        alert(`Pesan berhasil dikirim melalui ${sendVia.toUpperCase()} !`);
			},
			error: function() {
        $btnSend.prop('disabled', false);
        $('#modal-send-message').modal('hide');
				alert(`Maaf, Gagal mengirim pesan melalui ${sendVia.toUpperCase()} !`);
			}
		});
  });
});
</script>
@stop
