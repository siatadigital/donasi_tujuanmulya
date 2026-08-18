@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">

			@if (isPermitted('event.getCreate'))
			<a href="{{ route('event.getCreate') }}" class="btn btn-success pull-right">Write Event</a>
			@endif
			<div class="clearfix"></div>
			<br>

			{!! $events->render() !!}

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Title</th>
						<th>HTM</th>
						<th>Schedule</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $events->isEmpty() )
				@foreach($events as $u)
					<tr>
						<td>
							<a href="{{ route('event.getShow', $u['slug']) }}" target="_blank">
								{{ strlen($u['title']) > 70 ? substr($u['title'], 0, 70) . '...' : $u['title'] }}
							</a>
						</td>
						<td>
							@if($u['htm'] > 0)
								{{ priceFormat($u['htm']) }}
							@else
								Free
							@endif
						</td>
						<td>{{ date('d F Y, H:i', strtotime($u['schedule'])) }}</td>
						<td>
							@if (isPermitted('admin.events.getIndexUser'))
							<a href="{{ route('admin.events.getIndexUser', $u['id']) }}" class="btn btn-primary btn-sm">
								<i class="fa fa-pencil"></i>
								Participant
							</a>
							@endif
							@if (isPermitted('event.getEdit'))
							<a href="{{ route('event.getEdit', $u['slug']) }}" class="btn btn-info btn-sm">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
							@endif
							@if (isPermitted('event.getShow'))
							<a href="{{ route('event.getShow', $u['slug']) }}" target="_blank" class="btn btn-default btn-sm">
								<i class="fa fa-eye"></i>
								View
							</a>
							@endif
							@if (isPermitted('event.destroy'))
							<a href="{{ route('event.destroy', $u['slug']) }}" class="btn btn-danger btn-sm" onclick="confirm('Are you sure to delete this?');">
								<i class="fa fa-trash"></i>
								Delete
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

			{!! $events->render() !!}
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
