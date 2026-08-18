@extends('layouts.default')
@section('title','Username Blogs')
@section('content')
	<section class="new-artist prisec">
		<div class="container-mobile">
			<header>
				<br>
				<h2>Showing {{ $users->count() }} Users</h2>
				<br>
			</header>
		</div>
	</section>
	<section class="prisec">
		<div class="container-mobile">
			@foreach(array_chunk($users->all(), 6) as $row)
				<div class="row" style="margin-bottom:50px;">
					@foreach($row as $user)
						<div class="col-md-2 text-center">
							<img src="{{ media($user->avatar,'small') }}" alt="{{ $user->name }}" width="100%" class="img-rounded">
							<br>
							<a href="{{ route('user.getShow',$user->username) }}">
								<h4>{{ $user->name }}</h4>
							</a>
						</div>
					@endforeach
				</div>
			@endforeach
		</div>
	</section>

	<div class="container">
		{!! $users->render() !!}
	</div>
@stop