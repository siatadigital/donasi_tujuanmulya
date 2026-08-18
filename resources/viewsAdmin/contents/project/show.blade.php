@extends('admin::layouts.default')

@section('content')

	<div class="box box-default">
		<div class="box-header with-border">
			<i class="fa fa-file-text"></i>
			<h3 class="box-title">{{ $project['title'] }}</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-4">
					<a href="{{ media($project['cover'], 'large') }}" class="thumbnail" target="_blank">
						<img src="{{ media($project['cover'], 'medium') }}" style="width: 100%;" />
					</a>
					<div class="thumbnail">
						<iframe width="100%" height="auto" src="{{ $project['video'] }}" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="col-md-8">

					<div class="clearfix">
						<div class="pull-left">
							Current Status : <span class="label label-info">{{ $project['status'] }}</span>
						</div>

						<div class="pull-right">
							@if (isPermitted('admin.project.putAccept'))
							{!! Form::link('Accept', 'PUT', route('admin.project.putAccept', [$project['id']]), ['icon' => 'fa fa-angle-up', 'class' => 'btn btn-success btn-sm'], 'are you sure to confirm ?') !!}
							@endif
							or
							@if (isPermitted('admin.project.putReject'))
							{!! Form::link('Reject', 'PUT', route('admin.project.putReject', [$project['id']]), ['icon' => 'fa fa-angle-down', 'class' => 'btn btn-danger btn-sm'], 'are you sure reject ?') !!}
							@endif
						</div>
					</div>
					<hr>

					<div>
						<label>Money Target</label>
						<div>{!! priceFormat($project['money_target']) !!}</div>
					</div>
					<div>
						<label>Money Progress</label>
						<div>{!! priceFormat($project['money_progress']) !!}</div>
					</div>
					<br>
					<div>
						<label>Due Time</label>
						<div>{!! formatTime($project['time_end']) !!}</div>
					</div>
					<br>

					<div>
						<label>Summary</label>
						<div>{!! $project['summary'] !!}</div>
					</div>
					<br>

					<div>
						<label>Content</label>
						<div>{!! $project['content'] !!}</div>
					</div>
					<div>
						<label>Investor</label>
						<br>
						<!-- <a href="#" class="btn btn-primary">Blasting Email to Investor</a> -->
						<!-- <br><br> -->
						<div>
							@if(count($project['supporters']))
								@foreach($project['supporters'] as $sup)
									<div class="media-list">
										<div class="media-left">
											<a href="{{ route('user.getShow', $sup['user']['username']) }}">
											<img class="media-object img-rounded img-64" src="{{ media($sup['user']['avatar'], 'small') }}" alt="{{ $sup['user']['name'] }}" width="64">
											</a>
										</div>
										<div class="media-body">
											<h4 class="media-heading">
												{{ $sup['fullname'] }}
												<small>{{ formatTime($sup['created_at'], 'human') }}</small>
											</h4>
											<strong class="display">{{ priceFormat($sup['money'] + $sup['unique_code']) }}</strong>
											@if($sup['status'] == "accept")
											<span class="label label-success">ACCEPT</span>
											@else
											<span class="label label-danger">{{ strtoupper($sup['status']) }}</span>
											@endif
										</div>
										<br>
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@stop
