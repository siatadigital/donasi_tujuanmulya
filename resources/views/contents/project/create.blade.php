@extends('layouts.default')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/project-create.css') }}">
	<script src="{{ asset('js/project-create.js') }}"></script>
@stop
@section('content')
	<div class="container-mobile" style="padding: 20px 20px 50px;" ng-app="step1" ng-controller="defaultController">
		<div ng-controller="defaultController">
			@include('partials.alert-error')
			{!! Form::open(['id'=>'save-all','autocomplete'=>'off']) !!}
				@include('contents.project._form')
			{!! Form::close() !!}
		</div>
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
	<script src="{{ asset('lib/angular/angular.min.js') }}"></script>
	<script src="{{ asset('lib/angular/lang/id.min.js') }}"></script>
	<script src="{{ asset('lib/angular-input-masks/angular-input-masks-standalone.min.js') }}"></script>
	<script>
		var app = angular.module("step1",["ui.utils.masks"]);
		app.controller("defaultController",function($scope, $http){
			$scope.money = '';
		});
		
		$(function(){
			$("#username").keyup(function(){
				if($(this).val().indexOf(" ") >= 0)
				{
					$(this).val($(this).val().replace(" ",""));
				}

				// Replace symbols with underscore
				var username = $(this).val();
				if (username.match(/[-!$%^&*()+|~=`{}\[\]:";'<>?,.\/]/g)){
					$(this).val(username.replace(/[-!$%^&*()+|~=`{}\[\]:";'<>?,.\/]/g, "_"));
				}
			});
		});
	</script>
@stop

@section('scripts')
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'project_create_page',
		});
		function custom_slugs() {
			var x = document.getElementById("custom_slug");
			x.value = x.value.replace(/\s+/g, '-').toLowerCase();
		}
	</script>
@stop