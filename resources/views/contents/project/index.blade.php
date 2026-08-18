@extends('layouts.default')
@section('title',trans('project.title'))
@section('head')
<link rel="stylesheet" href="{{ asset('css/homepage1.3.css') }}">
<link rel="stylesheet" href="{{ asset('css/project-list1.1.css') }}">
@stop
@section('content')
<section class="filter-bar">
	<div class="container-mobile">
		<div class="row">
			<div class="col-md-12">
				{!! customDropdown('kategory', trans('project.semua_kategori'), $category, 'category_name', 'category_name', $selected_kategory, 'class="form-control" id="category"') !!}
			</div>
			<div class="col-md-12">
				{!! customDropdown('lokasi', trans('project.semua_lokasi'), $provinsi, 'provinsi_name', 'provinsi_name', $selected_provinsi, 'class="form-control" id="location"') !!}
			</div>
			<div class="col-md-12">
				{!! customDropdown('sort', trans('project.tampilkan_semua'), $sort, 'value', 'label', $selected_sort, 'class="form-control" id="sort"') !!}
			</div>
			<div class="col-md-12">
				<button type="button" id="btnFilter" class="btn btn-primary btn-block"><i class="fa fa-search"></i> {{ trans('project.cari_galang_dana') }}</button>
			</div>
		</div>
	</div>
</section>

<div class="project-list-content">
	<div class="container-mobile">
		@include('contents.project._list')
		<div class="text-center">
			{!! $projects->appends(Input::all())->render() !!}
		</div>
	</div>
</div>
@stop
@section('scripts')
<script src="{{ asset('js/homepage.js') }}"></script>
<script>
	fbq('track', 'ViewContent', {
		content_name: 'campaign_list',
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.open-modal').click(function() {
			var target = $(this).attr('data-target');

			$('#' + target + '.modal-box').fadeIn(300);
			// $('body').css('overflow', 'hidden');
		});

		$('.modal-box .modal-box-bg').click(function() {
			$('.modal-box').fadeOut(300);
			// $('body').css('overflow', 'auto');
		});

		$('.modal-box .modal-box-section-close').click(function() {
			$('.modal-box').fadeOut(300);
			// $('body').css('overflow', 'auto');
		});

	});
</script>
@stop