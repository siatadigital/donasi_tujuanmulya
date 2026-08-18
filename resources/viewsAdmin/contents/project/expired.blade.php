@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
		<br />
            <div class="row input-daterange">
                <div class="col-md-4">
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
                </div>
                <div class="col-md-4">
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
                </div>
                <div class="col-md-4">
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                </div>
            </div>
            <br />
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Nama Pemberi Infak</th>
						<th>Nama Project/Campaign</th>
						<th style="width: 30%;">Details</th>
						<th>Status</th>
						<th>Kode Unik</th>
						<th style="width: 10%;">Tanggal</th>
						<th> </th>
					</tr>
				</thead>
			</table>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->
  
@stop
@section('scripts')
<script>
$(document).ready(function(){
	$('.input-daterange').datepicker({
		todayBtn:'linked',
		format:'yyyy-mm-dd',
		autoclose:true
	});

	load_data();

function load_data(from_date = '', to_date = '')
 {
	var table = $('#datatable').DataTable({
        "ordering": false,
        "searching": true,
		processing: true,
		serverSide: true,
		ajax: {
			url:'{{ route("admin.project.getExpiredSupporter") }}',
    		data:{from_date:from_date, to_date:to_date}
		},
		columns: [
			{
			data:'fullname',
			name:'fullname'
			},
			{
                data: 'project.title',
				name:'project.title',
                render: function ( data, type, row ) {
                    return '<a href="/backend/project/review/' +row.id+ '">'+data+'</a>';
                }
            },
			{
                data: 'details'
            },
			{
				data:'status_project'
			},
			{
				data:'kode_unik'
			},
			{
				data:'tanggal'
			},
			{
				data:'status',
				name:'status'
			},
			
		],
		columnDefs: [
			{
				targets:6,
				render: function ( data, type, row ) {
					var reject = '{!! Form::link("Reject", 'PUT', route('admin.project.putRejectSupporter', ['id' => 'ID']), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], "are you sure reject ?") !!}';
                    reject = reject.replace('ID', row.id);

					var accept = '{!! Form::link("Accept", 'PUT', route('admin.project.putAcceptSupporter', ['id' => 'ID']), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], "are you sure to confirm ?") !!}';
                    accept = accept.replace('ID', row.id);

					if(data == "accept"){
						return reject;
					}else{
						return accept;
					}
                }
			},
		]
	});

	table.on('order.dt search.dt', () => {
    table.column(0, {
      search: 'applied',
      order: 'applied',
    }).nodes().each((cell, i) => {
      cell.innerHTML = i + 1;
    });
  }).draw();
 }
	
	$('#filter').click(function(){
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();

		if(from_date != '' &&  to_date != '')
		{
			$('#datatable').DataTable().destroy();
			load_data(from_date, to_date);
		}
		else
		{
			alert('Both Date is required');
		}
	});

	$('#refresh').click(function(){
		$('#from_date').val('');
		$('#to_date').val('');
		$('#datatable').DataTable().destroy();
		load_data();
	});
});
</script>
@stop