@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">

			<div class="clearfix"></div>
			<br>

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Type</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $notif->isEmpty() )
				@foreach($notif as $u)
					<tr>
						<td>{{ $u['type'] }}</td>
						<td>
							@if (isPermitted('admin.popup.getEditPopupTransaksi'))
							<a href="{{ route('admin.popup.getEditPopupTransaksi', $u['id']) }}" class="btn btn-info btn-sm">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
							@endif
						</td>
					</tr>
				@endforeach
				@else
					<tr>
						<td colspan="5" class="text-center">
							No data
						</td>
					</tr>
				@endif

				</tbody>
			</table>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
