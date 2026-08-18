@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">

			<div style="display:flex;align-items:center;">
				{!! $projects->render() !!}
				<form method="GET" style="display:inherit;margin:20px 0px;margin-left:auto;">
					<input type="text" placeholder="Cari Judul" name="keyword" id="keyword" class="form-control" style="width:160px;" value="{{ request()->keyword }}" />
					<button class="btn btn-primary" style="margin-left:8px;"><i class="fa fa-search"></i></button>
					<a href="{{ \Request::url() }}" class="btn btn-danger" style="margin-left:8px;"><i class="fa fa-refresh"></i></a>
				</form>
			</div>

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 50%;">Judul Campaign</th>
						<th>Donator</th>
						<th>Terkumpul</th>
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
						<td>{{ $pr['supporters']->count() }}</td>
						<td>{{ priceFormat($pr['money_progress']) }}</td>
						<td>{{ $pr['status'] }}</td>
						<td>{{ formatTime($pr['created_at']) }}</td>
					<td>
							@if (isPermitted('admin.project.getShow'))
							<a href="{{ route('admin.project.getShow', $pr['id']) }}" class="btn btn-default btn-sm">
								<i class="fa fa-search"></i>
								Details
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

			{!! $projects->render() !!}
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
