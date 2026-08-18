@extends('frontend.master')

@section('content')
<div class="container">
  <div class="history-main">
    <div class="row">
      @include('frontend.partials.sidebar')

      <div class="col-md-9">
        <div class="history-list-table">
          <div class="sub-title">
            Pengaturan Account
          </div>
          <form method="POST" action="" class="ubah-pass-form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="form-group">
              @if ($errors->has('old_password'))
                <span style="color: red;">{{ $errors->first('old_password') }}</span>
              @endif
              <input type="password" id="password-lama" name="old_password" class="form-control"
                placeholder="Password Lama">
              <label class="norm-label">Password Lama</label>
            </div>
            <div class="form-group">
              @if ($errors->has('password'))
                <span style="color: red;">{{ $errors->first('password') }}</span>
              @endif
              <input type="password" id="password-baru" name="password" class="form-control"
                placeholder="Password Baru">
              <label class="norm-label">Password Baru</label>
            </div>
            <div class="form-group">
              <input type="password" id="konfirm-password-baru" name="password_confirmation" class="form-control"
                placeholder="Konfirmasi Password Baru">
              <label class="norm-label">Konfirmasi Password Baru</label>
            </div>
            <input type="submit" class="btn" value="Ubah Password">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
