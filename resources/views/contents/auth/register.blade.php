@extends('layouts.default')
@section('title',trans('register.title'))
@section('head')
	<link rel="stylesheet" href="{{ asset('css/auth1.1.css') }}">
@stop
@section('content')
<style>
	.btn-facebook {
		color: #fff;
		background-color: #337ab7;
		border-color: #2e6da4;
	}
</style>
@if(session()->has('status'))
	<div class="alert alert-info">
		{{ session()->get('status') }}
	</div>
@endif

@include('partials.alert-error')
<div class="container-mobile" style="padding:25px">
	<div class="row">
		<div class="input-wrapper-register">
			<h1 class="content-title" style="margin: 0 30px -15px;">{{ trans('register.title_head') }}</h1>
			<div class="paper-white">
				{!! csrf_field() !!}
				@include('partials.alert-error')
				{!! Form::open(['route'=>'auth.postRegister']) !!}
				<label>{{ trans('register.email') }}</label>
				<p>
					{!! Form::text('email',null,['class'=>'form-control','placeholder'=>trans('register.email_placeholder'),'required','style'=>'height: 38px']) !!}
				</p>
				<label>{{ trans('register.password') }}</label>
				<div class="input-row">
					{!! Form::password('password',['class'=>'form-control reveal-password','placeholder'=>trans('register.password_placeholder'),'required','style'=>'height: 38px']) !!}
					<button class="button-input" type="button">
						<i class="fa fa-eye"></i>
					</button>
				</div>
				<p></p>
				<label>{{ trans('register.password_conf') }}</label>
				<div class="input-row">
				{!! Form::password('password_confirmation',['class'=>'form-control reveal-password','placeholder'=>trans('register.password_conf_placeholder'),'required','style'=>'height: 38px']) !!}
					<button class="button-input" type="button">
						<i class="fa fa-eye"></i>
					</button>
				</div>
				<p></p>
				<label>{{ trans('register.phone') }}</label>
				<p>
					{!! Form::text('phone',null,['class'=>'form-control','placeholder'=>trans('register.phone_placeholder'),'required','style'=>'height: 38px']) !!}
				</p>
				<p class="text-center btn-register-wrapper">
					<button type="submit" class="btn btn-lg-blue btn-lg btn-block">
						{{ trans('register.register') }}
					</button>
				</p>
				<p class="text-center">
					<a href="{{ route('auth.getLogin') }}" class="btn btn-lg-white btn-lg btn-block">
						{{ trans('register.login') }}
					</a>
				</p>
				{{-- <p class="text-center btn-register-wrapper">
					<a href="{{ url('/auth/facebook') }}" class="btn btn-facebook btn-lg-blue btn-lg btn-block"><i class="fa fa-facebook"></i>&nbsp;&nbsp; {{ trans('register.register_fb') }}</a>
				</p> --}}
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'register_page',
		});
	</script>
@stop
