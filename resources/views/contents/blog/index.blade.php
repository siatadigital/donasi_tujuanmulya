@extends('layouts.default')
@section('title',trans('news.title'))
@section('head')
	<link rel="stylesheet" href="{{ asset('css/homepage1.4.css') }}">
@stop
@section('content')
<style>
	.nav-tabs>.tabs-header-left.active>a, .nav-tabs>.tabs-header-left.active>a:focus, .nav-tabs>.tabs-header-left.active>a:hover {
		border: none;
		background: #ffffff;
		border-radius: 5px 0 0;
		font-family: Raleway;
		font-style: normal;
		font-weight: 400;
		font-size: 14px;
		letter-spacing: .03em;
		color: #333!important;
		margin: 0;
		border-bottom: none;
		height: 40px;
	}
	.nav-tabs>.tabs-header-right.active>a, .nav-tabs>.tabs-header-right.active>a:focus, .nav-tabs>.tabs-header-right.active>a:hover {
		border: none;
		background: #ffffff;
		border-radius: 0 5px 0 0;
		font-family: Raleway;
		font-style: normal;
		font-weight: 400;
		font-size: 14px;
		letter-spacing: .03em;
		color: #333!important;
		margin: 0;
		border-bottom: none;
		height: 40px;
	}
</style>
	<ul class="nav nav-tabs tabs-wrapper">
		<li class="nav tabs-header tabs-header-left active"><a data-toggle="tab" href="#artikel">Artikel</a></li>
		<li class="nav tabs-header tabs-header-right"><a data-toggle="tab" href="#update">Berita</a></li>
	</ul>
  	<div class="tab-content form-donasi-content-wrapper">
		<div class="tab-pane fade in active" id="artikel">
			{{-- <div class="header-title">
				<div style="background: url('{{ asset('images/home-banner.png') }}') center center no-repeat" class="header-title-img">
							<div class="container-mobile">
									<div class="col-md-6 col-xs-12 text-left padding-top-25">
											<br>
											<br>
											<h1 class="content-title">{{ trans('news.title_head') }}</h1>
											<!-- <p class="content-subtitle">{{ trans('news.desc_head') }}</p> -->
									</div>
							</div>
					</div>
			</div> --}}
		
			<section class="stories prisec">
				<div class="container-mobile">
					@if( !$blogs_artikel->isEmpty())
						@include('contents.blog._list_artikel')
					@else
						<h4>Tidak ada artikel ditemukan</h4>
					@endif
				</div>
			</section>
		
			<div class="container-mobile text-center">
				<nav class="text-center">
					@if( !$blogs_artikel->isEmpty())
						{!! $blogs_artikel->render() !!}
					@endif
				</nav>
			</div>
		</div>
		<div class="tab-pane fade in" id="update">
			{{-- <div class="header-title">
				<div style="background: url('{{ asset('images/home-banner.png') }}') center center no-repeat" class="header-title-img">
							<div class="container-mobile">
									<div class="col-md-6 col-xs-12 text-left padding-top-25">
											<br>
											<br>
											<h1 class="content-title">{{ trans('news.title_head') }}</h1>
											<!-- <p class="content-subtitle">{{ trans('news.desc_head') }}</p> -->
									</div>
							</div>
					</div>
			</div> --}}
		
			<section class="stories prisec">
				<div class="container-mobile">
					@if( !$blogs_update->isEmpty())
						@include('contents.blog._list_update')
					@else
						<h4>Tidak ada berita ditemukan</h4>
					@endif
				</div>
			</section>
		
			<div class="container-mobile text-center">
				<nav class="text-center">
					@if( !$blogs_update->isEmpty())
						{!! $blogs_update->render() !!}
					@endif
				</nav>
			</div>
		</div>
	</div>
	
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'blog_list',
		});
	</script>
@stop