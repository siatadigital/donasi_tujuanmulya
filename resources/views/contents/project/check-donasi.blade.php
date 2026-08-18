@extends('layouts.default')

@section('head')

@stop

@section('content')
	<div class="container-mobile text-center" style="margin-top:50px;margin-bottom:100px;">
		<div class="row">
			@include('partials.alert-error')
			<div class="col-md-4 col-md-offset-4">
				<div style="max-width:230px; margin: 0 auto;">
					<div class="text-center">
						<h3>Cek dukungan</h3>
						<hr>
						<p>Silahkan masukkan <b>Email</b> dan <b>Kode dukungan</b> anda untuk melihat status anda.</p>
					</div>
					{!! Form::open(array('route' => 'project.postDonasi', 'method' => 'POST', 'class' => 'form form-horizontal')) !!}
						<div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" placeholder="Email anda" required>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="code" placeholder="Kode dukungan anda" required>
							</div>
							<button class="btn btn-primary" type="submit">Submit</button>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
@stop

@section('scripts')
@stop
