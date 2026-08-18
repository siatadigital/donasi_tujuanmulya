@extends('layouts.default')
@section('title',trans('login.title'))
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
	<div class="container-mobile" style="padding:25px;">
		<div class="row">
			<div class="input-wrapper">
				<h1 class="content-title" style="margin: 0 30px -15px;">{{ trans('login.title_head') }}</h1>
				<div class="paper-white">
					@include('partials.alert-error')
					{!! Form::open(['route'=>'auth.postLogin']) !!}
					<label>{{ trans('login.email') }}</label>
					<p>
						{!! Form::text('user',null,['class'=>'form-control','placeholder'=>trans('login.email_placeholder'),'required','style'=>'height: 38px']) !!}
					</p>
					<label>{{ trans('login.kata_sandi') }}</label>
					<div class="input-row">
						{!! Form::password('password',['class'=>'form-control reveal-password','placeholder'=>trans('login.kata_sandi_placeholder'),'required','style'=>'height: 38px']) !!}
						<button class="button-input" type="button">
							<i class="fa fa-eye"></i>
						</button>
					</div>
					<p></p>
					<a href="{{ route('password.getEmail') }}" class="pull-right">{{ trans('login.lupa_kata_sandi') }}</a>
					<p class="text-center btn-register-wrapper">
						<button type="submit" class="btn btn-lg-blue btn-lg btn-block">
							{{ trans('login.masuk_akun') }}
						</button>
					</p>
					<p class="text-right">
						<a href="{{ route('auth.getRegister') }}" class="btn btn-lg-white btn-lg btn-block">
							{{ trans('login.buat_akun') }}
						</a>
					</p>
					{{-- <p class="text-center btn-register-wrapper">
							<a href="{{ url('/auth/facebook') }}" class="btn btn-facebook btn-lg-blue btn-lg btn-block"><i class="fa fa-facebook"></i>&nbsp;&nbsp; {{ trans('login.masuk_fb') }}</a>
					</p> --}}
					{!! Form::close() !!}
				</div>

				{{--  {!! Form::close() !!} --}}
					
				</div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'login_page',
		});
	</script>
@stop
