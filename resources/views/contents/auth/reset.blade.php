@extends('layouts.default')
@section('title',trans('reset.title'))
@section('head')
	<link rel="stylesheet" href="{{ asset('css/auth1.1.css') }}">
@stop
@section('content')
<div class="container-mobile" style="padding:70px 0px;">
	<div class="row">
		<div class="input-wrapper-forgot">
			<h1 class="content-title" style="margin: 0 30px -15px;">{{ trans('reset.title_head') }}</h1>
			<div class="paper-white">
				@include('partials.alert-error')
				{!! Form::open(['route'=>'auth.postRegister']) !!}
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
					<a href="{{ route('auth.getRegister') }}" class="btn btn-lg-blue btn-lg btn-block">
						{{ trans('reset.save') }}
					</a>
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