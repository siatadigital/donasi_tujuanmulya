<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon">
	<link rel="icon" href="{{ url('favicon.ico') }}" type="image/x-icon">
	<meta name="asset-url" content="{{ asset('images/') }}">
	<meta name="root-url" content="{{ URL::Route('page.getIndex') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	@include('admin::partials.head')
	@yield('styles')
</head>
<body class="@if($auth) skin-yellow-light sidebar-mini @else login-page @endif ">
	<div class="wrapper">

		@if($auth)
			@include('admin::partials.header')

			@section('side_left')
				@include('admin::partials.side_left')
			@show
		@endif


		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					{{ $tpl_title or @$title }}
					<small>{{ $tpl_description or @$description }}</small>
				</h1>
				{{-- @include(getBladeAdmin('partials.breadcrumb')) --}}
			</section>

			<section class="content">
				@include('admin::partials.message')

				@yield('content')
			</section>
		</div>

	</div>

	@include('admin::partials.script')
	@yield('scripts')

</body>
</html>