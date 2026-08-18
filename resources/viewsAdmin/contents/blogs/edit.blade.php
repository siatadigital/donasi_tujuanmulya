@extends('admin::layouts.default')
@section('head')
	<script src="{{ asset('js/blog-create.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
      <form action="{{ route('admin.blogs.postEdit', $blog['id']) }}" method="post">
        <header class="page-header text-center">
        	{!! Form::text('title', null, ['class'=>'form-control input-lg input-title-blog','required','placeholder'=>'Title here ..','autocomplete'=>'off']) !!}
        </header>

        <section>
        	{!! Form::textarea('content', null, ['class'=>'summernote']) !!}
        </section>
        <p class="text-center">
        	{!! Form::submit('Save & Publish',['class'=>'btn btn-primary btn-lg']) !!}
        </p>
      </form>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
