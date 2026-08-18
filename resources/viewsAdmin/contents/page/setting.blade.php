@extends('admin::layouts.default')

@section('content')

	<div class="box box-default">
		{{-- <div class="box-header with-border">
			<i class="fa fa-gears"></i>
			<h3 class="box-title"></h3>
		</div> --}}
		<div class="box-body">

			{!! Form::model($option, ['method' => 'PUT', 'url' => route('admin.page.putSetting')]) !!}

				<h4>General Settings</h4>

				<div class="form-group">
					{!! Form::label('site_quotes', 'Meta Description') !!}
					{!! Form::textarea('site_quotes', null, ['class' => 'form-control', 'rows' => 4]) !!}
				</div>

				<div class="form-group">
					{!! Form::label('transaksi_city_input', 'Transaksi Input Kota') !!}<br/>
					<select name="transaksi_city_input" class="form-control">
						@if ($option['transaksi_city_input'] == "true")
						<option value="true" selected>Aktif</option>
						@else
						<option value="true">Aktif</option>
						@endif
						@if ($option['transaksi_city_input'] == "false")
						<option value="false" selected>Non-Aktif</option>
						@else
						<option value="false">Non-Aktif</option>
						@endif
					</select>
				</div>

				<!-- <div class="form-group">
					{!! Form::label('official_address', 'Official Adress') !!}
					{!! Form::textarea('official_address', null, ['class' => 'form-control', 'rows' => 4]) !!}
				</div>

				<br>
				<h4>Social Media Settings</h4>

				<div class="form-group">
					{!! Form::label('official_facebook', 'Official Facebook') !!}
					{!! Form::text('official_facebook', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('official_twitter', 'Official Twitter') !!}
					{!! Form::text('official_twitter', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('official_instagram', 'Official Instagram') !!}
					{!! Form::text('official_instagram', null, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('official_gplus', 'Official Google Plus') !!}
					{!! Form::text('official_gplus', null, ['class' => 'form-control']) !!}
				</div> -->

				<hr>
				<div class="form-group">
					<button class="btn btn-primary"> Save </button>
				</div>

			{!! Form::close() !!}

		</div>
	</div>

@stop