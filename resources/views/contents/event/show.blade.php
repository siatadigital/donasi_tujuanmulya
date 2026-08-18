@extends('layouts.default')
@section('title','create post')
@section('head')
	<style>
		#map{
			height: 300px;
			width: 100%;
		}

		.events-cover{
			position: relative;
			overflow: hidden;
			height: 300px;
		}

		.events-cover img{
			width: 100%;
		}

		.related-event-cover{
			position: relative;
			overflow: hidden;
			height: 220px;
			background-color: #008797;
		}

		.related-event-cover img{
			width: 100%;
		}

		.outer{
			padding: 10px;
		}

		.event-paper{
			background-color: white;
			box-shadow: 0 0 2px rgba(0,0,0,0.2);
		}

		.event-paper .body{
			padding: 10px;
		}

		.event-price{
			position: absolute;
			background-color: white;
			padding: 5px;
			color: black;
			top: 5px;
			left: 5px;
		}

		.events-header{
			height: 300px;
			background-size: cover;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			background-repeat: no-repeat;
			margin-bottom: 50px;
		}
		.outer-related{
			position: relative;
			margin-bottom: 20px;
		}
		.outer-related a{
			color: white !important;
		}

		.outer-related .body{
			position: absolute;
			bottom: 0%;
			width: 100%;
			padding: 0px 10px;
		}
		.popup-image{
			display: none;
			position: fixed;
			z-index: 2;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: rgba(0, 0, 0, 0.51);
		}

		.popup-image img{
			max-width: 100% !important;
			padding-bottom: 100px;
		}

		.popup-image>.inside{
			position:absolute;
			max-width: 90%;
			max-height: 90%;
			left: 50%;
			top: 50%;
			transform: translate(-50%,-50%);
			-webkit-transform: translate(-50%,-50%);
		}

		.popup-image .body{
			position: relative;
		}

		.popup-image .body>button.dismiss-popup-image{
			position: absolute;
			top: 0;
			right: -40px;
			background-color: transparent;
			color: white;
			font-size: 30px;
			border: none;
		}

		img.zoom-able{
			cursor: pointer;
		}
	</style>
@stop
@section('content')
	<div class="popup-image" id="popup-image">
		<div class="inside">
			<div class="body">
				<img src="{{ media($event->cover,'large') }}">

				<button class="dismiss-popup-image">
					<i class="fa fa-times"></i>
				</button>
			</div>
		</div>
	</div>


	<div class="container" style="margin-top:50px;padding-bottom: 100px;">
		<div class="row">
			<div class="col-md-8">
				<article>
					<header>
						<!-- <div class="events-cover"> -->
							<img src="{{ media($event->cover,'large') }}" alt="" class="zoom-able img-responsive" width="100%">
						<!-- </div> -->

						<h2>
							{{ $event->title }}
						</h2>
						<p class="medium">
							<i class="fa fa-location-arrow"></i> {{ $event->location }} | <i class="fa fa-money"></i>
							@if($event->htm > 0)
								{{ priceFormat($event->htm) }}
							@else
								Free
							@endif
							| <i class="fa fa-calendar"></i> {{ date('d F Y, H:i', strtotime($event->schedule)) }}
							| <i class="fa fa-user"></i> {{ $event->count_attendance }} hadir
						</p>
						<hr>
					</header>

					<section>
						{!! $event->description !!}
					</section>
				</article>
				<br>
				<div id="map"></div>
				<div class="col-md-6 col-md-offset-3">
					<header class="page-header text-center">
						<h3>Ikut Event</h3>
					</header>
					@if (Auth::guest())
						<p class="text-center">Silahkan login / daftar akun untuk mengikuti event ini</p>
						<a href="{{ route('auth.getLogin') }}" class="btn btn-danger btn-block">Masuk</a>
						<p class="text-center" style="margin-top: 7px;">atau</p>
						<a href="{{ route('auth.getRegister') }}" class="btn btn-danger btn-block">Gabung</a>
					@else
						<?php $alreadyJoin = false; ?>
						@if (isset($event->attendances))
							@foreach($event->attendances as $item)
								@if ($item->user_id == Auth::user()->id)
									<?php $alreadyJoin = true; ?>
								@else
									<?php $alreadyJoin = false; ?>
								@endif
							@endforeach
						@endif
						@if ($alreadyJoin)
							<p class="text-center">Anda telah terdaftar pada event ini</p>
						@else
							<?php
							$dateSchedule = strtotime($event->schedule);
							$dateNow = strtotime(date('Y-m-d H:i:s'));
							$dateNow = strtotime("+7 hours", $dateNow);
							?>
							@if ($dateSchedule < $dateNow)
								<p class="text-center">Event ini telah berakhir</p>
							@else
								@include('partials.alert-error')
								{!! Form::open(['route'=>'event.registration']) !!}
									<div class="form-group">
										<label for="name">Nama Lengkap</label>
										{!! Form::text('name',Auth::user()->name,['class'=>'form-control','placeholder'=>'Nama Lengkap','required', 'autofocus']) !!}
									</div>
									<div class="form-group">
										<label for="email">Email</label>
										{!! Form::email('email',Auth::user()->email,['class'=>'form-control','placeholder'=>'contoh@example.com','required']) !!}
									</div>
									<div class="form-group">
										<label for="phone">No. HP (WhatsApp)</label>
										<div class="input-group">
											<span class="input-group-addon" id="phone-addon">+62</span>
											{!! Form::text('phone',Auth::user()->phone,['class'=>'form-control','placeholder'=>'Contoh: 8123456789','required']) !!}
										</div>
									</div>
									<div class="form-group text-center">
										{!! Form::submit('Ikut Hadir',['class'=>'btn btn-danger btn-lg btn-block']) !!}
									</div>
									<input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
									<input type="hidden" name="event_id" value="{{ $event->id }}">
								{!! Form::close() !!}
							@endif
						@endif
					@endif
				</div>
				<br>
				<br>
				<div class="fb-comments" data-href="{{ request()->fullUrl() }}" data-width="100%" data-numposts="5"></div>
			</div>

			<div class="col-md-4">
				@foreach($related as $item)
				<div class="outer-related">
					<div class="related-event-cover">
						<strong class="event-price">
							@if($item->htm > 0)
								{{ priceFormat($item->htm) }}
							@else
								Free
							@endif
							| <i class="fa fa-calendar"></i> {{ date('d F Y, H:i', strtotime($item->schedule)) }}
							| <i class="fa fa-user"></i> {{ $event->count_attendance }} hadir
						</strong>
						<a href="{{ URL::Route('event.getShow',$item->slug) }}">
							<img src="{{ media($item->cover,'medium') }}" alt="{{ $item->title }}" style="margin-top: -20%">
						</a>
					</div>

					<div class="body">
						<h4>
							<a href="{{ URL::Route('event.getShow',$item->slug) }}">{{ strlen($item->title) > 35 ? substr($item->title, 0, 35) . '...' : $item->title }}</a>
						</h4>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>

	<script>
		$(function(){
			$(".zoom-able").click(function(){
				$("#popup-image").fadeIn(300);
			});

			$("#popup-image button.dismiss-popup-image").click(function(){
				$("#popup-image").fadeOut(200);
			})
		})
		function initMap() {
			var $lat = parseInt({{ $event->lat }});
			var $lng = parseInt({{ $event->lng }});
			var myLatLng = {lat: $lat, lng: $lng};

			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 12,
				center: myLatLng
			});

			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'Event Location'
			});
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiB2e6nuAnjEox-xPzUBL7Tw8vzHs4FIY&libraries=places&signed_in=true&callback=initMap"
        async defer></script>
@stop
