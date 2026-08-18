@extends('admin::layouts.default')

@section('content')

<div class="box box-default">
	{{-- <div class="box-header with-border">
			<i class="fa fa-gears"></i>
			<h3 class="box-title"></h3>
		</div> --}}
	<div class="box-body">

		{!! Form::model($option, ['method' => 'PUT', 'url' => route('admin.page.putCustomPage')]) !!}

		<h4>Konten Halaman</h4>

		<div class="form-group">
			{!! Form::label('syarat_ketentuan', 'Syarat dan Ketentuan') !!}
			{!! Form::textarea('syarat_ketentuan', null, ['class' => 'form-control summernote', 'rows' => 4]) !!}
		</div>

		<div class="form-group">
			{!! Form::label('bantuan', 'Bantuan') !!}
			{!! Form::textarea('bantuan', null, ['class' => 'form-control summernote', 'rows' => 4]) !!}
		</div>

		<div class="form-group">
			{!! Form::label('tentang', 'Tentang Kami') !!}
			{!! Form::textarea('tentang', null, ['class' => 'form-control summernote', 'rows' => 4]) !!}
		</div>

		<hr>
		<div class="form-group">
			<button class="btn btn-primary"> Simpan </button>
		</div>

		{!! Form::close() !!}

	</div>
</div>

@stop
@section('scripts')
<script>
	$(document).ready(function() {
		$('.summernote').summernote({
			height: 300
		});
	});
</script>
@stop