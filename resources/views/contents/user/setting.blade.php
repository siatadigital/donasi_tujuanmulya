@extends('layouts.default')
@section('head')
	<link rel="stylesheet" href="{{ asset('css/user-setting.css') }}">
@stop
@section('content')
	<div class="container-mobile" style="padding-bottom: 50px;min-height:500px;">
		<div class="row">
			<header class="page-header text-center">
				<h1>Settings</h1>
				@include('contents.user._alert-validation')
			</header>
			<br>
			<div class="col-md-12 text-center">
				<nav class="setting-nav">
					<ul>
						<li class="active">
							<a href="#"><i class="fa fa-puzzle-piece"></i> Basic Information</a>
						</li>

						<li>
							<a href="{{ route('user.getSettingProfile',$user->username) }}"><i class="fa fa-music"></i> Profile Content</a>
						</li>

						<li>
							<a href="{{ route('user.getSettingSocial',$user->username) }}"><i class="fa fa-twitter"></i> Social Account</a>
						</li>
						<li>
							<a href="{{ route('user.getSettingSecurity',$user->username) }}"><i class="fa fa-user-secret"></i> Security</a>
						</li>
					</ul>
				</nav>
			</div>

			<div class="col-md-12" style="padding: 0 40px;">
				{!! Form::open(['id'=>'form-setting', 'route' => array('user.putSetting', Auth::user()->username)]) !!}

					{!! Form::hidden('avatar',$user->avatar) !!}
					{!! Form::hidden('cover',$user->cover) !!}
					{!! Form::hidden('facebook',$user->facebook) !!}
					{!! Form::hidden('twitter',$user->twitter) !!}
					{!! Form::hidden('bio',$user->bio) !!}

					<div class="form-group">
						<label>Full Name</label>
						{!! Form::text('name',$user->name,['class'=>'form-control','placeholder'=>'Make it look good vroh','required']) !!}
					</div>
					<div class="form-group">
						<label>Quotes</label>
						{!! Form::text('quotes',$user->quotes,['class'=>'form-control input-text-wrap','placeholder'=>'Like when you tweet something']) !!}
					</div>
					<div class="form-group">
						<label>Username</label>
						<div class="input-group">
							{!! Form::text('username',$user->username,['class'=>'form-control','required','id'=>'username','placeholder'=>'ketik username disini...']) !!}
							<span class="input-group-btn">
								<button type="button" id="btn-check-username" class="btn btn-default">
									<i class="fa fa-refresh"></i> Check Username
								</button>
							</span>
						</div>
						<strong id="alert-username">

						</strong>
					</div>

					<div class="form-group">
						<label>Email</label>
						{!! Form::email('email',$user->email,['class'=>'form-control','disabled']) !!}
					</div>

					<div class="form-group">
						<label>Mobile Phone</label>
						<div class="input-group">
							<span class="input-group-addon" id="phone-addon">+62</span>
							<input type="number" name="phone" id="phone" value="{{ $user->phone }}" class="form-control" placeholder="ex : 62883 . ." aria-describedby="phone-addon">
						</div>
					</div>

					<div class="form-group">
						<p class="text-success" id="success-save">

						</p>
						<button type="submit" class="btn btn-lg btn-primary btn-block">Save</button>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-md" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Crop image</h4>
	      </div>
	      <div class="modal-body">
	        <img src="" alt="" id="avatar-crop">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="tutorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-md" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Soundcloud Playlist tutorial</h4>
	      </div>
	      <div class="modal-body">
	        <img src="{{ asset('images/soundcloud-tutorial.jpg') }}" alt="" class="img-responsive">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
@stop
@section('scripts')
	<script src="{{ asset('js/user-setting.js') }}"></script>
@stop
