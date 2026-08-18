<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title')</title>

  <link rel="shortcut icon" type="image/png" href="{{ asset('img/RTL_Logo.png') }}"/>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/jqvmap/dist/jqvmap.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/summernote/summernote-bs4.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/owlcarousel2/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/owlcarousel2/dist/assets/owl.theme.default.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/datatables/datatables.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/izitoast/css/iziToast.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/bootstrap-daterangepicker/daterangepicker.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ url('admin-assets') }}/css/style.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/css/components.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/css/custom.css">
  <link rel="stylesheet" href="{{ url('css/modern-font.css') }}">
  @yield('css')
<!-- Start GA -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-94034622-3');
</script>
<!-- /END GA --></head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      @include('admin.partials.header')
      @include('admin.partials.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
            @yield('content')
        </section>
      </div>
      @yield('modal')
      @include('admin.partials.footer')
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ url('admin-assets') }}/modules/jquery.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/popper.js"></script>
  <script src="{{ url('admin-assets') }}/modules/tooltip.js"></script>
  <script src="{{ url('admin-assets') }}/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/moment.min.js"></script>
  <script src="{{ url('admin-assets') }}/js/stisla.js"></script>

  <!-- JS Libraries -->
  <script src="{{ url('admin-assets') }}/modules/jquery.sparkline.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/chart.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/owlcarousel2/dist/owl.carousel.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/summernote/summernote-bs4.js"></script>
  <script src="{{ url('admin-assets') }}/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/datatables/datatables.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/izitoast/js/iziToast.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/select2/dist/js/select2.full.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/sweetalert/sweetalert.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script src="{{ url('admin-assets') }}/modules/tinymce/js/tinymce/tinymce.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/cleave-js/dist/cleave.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/lodash.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/Sortable.min.js"></script>

  <!-- Template JS File -->
  <script src="{{ url('admin-assets') }}/js/scripts.js"></script>
  <script src="{{ url('admin-assets') }}/js/custom.js"></script>

  @yield('js')
</body>
</html>
