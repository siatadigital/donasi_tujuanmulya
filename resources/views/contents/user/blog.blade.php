@extends('layouts.default')
@section('title','Username Blogs')
@section('content')
	@include('contents.user._cover')

	<section class="stories prisec">
		<div class="container-mobile">
			<div class="row">
				<div class="col-sm-8">
					@if( !$blogs->isEmpty())
						@include('contents.blog._list')

						<nav>
							{!! $blogs->render() !!}
						</nav>
					@else
						<h4>No blog, right now</h4>
					@endif
				</div>

				<div class="col-sm-4">
					<a href="{{ getOption('homepage_vertical_ads')['link'] }}" target="_blank" >
						<img src="{{ getOption('homepage_vertical_ads')['image'] }}" alt="{{ getOption('homepage_vertical_ads')['title'] }}" width="100%">
					</a>
				</div>
			</div>
		</div>
	</section>
@stop