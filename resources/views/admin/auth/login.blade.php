
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; RTL</title>

  <link rel="shortcut icon" type="image/png" href="{{ asset('img/RTL_Logo.png') }}"/>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ url('admin-assets') }}/modules/bootstrap-social/bootstrap-social.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ url('admin-assets') }}/css/style.css">
  <link rel="stylesheet" href="{{ url('admin-assets') }}/css/components.css">
  <link rel="stylesheet" href="{{ url('css/modern-font.css') }}">
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
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="{{ url('img') }}/RTL_Logo.png" alt="logo" width="100" class="shadow-light rounded-circle">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Login</h4></div>

              <div class="card-body">
                <form method="POST" action="{{ route('admin.auth.authenticate') }}" class="needs-validation" novalidate="">
                  {{ csrf_field() }}
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                    @if ($errors->first('username'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('username') }}</div>
                    @endif
                  </div>

                  <div class="form-group">
                    <label for="password" class="control-label">Kata Sandi</label>
                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                    @if ($errors->first('password'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
                    @endif
                  </div>

                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Login
                    </button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ url('admin-assets') }}/modules/jquery.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/popper.js"></script>
  <script src="{{ url('admin-assets') }}/modules/tooltip.js"></script>
  <script src="{{ url('admin-assets') }}/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="{{ url('admin-assets') }}/modules/moment.min.js"></script>
  <script src="{{ url('admin-assets') }}/js/stisla.js"></script>
  <script src="{{ url('admin-assets') }}/modules/sweetalert/sweetalert.min.js"></script>

  <!-- JS Libraies -->

  <!-- Page Specific JS File -->

  <!-- Template JS File -->
  <script src="{{ url('admin-assets') }}/js/scripts.js"></script>
  <script src="{{ url('admin-assets') }}/js/custom.js"></script>

  @if (session()->has('error'))
  <script>
    swal({
        icon: 'error',
        title: 'Gagal',
        text: '{{ session("error") }}',
    });
  </script>
  @endif
</body>
</html>
