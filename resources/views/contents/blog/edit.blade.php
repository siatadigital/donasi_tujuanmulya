@extends('layouts.default')
@section('title','create post')
@section('head')
	<style>
		article img{
			max-width: 100%;
			margin-bottom:30px;
		}
		article{
			font-size: 16px;
		}
		.input-title-blog{
			text-align: center;
			font-size: 40px;
			height: 55px;
			border: none;
			box-shadow: none !important;
		}
		.input-title-blog:focus,.input-title-blog:hover..input-title-blog:active{
			outline: none !important;
			border: none;
			box-shadow: none !important;
		}
	</style>
	<script src="{{ asset('js/blog-create.js') }}"></script>
	<script src="{{ asset('js/project-create.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')
	<div class="container-mobile" style="padding: 20px 20px 50px">
		<article>
			{!! Form::model($blog, ['url' => route('blog.putEdit', $blog['slug']), 'method'=>'PUT']) !!}
				@include('contents.blog._form')
			{!! Form::close() !!}
		</article>
	</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'blog_edit_page',
		});
		function custom_slugs() {
			var x = document.getElementById("custom_slug");
			x.value = x.value.replace(/\s+/g, '-').toLowerCase();
		}
	</script>
@stop