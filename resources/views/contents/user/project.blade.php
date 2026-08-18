@extends('layouts.default')
@section('title','User projects')
@section('content')
	@include('contents.user._cover')


	<div class="project-list-content">
		<div class="container-mobile" style="padding: 20px;">
			@if(! $projects->isEmpty())
				@include('contents.project._list')
			@else
				<h4 class="text-center">Tidak ada campaign yang dibuat</h4>
			@endif
		</div>
  </div>

	<div class="container text-right" style="padding-bottom:50px;">
		<nav>
			{!! $projects->render() !!}
		</nav>
	</div>
@stop