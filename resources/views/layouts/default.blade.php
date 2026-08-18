<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="facebook-domain-verification" content="drna6ndqsydehgqtsn6p3sjkz3y1uc" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="{{ url('favicon.ico') }}" type="image/x-icon">
	<link rel="icon" href="{{ url('favicon.ico') }}" type="image/x-icon">
	<meta name="asset-url" content="{{ url('images/logo-nh.jpg') }}">
	<meta name="root-url" content="{{ URL::Route('page.getIndex') }}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta content="@section('title') {{ $title or '' }} tujuanmulia.id - @show" property="og:title" />
	<meta content="tujuanmulia.id" property="og:site_name" />
	<title>tujuanmulia.id - @section('title') {{ $title or '' }} @show</title>
	<link href="{{ asset('lib/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
	<link href="{{ asset('lib/owl-carousel/owl.theme.css') }}" rel="stylesheet">
	<link href="{{ asset('lib/wowjs/animate.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('css/app1.2.css') }}">
	<link rel="stylesheet" href="{{ asset('css/homepage1.3.css') }}">
	<link href="{{ asset('css/plugins/cropper.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
	<script type="text/javascript" src="{{ asset('plugins/jquery.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/bootstrap.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/summernote.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/sweetalert.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/cropper.js') }}"></script>
	<script type="text/javascript" src="{{ asset('plugins/jquery-cropper.js') }}"></script>

	<meta name="description" content="{{ getOption('site_quotes') }}" />

	<meta property="og:locale" content="en_US" />
	<meta property="og:site_name" content="tujuanmulia.id" />
	<meta property="og:url" content="{{ url('/') }}" />
	<meta name="author" content="tujuanmulia.id">
	<meta property="og:title" content="tujuanmulia.id - @section('title') {{ $title or '' }} @show" />
	<meta property="og:description" content="{{ getOption('site_quotes') }}" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="{{ asset('images/logo.png') }}" />

	<meta name="twitter:site" content="{{ getOption('official_twitter') }}">
	<meta name="twitter:title" content="tujuanmulia.id - @section('title') {{ $title or '' }} @show">
	<meta name="twitter:description" content="{{ getOption('site_quotes') }}">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:image" content="{{ asset('images/logo.png') }}">
	@yield('head')

	<link rel="stylesheet" href="{{ asset('css/style1.css') }}">
	<link rel="stylesheet" href="{{ asset('css/custom1.1.css') }}">
	<link rel="stylesheet" href="{{ asset('css/modern-font.css') }}">

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-S2VZBSC6MR"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', 'G-S2VZBSC6MR');
	</script>

	<!-- Meta Pixel Code -->
	<script>
		! function(f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function() {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '312693487972724');
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=312693487972724&ev=PageView&noscript=1" /></noscript>

	<!-- End Meta Pixel Code -->
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-S2VZBSC6MR');
	</script>
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=G-S2VZBSC6MR"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<div class="container-mobile">
		@include('partials.alert-info')
		@include('partials.header')
		@yield('after-header')

		@yield('content')

		@include('partials.footer-menu')

		<!-- Modal -->
		<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Change Password</h4>
					</div>
					<div class="modal-body">
						{!! Form::open(['id'=>'changePasswordForm']) !!}
						<div class="form-group">
							<label>
								Current Password
							</label>
							<div class="input-row">
								{!! Form::password('current_password',['class'=>'form-control reveal-password','required','id'=>'current_password']) !!}
								<button class="button-input" type="button">
									<i class="fa fa-eye"></i>
								</button>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label>
								New Password
							</label>
							<div class="input-row">
								{!! Form::password('password',['class'=>'form-control reveal-password','required','id'=>'password']) !!}
								<button class="button-input" type="button">
									<i class="fa fa-eye"></i>
								</button>
							</div>
						</div>
						<div class="form-group">
							<label>
								Repeat Password
							</label>
							<div class="input-row">
								{!! Form::password('password_confirmation',['class'=>'form-control reveal-password','required','id'=>'password_confirmation']) !!}
								<button class="button-input" type="button">
									<i class="fa fa-eye"></i>
								</button>
							</div>
						</div>
						<p class="text-center">
							<button type="submit" class="btn btn-primary btn-lg">Save Password</button>
						</p>
						{!! Form::close() !!}
					</div>
					{{-- <div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
					</div> --}}
				</div>
			</div>
		</div>

	</div>

	@if (Route::currentRouteName() != 'blog.getIndex' and Route::currentRouteName() != 'blog.getShow')
	<div class="wa-box">
		<a target="_blank" href="https://api.whatsapp.com/send?phone=+6285711122646&text=Assalamualaikum, ingin bertanya tentang tujuanmulia.id." id="contact-wa">
			<i class="fa fa-whatsapp"></i>
		</a>
		<p>Chat WA</p>
	</div>
	@endif

	<script src="{{ asset('js/app.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
	<script defer src="{{ asset('lib/owl-carousel/owl.carousel.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('lib/wowjs/wow.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/custom.js') }}"></script>
	<script type="text/javascript">
		new WOW().init();
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#contact-wa').click(function() {
				var href = $(this).attr('href');
				fbq('track', 'Contact');
				window.open(href, '_blank');

				return false;
			});
			$("#slider-box").owlCarousel({
				pagination: false,
				navigation: false,
				slideSpeed: 300,
				paginationSpeed: 400,
				singleItem: true,
				autoPlay: true
			});
		});
	</script>
	@yield('scripts')
</body>

</html>
