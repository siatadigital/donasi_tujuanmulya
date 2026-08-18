@extends('admin::layouts.default')

@section('content')
	
	<div class="nav-tabs-custom">

		<div class="tab-content">

			<div style="display:flex;align-items:center;">
				{!! $users->render() !!}
				<form method="GET" style="display:inherit;margin:20px 0px;margin-left:auto;">
					<input type="text" placeholder="Cari Nama" name="keyword" id="keyword" class="form-control" style="width:160px;" value="{{ request()->keyword }}" />
					<button class="btn btn-primary" style="margin-left:8px;"><i class="fa fa-search"></i></button>
					<a href="{{ \Request::url() }}" class="btn btn-danger" style="margin-left:8px;"><i class="fa fa-refresh"></i></a>
				</form>
			</div>
			
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Attachment</th>
						<th>Requesting at</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>
				@if( ! $users->isEmpty() )
				@foreach($users as $u)
					<tr>
						<td>
							<a href="">
								{{ $u['name'] }}
							</a>
						</td>
						<td>
							<a href="{{ media($u['fotoktp'], 'medium') }}" target="_blank">
								<img src="{{ media($u['fotoktp'], 'medium') }}" style="max-width:300px" class="img-responsive">
							</a>
						</td>
						<td>{{ formatTime($u['created_at']) }}</td>
						<td>
							<a href="{{ route('admin.user.getShow', $u['id']) }}" class="btn btn-default btn-sm">
								<i class="fa fa-search"></i> 
								Review
							</a>
							@if (isPermitted('admin.user.putVerifyAccept'))
							{!! Form::link('Confirm', 'PUT', route('admin.user.putVerifyAccept', [$u['id']]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], 'are you sure to confirm ?') !!}
							@endif
							@if (isPermitted('admin.user.putVerifyReject'))
							{!! Form::link('Reject', 'PUT', route('admin.user.putVerifyReject', [$u['id']]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], 'are you sure reject ?') !!}
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

			{!! $users->render() !!}
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop