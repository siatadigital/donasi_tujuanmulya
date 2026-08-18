@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Nama Referrer</th>
						<th style="width: 15%;">Kode</th>
						<th>Nama Project</th>
						<th>Total Donatur</th>
						<th>Dana Terkumpul</th>
					</tr>
				</thead>
			</table>

		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop

@section('scripts')
<script>
$(document).ready(function() {
	var table = $('#datatable').DataTable({
    ordering: false,
    searching: false,
    processing: true,
    serverSide: true,
    ajax: {
      url:'{{ route("admin.referral.getJsonProject") }}',
    },
    columns: [
      {
        data:'referrer_name',
        name:'referrer_name'
      },
      {
        data:'code_referral',
        name:'code_referral'
      },
      {
        data:'project_name',
        name:'project_name'
      },
      {
        data:'total_donors',
        name:'total_donors'
      },
      {
        data:'total_amount',
        name:'total_amount'
      },
    ]
  });
});
</script>
@stop