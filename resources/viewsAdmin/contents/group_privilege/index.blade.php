@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			@if (isPermitted('admin.group_privilege.createGroupPrivilege'))
			<a href="{{ url('/backend/group_privilege/create') }}" class="btn btn-success pull-right">Create New</a><br><br><br>
			@endif
			<table id="datatable" class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Title</th>
						<th>Description</th>
						<th width="20%"> </th>
					</tr>
				</thead>
				<tbody>

				@foreach($data as $item)
					<tr>
						<td>{{ $item->title }}</td>
						<td>
							{{ $item->description }}
						</td>
						<td>
							@if (isPermitted('admin.group_privilege.editGroupPrivilege'))
							<a href="{{ url('/backend/group_privilege/edit/'.$item->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
							@endif
							@if (isPermitted('admin.group_privilege.deleteGroupPrivilege'))
							<a href="{{ url('/backend/group_privilege/delete/'.$item->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</a>
							@endif
						</td>
					</tr>
				@endforeach

				</tbody>
			</table>

		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
@section('scripts')
<script>
$(document).ready(function(){
	$('#datatable').DataTable({
        "ordering": false,
        "searching": false,
	});
});
</script>
@stop