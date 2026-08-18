@extends('admin::layouts.default')

@section('content')

	{{-- <h1>List Blog</h1> --}}

	<div class="nav-tabs-custom">

		{{-- @include('admin::contents.category._tab') --}}

		<div class="tab-content">

			@if (isPermitted('blog.getCreate'))
			<a href="{{ route('blog.getCreate') }}" class="btn btn-success pull-right">Write Post</a>
			@endif
			<div class="clearfix"></div>
			<br>

			{!! $blogs->render() !!}

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Title</th>
						<th>Kategori</th>
						<th>Status</th>
						<th>Created at</th>
						<th> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $blogs->isEmpty() )
				@foreach($blogs as $u)
					<tr>
						<td>
							<a href="{{ route('blog.getShow', $u['slug']) }}" target="_blank">
								{{ $u['title'] }}
							</a>
						</td>
						<td>{{ $u['blog_categories']['title'] }}</td>
						<td>{{ $u['status'] }}</td>
						<td>{{ formatTime($u['created_at']) }}</td>
						<td>
							@if (isPermitted('blog.getEdit'))
							<a href="{{ route('blog.getEdit', $u['slug']) }}" class="btn btn-info btn-sm">
								<i class="fa fa-pencil"></i>
								Edit
							</a>
							@endif
							@if (isPermitted('blog.getShow'))
							<a href="{{ route('blog.getShow', $u['slug']) }}" class="btn btn-default btn-sm">
								<i class="fa fa-eye"></i>
								View
							</a>
							@endif
							@if (isPermitted('blog.destroy'))
							<a href="{{ route('blog.destroy', $u['slug']) }}" class="btn btn-danger btn-sm" onclick="confirm('Are you sure to delete this?');">
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

			{!! $blogs->render() !!}
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
