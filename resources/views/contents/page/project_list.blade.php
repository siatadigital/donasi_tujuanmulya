@extends('layouts.default')
@section('title','Search Artist')
@section('head')
	<link rel="stylesheet" href="{{ asset('css/search-user.css') }}">
	<style>
		body{
			background-color:#FBFBFB;
		}
	</style>
@stop
@section('content')
	<div class="container" style="padding-bottom:50px; min-height: 400px;">
		<header class="page-header text-center">
			<h1>Menampilkan hasil pencarian untuk <span class="highlight">{{ urldecode(request()->segment(2)) }}</span></h1>
		</header>

		@if( ! $searched->isEmpty())
			<div class="row">
				@include('contents.project._list', ['projects' => $searched])
			</div>
		@else

			<h2 class="text-center" style="padding: 40px 0px;">Whooops, there is no result for it</h2>

		@endif

		<div class="text-right">
			{!! $searched->render() !!}
		</div>

	</div>
@stop
