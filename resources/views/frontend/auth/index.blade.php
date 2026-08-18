@extends('frontend.master')

@section('content')
<div class="top-sign">
  <div class="container">
    <h2>Jadilah Member<br>Dan Dapatkan Keuntungannya</h2>
    <p>Nikmati banyak keuntungan yang kami tawarkan khusus hanya<br>kepada member Rumah Tas Lucu.</p>
  </div>
</div>

<div class="logreg-mian">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 col-md-12 forms-col">
        <div class="logreg-forms">
          <ul class="nav nav-pills nav-justified" id="logreg-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">Log In</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="regist-tab" data-toggle="tab" href="#regist" role="tab" aria-controls="regist" aria-selected="false">Buat Akun Baru</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
              <p class="top-text">Selamat Datang<br>Silahkan login menggunakan akun anda.</p>
              <form action="{{ route('frontend.auth.login_post') }}" method="post" id="login-form">
                {{ csrf_field() }}
                <div class="form-group">
                  <input type="email" id="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                  placeholder="Email...">
                  <label class="norm-label">Email</label>
                </div>
                <div class="form-group last">
                  <input type="password" id="password" name="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                  placeholder="Password...">
                  <label class="norm-label">Password</label>
                </div>
                <div class="ingat-lupa">
                  <div class="custom-control custom-checkbox ingat">
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Ingat saya</label>
                  </div>
                  <a class="lupa" href="{{ route('frontend.auth.forgot_password') }}">Lupa password?</a>
                </div>
                <input class="btn btn-pink w-100" type="submit" name="submit" value="Log In">
                <p>Atau login dengan</p>
                <a class="btn google w-100" href="{{ route('frontend.auth.login_social', ['provider' => 'google']) }}">
                  <img src="{{ url('img/google.png') }}">
                  <span>Login dengan Google</span>
                </a>
                <a class="btn facebook w-100" href="{{ route('frontend.auth.login_social', ['provider' => 'facebook']) }}">
                  <i class="fab fa-facebook-f"></i>
                  <span>Login dengan Facebook</span>
                </a>
              </form>
            </div>
            <div class="tab-pane fade" id="regist" role="tabpanel" aria-labelledby="regist-tab">
              <p class="top-text">Silahkan mengisi formulir dibawah<br>untuk menikmati layanan khusus Rumah Tas Lucu.</p>
              <form action="{{ route('frontend.auth.register_post') }}" method="post" id="regist-form">
                {{ csrf_field() }}
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Nama Depan">
                      <label class="norm-label">Nama Depan</label>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <input type="text"  id="lastname" name="lastname" class="form-control" placeholder="Nama Belakang">
                      <label class="norm-label">Nama Belakang</label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" id="reg_email" name="reg_email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                  placeholder="Email">
                  <label class="norm-label">Email</label>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control phone" id="phone" name="phone" aria-describedby=""
                  placeholder="Nomor Hp" maxlength="13">
                  <label class="norm-label">Nomor Hp</label>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" id="reg_password" name="reg_password" aria-describedby="emailHelp"
                  placeholder="Password">
                  <label class="norm-label">Password</label>
                </div>
                <div class="form-group last">
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" aria-describedby="emailHelp"
                  placeholder="Konfirmasi Password">
                  <label class="norm-label">Konfirmasi Password</label>
                </div>
                <input class="btn btn-pink w-100" type="submit" value="Buat Akun">
                <p>Atau mendaftar dengan</p>
                <a class="btn google w-100" href="{{ route('frontend.auth.login_social', ['provider' => 'google']) }}">
                  <img src="{{ url('img/google.png') }}">
                  <span>Daftar dengan Google</span>
                </a>
                <a class="btn facebook w-100" href="{{ route('frontend.auth.login_social', ['provider' => 'facebook']) }}">
                  <i class="fab fa-facebook-f"></i>
                  <span>Daftar dengan Facebook</span>
                </a>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 kelmem-col">
        <h2>Kelebihan Member</h2>
        <div class="kelmem-box">
          <div class="media">
            <div class="img-box">
              <img src="{{ url('img/img1.png') }}">
            </div>
            <div class="media-body">
              <h5>Banyak Diskon dan Promo</h5>
              <p>Member akan mendapatkan banyak Diskon dan Promo yang tidak di dapatkan oleh pengguna non-mebership</p>
            </div>
          </div>
        </div>
        <div class="kelmem-box">
          <div class="media">
            <div class="img-box">
              <img src="{{ url('img/img2.png') }}">
            </div>
            <div class="media-body">
              <h5>Beli Sekarang, Bayar Nanti</h5>
              <p>Anda dapat membeli barang dengan pembayaran maksimal 3 hari setelah barang dibeli.</p>
            </div>
          </div>
        </div>
        <div class="kelmem-box">
          <div class="media">
            <div class="img-box">
              <img src="{{ url('img/img3.png') }}">
            </div>
            <div class="media-body">
              <h5>Menjadi Re-seller</h5>
              <p>Setiap member dapat menjadi reseller dengan mendapatkan harga yang lebih murah tanpa minimum pembelian.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
