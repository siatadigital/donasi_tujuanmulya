@extends('admin::layouts.default')

@section('content')
	
	{{-- <h1>List User</h1> --}}
	
	<div class="nav-tabs-custom">

		{{-- @include('admin::contents.category._tab') --}}

		<div class="tab-content">

			<div style="display:flex;align-items:center;">
				{!! $users->render() !!}
				<form method="GET" style="display:inherit;margin:20px 0px;margin-left:auto;">
					<input type="text" placeholder="Cari Nama" name="keyword" id="keyword" class="form-control" style="width:160px;" value="{{ request()->keyword }}" />
					<button class="btn btn-primary" style="margin-left:8px;"><i class="fa fa-search"></i></button>
					<a href="{{ \Request::url() }}" class="btn btn-danger" style="margin-left:8px;"><i class="fa fa-refresh"></i></a>
				</form>
			</div>
			@if(Route::current()->getName() == 'admin.user.getAdmin')
			@if (isPermitted('admin.user.createAdmin'))
				<br>
				<a href="{{ url('/backend/user/admin/create') }}" class="btn btn-success pull-right">Create New</a>
			@endif
			@endif
			<br><br>
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Register at</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $users->isEmpty() )
				@foreach($users as $u)
					<tr>
						<td>
							<a href="{{ route('user.getShow', $u['username']) }}" target="_blank">
								{{ $u['name'] }} <small>({{ $u['username'] }})</small>
							</a>
						</td>
						<td>{{ $u['email'] }}</td>
						<td>{{ formatTime($u['created_at']) }}</td>
						<td>
							@if (isPermitted('admin.user.getShow'))
							<a href="{{ route('admin.user.getShow', $u['id']) }}" class="btn btn-default btn-sm">
								<i class="fa fa-search"></i> 
								Details
							</a>
							@endif
							@if(Route::current()->getName() == 'admin.user.getAdmin')
							@if (isPermitted('admin.user.editAdmin'))
              <a href="{{ url('/backend/user/admin/edit/'.$u->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit</a>
							@endif
							@if (isPermitted('admin.user.deleteAdmin'))
							<a href="{{ url('/backend/user/admin/delete/'.$u->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</a>
							@endif
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