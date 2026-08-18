<!DOCTYPE html>
<html>
<head>
	@include('admin::partials.head')
</head>
<body class="login-page">

	<div class="login-box">

		@include('partials.alert-error')
		@include('admin::partials.message')

		<div class="login-logo">
			Login
		</div><!-- /.login-logo -->

		<div class="login-box-body">
			<p class="login-box-msg">Sign in to enter administration page</p>
			{!! Form::open(['url' => route('admin.user.postLogin'), 'method' => 'POST']) !!}
				<div class="form-group has-feedback">
					{!! Form::email('email', null, ['class' => 'form-control', 'autofocus' => 1]) !!}
					<span class="glyphicon glyphicon-envelope form-control-feedback" style="margin-right:6px;font-size:16px;"></span>
				</div>
				<div class="form-group has-feedback">
					<div class="input-row">
						{!! Form::password('password', ['class' => 'form-control reveal-password']) !!}
						<button class="button-input" type="button">
							<i class="fa fa-eye"></i>
						</button>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="checkbox">
							<label>
								{!! Form::checkbox('remember', 1, null) !!}
								Remember Me
							</label>
						</div>
					</div><!-- /.col -->
					<div class="col-xs-4">
						<button type="submit"type="submit" class="btn btn-primary btn-block btn-flat">
							Sign In
						</button>
					</div><!-- /.col -->
				</div>
			{!! Form::close() !!}

		</div><!-- /.login-box-body -->
	</div><!-- /.login-box -->

	@include('admin::partials.script')
</body>
</html>
