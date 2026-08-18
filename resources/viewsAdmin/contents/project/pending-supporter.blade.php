@extends('admin::layouts.default')

@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">

			{!! $supporters->render() !!}

			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th style="width: 15%;">Supporter Name</th>
						<th>Project Name</th>
						<th style="width: 30%;">Details</th>
						<th>Status</th>
						<th>CODE</th>
						<th style="width: 10%;">Supporting at</th>
						<th style="width: 15%;"> </th>
					</tr>
				</thead>
				<tbody>

				@if( ! $supporters->isEmpty() )
				@foreach($supporters as $s)
					<tr>
						<td>
							<a href="{{ route('admin.user.getShow', $s['id']) }}">
								{{ $s['user']['name'] }}
							</a>
						</td>
						<td>
							<a href="{{ route('admin.project.getShow', $s['project']['id']) }}">
								{{ $s['project']['title'] }}
							</a>
						</td>
						<td>
							<div>
								<label>Money : </label> {{ priceFormat($s['money']) }}
							</div>
							<div>
								<label>Bank : </label> {{ $s['bank_type'] }}
							</div>
							<div>
								@if ($s['email'] != "")
									Name : {{ $s['name'] }} <br>
									Email : {{ $s['email'] }} <br>
									Phone : {{ $s['phone'] }} <br>
									Phone : {{ $s['referal'] }}
								@else
									<label>Notes : </label> {!! $s['notes'] or '-' !!}
								@endif
							</div>
						</td>
						<td>{{ $s['status'] }}</td>
						<td>{{ $s['unique_code'] }}</td>
						<td>{{ formatTime($s['created_at']) }}</td>
						<td>
							{!! Form::link('Accept', 'PUT', route('admin.project.putAcceptSupporter', [$s['id']]), ['icon' => 'fa fa-check', 'class' => 'btn btn-success btn-sm'], 'are you sure to confirm ?') !!}

							{!! Form::link('Reject', 'PUT', route('admin.project.putRejectSupporter', [$s['id']]), ['icon' => 'fa fa-check', 'class' => 'btn btn-danger btn-sm'], 'are you sure reject ?') !!}
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

			{!! $supporters->render() !!}
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
