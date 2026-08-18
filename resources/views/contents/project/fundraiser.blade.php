@extends('layouts.default')
@section('head')
  <script>
    $(function(){
      $("form").on('submit',function(e){
        var title = $("#title").val(),
            description = $("#description").val(),
            project_id = parseInt($("#project_id").val());

        if( title == '' || description == '' || project_id == '' || project_id == 0) {
          swal("Terjadi Kesalahan!",'Anda tidak diperbolehkan mengosongi field ataupun project.',"error");
          $("#project_id").focus();
          e.preventDefault();
        }
      })
    })
  </script>
@stop
@section('content')
  <div class="container-mobile" style="padding: 20px;margin-bottom:100px;">
    <header class="page-header text-center">
      <h2>Galang Dana Sebagai Fundraiser</h2>
      <p>Dengan menjadi Fundraiser, Anda membantu menggalang dana ke penggalangan <strong>"{{ $project['title'] }}"</strong>.<br><strong>Donasi hanya bisa dicairkan oleh {{ $project['user']['name'] }}</strong><br>Fundraiser tidak dapat mencairkan dana untuk sebuah penggalangan.</p>
    </header>


    {!! Form::open() !!}

      <div class="form-group">
        <label for="goal">Target Galang Dana</label>
          {!! Form::text('money_target', null, ['class'=>'form-control input-lg','id'=>'money','ui-money-mask','ng-model'=>'money','ui-mask'=>'50.000','required'=>'required']) !!}
        <small>{{ trans('create_project.target_placeholder') }}</small>
      </div>
      <div class="form-group">
        <label>Judul Galang Dana</label>
        {!! Form::text('title','',['class'=>'form-control','Judul','required','id'=>'title']) !!}
      </div>
      <div class="form-group">
        <label for="slug">Link Galang Dana</label>
        <div class="input-group">
          <span class="input-group-addon">tujuanmulia.id/</span>
          <input type="text" name="slug" class="form-control" id="custom_slug" onkeyup="custom_slugs(this)">
        </div>
      </div>
      <div class="form-group text-center">
        <button type="submit" class="btn btn-lg btn-primary" id="submit">Jadi Fundraiser</button>
      </div>
    {!! Form::close() !!}
  </div>
@stop

@section('scripts')
	<script src="{{ asset('lib/angular/angular.min.js') }}"></script>
	<script src="{{ asset('lib/angular/lang/id.min.js') }}"></script>
	<script src="{{ asset('lib/angular-input-masks/angular-input-masks-standalone.min.js') }}"></script>
	<script>
		fbq('track', 'ViewContent', {
			content_name: 'fundraiser_create_page',
		});
		
		function custom_slugs() {
			var x = document.getElementById("custom_slug");
			x.value = x.value.replace(/\s+/g, '-').toLowerCase();
    }
		var app = angular.module("fundraiser",["ui.utils.masks"]);
		app.controller("defaultController",function($scope, $http){
			$scope.money = '';
		});
	</script>
@stop