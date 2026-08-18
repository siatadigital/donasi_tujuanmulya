@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<br />
			{{ csrf_field() }}
			<div class="row">
				<div class="input-daterange">
					<div class="col-md-2">
						<input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
					</div>
					<div class="col-md-2">
						<input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
					</div>
				</div>
				<div class="col-md-2">
					<input type="text" name="cari" id="cari" class="form-control" placeholder="Search" />
				</div>
				<div class="col-md-2">
					<select name="type_cari" class="form-control" id="type_cari" >
						<option value="Pilih Tipe Cari">Pilih Tipe Cari</option>
						<option value="Nama Pemberi Infak">Nama Project</option>
						<option value="Bank Tujuan">Bank Tujuan</option>
						<option value="Nominal">Nominal</option>
					</select>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-8">
					<select name="category_ids" class="form-control select2" multiple="multiple" data-placeholder="Pilih kategori" id="categories">
						@foreach($categories as $category)
							<option value="{{ $category['id'] }}" @if(!empty(request('category_ids')) && in_array($category['id'], request('category_ids'))) selected @endif>
								{{ $category['category_name'] }}
							</option>
						@endforeach
					</select>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-4">
					<a name="filter" id="filter" class="btn btn-primary">Filter</a>
					<a name="refresh" id="refresh" class="btn btn-default">Refresh</a>
				</div>
			</div>
      <br><br>
			<h2 class="pull-left">{{ $total }}</h2>
			<br><br><br><hr>
			<p class="text-right"><span id="count">0</span> transaksi ditemukan</p>
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Nama Project</th>
						<th>Details</th>
						<th width="96px;">Status</th>
						<th style="width: 10%;">Tanggal</th>
						<th width="128px"></th>
					</tr>
				</thead>
			</table>

		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop

@section('scripts')
<script>
$(document).ready(function(){
	$(window).keydown(function(event){
		if(event.keyCode == 13) {
			event.preventDefault();
			$('#filter').click();
		}
	});

	$('.input-daterange').datepicker({
		todayBtn:'linked',
		format:'yyyy-mm-dd',
		autoclose:true
	});

	load_data();

function load_data(from_date = '', to_date = '', cari = '', type_cari = '', category_ids = '')
 {
	var table = $('#datatable').DataTable({
        "ordering": false,
        "searching": false,
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ $datatableUrl }}",
			data:{
				from_date: from_date,
				to_date: to_date,
				cari: cari,
				type_cari: type_cari,
				category_ids: category_ids,
			}
		},
		columns: [
			{
				data:'project_name',
				name:'project_name'
			},
			{
				data:'details',
				name:'details'
			},
			{
				data:'status',
				name:'status'
			},
			{
				data:'tanggal',
				name:'tanggal'
			},
		],
		columnDefs: [
			{
				targets: 4,
				render: function ( data, type, row ) {

					@if (isPermitted('admin.withdraw.putRejectWithdraw'))
					var reject = '{!! Form::link("Reject", 'PUT', route('admin.withdraw.putRejectWithdraw', ['id' => 'ID']), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], "are you sure reject ?") !!}';
					@endif

					@if (isPermitted('admin.withdraw.putSuccessWithdraw'))
					var accept = '{!! Form::link("Accept", 'PUT', route('admin.withdraw.putSuccessWithdraw', ['id' => 'ID']), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], "are you sure to confirm ?") !!}';
					@endif

					reject = reject.replace('ID', row.id);
					accept = accept.replace('ID', row.id);

					@if(Route::current()->getName() == "admin.withdraw.getPendingWithdraw")
					return reject + "&nbsp;&nbsp;" + accept;
					@else
					return '';
					@endif
        }
			},
		],
		drawCallback : function() {
			var info = this.api().page.info();

			$('#count').text(info.recordsTotal);
		}
	});
 }

 $('#filter').click(function(){
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		var cari = $('#cari').val();
		var type_cari = $('#type_cari').val();
		var categories = $('#categories').val();

		if(from_date == '' &&  to_date == '' && cari != '' && type_cari == 'Pilih Tipe Cari'){
			alert('Pilih Tipe Cari Harus Diisi');
		}else if(from_date != '' &&  to_date != '' && cari != '' && type_cari == 'Pilih Tipe Cari'){
			alert('Pilih Tipe Cari Harus Diisi');
		}else if(from_date == '' &&  to_date == '' && cari != '' && type_cari != 'Pilih Tipe Cari'){
			$('#datatable').DataTable().destroy();
			load_data(from_date, to_date, cari, type_cari, categories);
		}
		else if(from_date != '' &&  to_date != '' &&  cari == '' &&  type_cari == 'Pilih Tipe Cari')
		{
			$('#datatable').DataTable().destroy();
			load_data(from_date, to_date, cari, type_cari, categories);
		}else if(from_date != '' &&  to_date != '' && cari != '' && type_cari != 'Pilih Tipe Cari'){
			$('#datatable').DataTable().destroy();
			load_data(from_date, to_date, cari, type_cari, categories);
		}else if(categories != ''){
			$('#datatable').DataTable().destroy();
			load_data('', '', '', '', categories);
		}
	});

	$('#refresh').click(function(){
		$('#from_date').val('');
		$('#to_date').val('');
		$('#cari').val('');
		$('#type_cari').val('Pilih Tipe Cari');
		$('#datatable').DataTable().destroy();
		load_data();
	});
});
</script>
@stop
