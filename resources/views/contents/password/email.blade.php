@extends('layouts.default')
@section('title',trans('forgot.title'))
@section('head')
	<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@stop
@section('content')
@if(session()->has('status'))
		<div class="alert alert-info">
			{{ session()->get('status') }}
		</div>
	@endif
<div class="container-mobile" style="padding:25px;">
	<div class="row">
		<div class="input-wrapper-reset">
			<h1 class="content-title" style="margin: 0 30px -15px;">{{ trans('forgot.title_head') }}</h1>
			<div class="paper-white">
        <div class="forgot-text-wrapper">
            <p class="text-center forgot-text">{{ trans('forgot.title_form') }}</p>
        </div>
				@include('partials.alert-error')
				{!! Form::open(['route'=>'password.postEmail']) !!}
				<label>{{ trans('forgot.email') }}</label>
				<p>
					{!! Form::text('email',null,['class'=>'form-control','placeholder'=>trans('forgot.email_placeholder'),'required','style'=>'height: 38px']) !!}
				</p>
				<p class="text-center btn-forgot-wrapper">
					<button  type="submit" class="btn btn-lg-blue btn-lg btn-block">
						{{ trans('forgot.kirim_email') }}
					</button>
				</p>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'forgot_password_page',
		});
	</script>
@stop