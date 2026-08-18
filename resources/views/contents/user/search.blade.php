@extends('layouts.default')
@section('title','Search Artist')
@section('head')
	<link rel="stylesheet" href="{{ asset('css/search-user.css') }}">
@stop
@section('content')
	<div class="container-mobile" style="padding-bottom:50px; min-height: 400px;">
		<header class="page-header text-center">
			<h1>Search result for <span class="highlight">{{ urldecode(request()->segment(2)) }}</span></h1>
		</header>

		@if( ! $searched->isEmpty())
			@foreach (array_chunk($searched->all(), 4) as $row)
				<div class="row">
					@foreach ($row as $artist)
						<div class="col-md-3">
							<div class="user-paper">
								<a href="{{ route('user.getShow', $artist['username']) }}">
									<img src="{{ media($artist['avatar'],'medium') }}" alt="{{ $artist['name'] }}" class="img-rounded">
								</a>
								<header>
									<h3><a href="{{ route('user.getShow', $artist['username']) }}">
										{!! str_replace(
											urldecode(request()->segment(2)),
											'<span style="color: red;">'. urldecode(request()->segment(2)) .'</span>',
											$artist['name']) !!}
									</a></h3>
								</header>
								<section>
									<p>{{ $artist['quotes'] }}</p>
								</section>
							</div>
						</div>
					@endforeach
				</div>
			@endforeach
		@else

			<h2 class="text-center" style="padding: 40px 0px;">Whooops, there is no result for it</h2>

		@endif
		
		<div class="text-right">
			{!! $searched->render() !!}
		</div>

	</div>
@stop