@extends('layouts.default')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/project-create.css') }}">
	<script src="{{ asset('js/project-create.js') }}"></script>
@stop
@section('content')
	<div class="container-mobile" style="padding-bottom:50px;" ng-app="step1" ng-controller="defaultController">
		@include('partials.alert-error')
		{!! Form::model($project, ['id'=>'save-all', 'autocomplete'=>'off', 'method' => 'PUT', 'url' => route('project.putEdit', $project['slug'])]) !!}
			@include('contents.project._form')
		{!! Form::close() !!}
	</div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
	      </div>
	      <div class="modal-body">

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'project_edit_page',
		});
		function custom_slugs() {
			var x = document.getElementById("custom_slug");
			x.value = x.value.replace(/\s+/g, '-').toLowerCase();
		}
	</script>
@stop
