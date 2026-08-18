@extends('layouts.default')
@section('title','User Photos')
@section('content')

	@include('contents.user._cover')

	<div class="container-mobile">
		<div id="justifiedPhotos">
			@if( ! $photos->isEmpty())
			@foreach($photos as $p)
				<div class="media-photo-block">
					@if(Auth::check() && (Auth::user()->id == $p->user_id) )
						<button class="btn photo-del" data-id="{{ $p->id }}">
							<i class="fa fa-times"></i>
						</button>
					@endif
					<a href="{{ media($p['filename'], 'large') }}" id="{{ $p->id }}">
						<img src="{{ media($p['filename'], 'medium') }}" alt="{{ $p['title'] }}">
					</a>
				</div>
			@endforeach
			@else
				<h4>Threre is no photos of this user</h4>
			@endif
		</div>
	</div>

	<div class="container-mobile text-center" style="padding-bottom:50px;">
		<nav>
			{!! $photos->render() !!}
		</nav>
	</div>
	<link rel="stylesheet" href="{{ asset('css/user-photos.css') }}">
	<script src="{{ asset('js/user-photos.js') }}"></script>
@stop
