@extends('layouts.default')
@section('title','All Events')
@section('head')
<link rel="stylesheet" href="{{ asset('css/homepage1.4.css') }}">
@stop
@section('content')
<div class="header-title">
	<div style="background: url('{{ asset('images/home-banner.png') }}') center right no-repeat" class="header-title-img">
		<div class="container">
			<div class="col-md-6 col-xs-12 text-left padding-top-25">
				<br>
				<br>
				<h1 class="content-title">Kegiatan Positif</h1>
				<p class="content-subtitle">Mari belajar dan berbagi dengan mengikuti kegiatan kami</p>
			</div>
		</div>
	</div>
</div>
<br />
<br />
<br />
<div class="container">
	@foreach($events as $event)
	<div class="col-md-4 outer">
		<div class="new-activity-wrapper">
			<img src="{{ media($event->cover,'medium') }}" alt="{{ str_limit($event['title'], $limit = 30, $end='...') }}" class="new-activity-img">
			<div class="new-activity-text">
				<a href="{{ route('event.getShow',$event->slug) }}" class="activity-title">{{ str_limit($event['title'], $limit = 30, $end='...') }}</a>
				<div class="flex-row-wrapper">
					<i class="fa fa-calendar"></i>
					<div class="space"></div>
					<p class="medium-title-lite">{{ date('d M Y', strtotime($event['schedule'])) }}</p>
					<div class="medium-space"></div>
					<i class="fa fa-money"></i>
					<div class="space"></div>
					<p class="medium-title-lite">
						@if($event->htm > 0)
						{{ priceFormat($event->htm) }}
						@else
						Gratis!
						@endif
					</p>
				</div>
				<div class="flex-row-wrapper">
					<i class="fa fa-map-marker" style="font-size:17px;"></i>
					<div class="space"></div>
					<p class="medium-title-lite">{{ str_limit($event['location'], $limit = 90, $end = '...' ) }}</p>
				</div>
			</div>
		</div>
		<br />
		<br />
	</div>
	@endforeach
</div>

<div class="container">
	<nav class="text-center">
		@if( !$events->isEmpty())
		{!! $events->render() !!}
		@endif
	</nav>
</div>
<br>
<br>
@stop
@section('scripts')
<script src="{{ asset('js/homepage.js') }}"></script>
@stop