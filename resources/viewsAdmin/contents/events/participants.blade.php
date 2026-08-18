@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">

            <a href="{{ route('admin.events.getIndex') }}" class="btn btn-warning">Back</a>
			<div class="clearfix"></div>
			<br>

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>Nama Lengkap</th>
						<th>Email</th>
						<th>No. HP (WhatsApp)</th>
					</tr>
				</thead>
				<tbody>

				@if( ! $events_users->isEmpty() )
				@foreach($events_users as $u)
					<tr>
                        <td>{{ $u['name'] }}</td>
                        <td>{{ $u['email'] }}</td>
						<td>+62{{ $u['phone'] }}</td>
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
