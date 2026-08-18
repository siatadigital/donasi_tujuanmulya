@extends('layouts.default')

@section('head')
	<link rel="stylesheet" href="{{ asset('css/project-support.css') }}">
@stop
@section('content')
	<div class="container" ng-app="support" ng-controller="defaultController" style="padding-bottom: 50px;">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<header class="page-header text-center">
					<h1>{{ $project['title'] }}</h1>
					<p>{{ $project['summary'] }}</p>
				</header>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				@include('partials.alert-error')
				{!! Form::open(['url' => route('project.postSupport', $project['slug'])]) !!}
				@if(! Auth::check())
					<div class="form-group">
						<a href="{{ URL::Route('auth.getLogin') }}" class="btn btn-primary btn-lg btn-block">Login Terlebih Dahulu</a>
						<br>
						<p class="text-center">Anda tetap dapat menyumbang tanpa harus login.</p>
					</div>
					<hr>
				@endif
				<div id="next3">
					<section id="support-paper">
						<div class="form-group">
							<label for="money">Jumlah Dukungan Anda</label>
							{!! Form::text('money','100000',['class'=>'form-control input-lg','id'=>'money','ui-money-mask','ng-model'=>'money','ui-mask'=>'50.000','readonly'=>'readonly']) !!}
						</div>
					</section>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div id="next1">
					<header class="text-center">
						<h3>Pilih Metode Pembayaran</h3>
					</header>
					<select name="bank" class="image-picker" id="bank">
						<option data-img-src="{{ asset('images/bca.png') }}" value="bca"></option>
						<!-- <option data-img-src="{{ asset('images/mandiri.png') }}" value="mandiri"></option> -->
						<!-- <option data-img-src="{{ asset('images/danamon.png') }}" value="danamon"></option> -->
						{{-- <option data-img-src="{{ asset('images/bni.png') }}" value="bni"></option>
						<option data-img-src="{{ asset('images/bri.png') }}" value="bri"></option> --}}
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-md-offset-3" id="select-reward">
				<div id="next2">
					<header class="text-center">
						<h3>Pilih Rewards</h3>
					</header>
						<!-- <div class="radio radio-custom">
							<label>
								<input type="radio" name='reward_id' value="0" id="ikhlas" checked="true">
								<h4>Saya tidak ingin rewards</h4>
								Saya ikhlas menyumbang tidak ingin rewards.
							</label>
						</div> -->
					@foreach($project->rewards as $rew)
						<div class="radio radio-custom">
							<label>
								<?php !isset($_GET['reward']) ? $_GET['reward']=0 : ''; ?>
								@if ($_GET['reward'] == $rew['id'])
								{!! Form::radio('reward_id', $rew['id'], isset($_GET['reward'])?$_GET['reward']:null,['data-price'=>$rew['price'],'class'=>'theRadio','selected'=>'selected']) !!}
								@else
								{!! Form::radio('reward_id', $rew['id'], null,['data-price'=>$rew['price'],'class'=>'theRadio']) !!}
								@endif
								<h4>{{ priceFormat($rew['price']) }}</h4>
								{{ $rew['content'] }}
							</label>
						</div>
					@endforeach

					@if(Auth::check())
						<button type="submit" class="btn btn-primary btn-lg btn-block">
							Selanjutnya
						</button>
					@else
						<button type="button" class="btn btn-primary btn-lg btn-block" id="next">
							Selanjutnya
						</button>
					@endif
				</div>

				<div id="unauth">
					<header>
						<h2>Informasi Pribadi</h2>
						<p>Kami membutuhkan data pribadi anda dan contact anda agar kami dapat memproses dan mencatat data sumbangan anda.</p>
					</header>
					<div class="form-group">
						<label for="name">Nama</label>
						<input type="text" class="form-control input-lg" id="name" name="name" placeholder="Nama Lengkap">
					</div>

					<div class="form-group">
						<label for="name">Nomor Hp</label>
						<input type="text" class="form-control input-lg" id="phone" name="phone" placeholder="Nomor Handphone Aktif">
					</div>

					<div class="form-group">
						<label for="name">Email</label>
						<input type="email" class="form-control input-lg" id="email" name="email" placeholder="Alamat Email">
					</div>

					<div class="form-group">
						<label for="referal">Referal</label>
						<input type="text" class="form-control input-lg" id="referal" name="referal" placeholder="Nama / Email Referal">
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-primary btn-lg btn-block" id="processunauth">
							Proses Dukungan
						</button>
					</div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
@stop
@section('scripts')
	<script src="{{ asset('js/project-support.js') }}"></script>
	<script src="{{ asset('lib/angular/angular.min.js') }}"></script>
	<script src="{{ asset('lib/angular/lang/id.min.js') }}"></script>
	<script src="{{ asset('lib/angular-input-masks/angular-input-masks-standalone.min.js') }}"></script>
	<script>
		var app = angular.module("support",["ui.utils.masks"]);
		app.controller("defaultController",function($scope, $http){

		});

		$(function(){
			$("#unauth").hide();
			$("#money").keyup(function() {
				var money = $(this).val().replace("Rp ","");
				money = parseInt(money.replace(/\./g,""))
				theRadio(money)
			})

			$(window).load(function(){
				rewardChange();
			})

			function rewardChange() {
				var reward = $('#select-reward input[name="reward_id"]:checked').parent('label').children('h4').html();
				$('#money').val(reward);
			}

			$('#select-reward input[name="reward_id"]').change(function(){
				if ($(this).is(":checked")) {
					$('#select-reward input[name="reward_id"]').prop('checked',false);
					$(this).prop('checked',true);
					rewardChange();
				}
			})

			$("#processunauth").on('click',function(e){

				if ($("#name").val() == "" || $("#phone").val() == "" || $("#email") == ""){
					e.preventDefault();
					swal("Error!","Anda tidak dapat mengosongi Nama, Nomor Hp, dan Email","error");
				}
			})

			$("#next").on('click',function(){
				$("#next1, #next2, #next3").fadeOut(500);
				$("#unauth").fadeIn(600);
			})

			function theRadio(money) {
				/*console.log(money);
				$(".theRadio").each(function() {
					if ( parseInt($(this).data('price')) <= money ) {
						$(this).prop("disabled",false)
					}else{
						$(this).prop("disabled",true)
					}
				})*/
			}
		})
	</script>
@stop
