@extends('layouts.default')
@section('title','Pencarian Penggalangan Dana')
@section('head')
	<link rel="stylesheet" href="{{ asset('css/homepage1.3.css') }}">
  <link rel="stylesheet" href="{{ asset('css/project-list1.1.css') }}">
@stop
@section('content')
  <div class="header-title">
		<div style="background: url('{{ asset('images/home-banner.png') }}') center center no-repeat" class="header-title-img">
			<div class="container-mobile">
				<div class="col-md-6 col-xs-12 text-left padding-top-25">
					<br>
					<br>
					<h1 class="content-title">Beri Bantuan</h1>
					<!-- <p class="content-subtitle">Cari dan temukan yang tepat dihati untuk dibantu</p> -->
				</div>
			</div>
		</div>
	</div>
	<div class="container-mobile" style="padding-bottom:50px; min-height: 400px;">
		<header class="text-center content-search-wrapper">
			<h4>Menampilkan hasil pencarian untuk<span class="space-40"/><span class="btn btn-search">{{ urldecode(request()->segment(2)) }} bantu</span></h4>
		</header>

		@if( ! $searched->isEmpty())
			@include('contents.project._list', ['projects' => $searched])
		@else
			<h5 class="text-center" style="padding: 40px 0px;">Tidak menemukan galang dana apapun :(</h5>
		@endif

		<div class="text-center">
			{!! $searched->render() !!}
		</div>

	</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'Search', {
			content_name: 'campaign_search',
			search_string: "{{ urldecode(request()->segment(2)) }}",
		});
	</script>
@stop
