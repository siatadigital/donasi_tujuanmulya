@extends('admin::layouts.default')
@section('head')
	<script src="{{ asset('js/blog-create.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/summernote.css') }}">
@stop
@section('content')

	<div class="nav-tabs-custom">

		<div class="tab-content">
			<form method="post" action="{{ url('/backend/popup/doa_zakat/update/'.$notif->id) }}" enctype="multipart/form-data">
				{{ csrf_field() }}
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label mb-10">Type</label>
						<input type="text" name='type' id="type" value="{{ $notif->type }}" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label mb-10">Template</label>
						<textarea name='value' id="summernote2" disabled>
							<p class="text-right">نَوَيْتُ أَنْ أُخْرِجَ زَكاَةَ اْللَالِ عَنْ نَفْسِيْ فَرْضًالِلهِ تَعَالَى</p>
							<p>Nawaitu an ukhrija zakata maali fardha llillahi ta'aala.</p>
							<p>Saya Niat Mengeluarkan Zakat Maal Dari Diriku Sendiri Fardhu Karena Allah Ta’ala</p>
						</textarea>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label mb-10">Content</label>
						<textarea name='value' id="summernote" required>{{ $notif->value }}</textarea>
					</div>
				</div>
				<p class="text-center">
					{!! Form::submit('Save',['class'=>'btn btn-primary btn-lg']) !!}
				</p>
      		</form>
		</div><!-- /.tab-content -->
	</div><!-- /.nav-tabs-custom -->

@stop
@section('scripts')
<script>
	$(document).ready(function() {
		$('#summernote').summernote({
			height:300
		});
		$('#summernote2').summernote({
			height:300
		});
	});
</script>
@stop