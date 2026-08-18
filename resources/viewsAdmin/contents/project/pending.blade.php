@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 50%;">Judul Campaign</th>
						<th>Status</th>
						<th>Dibuat Tanggal</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $projects->isEmpty() )
				@foreach($projects as $pr)
					<tr>
						<td>
							<a href="{{ route('project.newGetShow', $pr['slug']) }}" target="_blank">
								{{ $pr['title'] }}
							</a>
						</td>
						<td>{{ $pr['status'] }}</td>
						<td>{{ formatTime($pr['created_at']) }}</td>
						<td>
							@if (isPermitted('admin.project.getShow'))
							<a href="{{ route('admin.project.getShow', $pr['id']) }}" class="btn btn-default btn-sm">
								<i class="fa fa-search"></i> 
								Review
							</a>
							@endif
							@if (isPermitted('admin.project.putAccept'))
							{!! Form::link('Accept', 'PUT', route('admin.project.putAccept', [$pr['id']]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], 'are you sure to confirm ?') !!}
							@endif
							@if (isPermitted('admin.project.putReject'))
							{!! Form::link('Reject', 'PUT', route('admin.project.putReject', [$pr['id']]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], 'are you sure reject ?') !!}
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

			{!! $projects->render() !!}

		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->
  
@stop