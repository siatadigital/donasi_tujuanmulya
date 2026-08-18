@extends('layouts.default')
@section('title',trans('reset.title'))
@section('head')
	<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@stop
@section('content')
@if(session()->has('status'))
	<div class="alert alert-info">
		{{ session()->get('status') }}
	</div>
@endif

@include('partials.alert-error')
<div class="container-mobile" style="padding:25px;">
	<div class="row">
		<div class="input-wrapper-reset">
			<h1 class="content-title" style="margin: 0 30px -15px;">{{ trans('reset.title_head') }}</h1>
			<div class="paper-white">
				@include('partials.alert-error')
				{!! Form::open(['route'=>'password.postReset']) !!}
				<input type="hidden" name="email" value="{{ $password->email }}" />
				<input type="hidden" name="token" value="{{ $password->token }}" />
        <label>{{ trans('reset.password') }}</label>
				<div class="input-row">
					{!! Form::password('password',['class'=>'form-control reveal-password','placeholder'=>trans('reset.password_placeholder'),'required','style'=>'height: 38px']) !!}
					<button class="button-input" type="button">
						<i class="fa fa-eye"></i>
					</button>
				</div>
				<p></p>
				<label>{{ trans('reset.password_conf') }}</label>
				<div class="input-row">
					{!! Form::password('password_confirmation',['class'=>'form-control reveal-password','placeholder'=>trans('reset.password_conf_placeholder'),'required','style'=>'height: 38px']) !!}
					<button class="button-input" type="button">
						<i class="fa fa-eye"></i>
					</button>
				</div>
				<p></p>
				<p class="text-center btn-forgot-wrapper">
					<button  type="submit" class="btn btn-lg-blue btn-lg btn-block">
						{{ trans('reset.save') }}
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
			content_name: 'reset_password_page',
		});
	</script>
@stop