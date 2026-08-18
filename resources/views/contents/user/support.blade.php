@extends('layouts.default')
@section('title','User projects')
@section('content')

	@include('contents.user._cover')

	<div class="container-mobile" style="padding: 20px;">
		@if( !$supportings->isEmpty())
			<table class="table table-striped">
				<tr>
					<thead>
						<th>Campaign</th>
						<th>Nominal</th>
						<th>Status</th>
						<th>Tanggal</th>
					</thead>
				</tr>
				<tbody>
					@foreach($supportings as $sup)
						<tr>
							<td>
								<a href="{{ route('project.newGetShow', $sup['project']['slug']) }}">
									{{ $sup['project']['title'] }}
								</a>
							</td>
							<td>
								{{ priceFormat($sup['money'] + $sup['unique_code']) }}
							</td>
							<td>
								{{ $sup['status'] }}
							</td>
							<td>
								{{ formatTime($sup['created_at'], 'human') }}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<h4>Tidak ada aktifitas sebagai donatur</h4>
		@endif
	</div>

	<div class="container-mobile text-right" style="padding-bottom:50px;">
		<nav>
			{!! $supportings->render() !!}
		</nav>
	</div>
@stop