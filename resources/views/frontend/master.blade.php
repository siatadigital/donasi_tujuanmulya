<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
  @hasSection('meta')
    @yield('meta')
  @else
	  <title>{{ $settings['WEB_TITLE'] }}</title>
    <meta name="description" content="{{ $settings['WEB_DESCRIPTION'] }}">

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $settings['WEB_TITLE'] }}" />
    <meta property="og:site_name" content="{{ $settings['WEB_TITLE'] }}">
    <meta property="og:description" content="{{ $settings['WEB_DESCRIPTION'] }}" />
    <meta property="og:image" itemprop="image" content="{{ $settings['WEB_LOGO'] }}" />
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">
  @endif
  <meta name="msapplication-TileImage" content="{{ $settings['WEB_LOGO'] }}">
  <meta property="article:author" content="{{ $settings['WEB_TITLE'] }}" />
  <meta property="article:publisher" content="{{ $settings['WEB_TITLE'] }}" />
  <meta name="fb:app_id" content="{{ $settings['FB_APP_ID'] }}">
  <meta name="keywords" content="{{ $settings['WEB_KEYWORD'] }}">
  <meta name="author" content="Garry Priambudi Yunior">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/RTL_Logo.png') }}"/>
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap" rel="stylesheet">
	<link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('vendor/fontawesome/css/all.min.css') }}"
  />
	<link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('vendor/owlcarousel/assets/owl.carousel.min.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('vendor/owlcarousel/assets/owl.theme.default.min.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('vendor/select2/css/select2.min.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('vendor/bootstrap-daterangepicker/daterangepicker.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('css/styles.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('css/custom.css') }}"
  />
  <link
    rel="stylesheet"
    type="text/css"
    media="screen"
    href="{{ url('css/modern-font.css') }}"
  />
</head>
<body>
  @hasSection('header')
    @yield('header')
  @else
    @include('frontend.partials.header')
  @endif

  @yield('content')

  @include('frontend.partials.footer')

  <script src="{{ url('js/jquery.min.js') }}"></script>
  <script src="{{ url('js/moment.min.js') }}"></script>
  <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <script src="{{ url('vendor/jquery-validation-1.19.1/dist/jquery.validate.min.js') }}"></script>
  <script src="{{ url('vendor/owlcarousel/owl.carousel.min.js') }}"></script>
  <script src="{{ url('vendor/select2/js/select2.min.js') }}"></script>
  <script src="{{ url('admin-assets') }}/modules/sweetalert/sweetalert.min.js"></script>
  <script src="{{ url('vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ url('js/app.js') }}"></script>
  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function() {
      var s1 = document.createElement('script'),
        s0 = document.getElementsByTagName('script')[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/5d7c43aa9f6b7a4457e19a72/default';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
  <!--End of Tawk.to Script-->

  <!-- Firebase Config -->
  <!-- <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.3.0/firebase-analytics.js"></script>
  <script>
    var firebaseConfig = {
      apiKey: "AIzaSyC7fTvEZOs9Y4g4XVmefIsdIAZG_QnXQEU",
      authDomain: "rumahtaslucu-71eef.firebaseapp.com",
      databaseURL: "https://rumahtaslucu-71eef.firebaseio.com",
      projectId: "rumahtaslucu-71eef",
      storageBucket: "rumahtaslucu-71eef.appspot.com",
      messagingSenderId: "431956529203",
      appId: "1:431956529203:web:de039d3ad5fd78de26d346",
      measurementId: "G-HVCLVFKMBM"
    };
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();
  </script> -->
  <!-- End of Firebase COnfig -->

  @yield('js')
  @if (session()->has('error'))
  <script>
    swal({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session("error") }}',
    });
  </script>
  @endif

  @if (session()->has('success'))
  <script>
    swal({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session("success") }}',
    });
  </script>
  @endif

</body>
</html>
