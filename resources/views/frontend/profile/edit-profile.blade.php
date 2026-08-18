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
          <form method="POST" action="" class="pengaturan-akun-form">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <div class="form-group">
              @if ($errors->has('fullname'))
                <span style="color: red;">{{ $errors->first('fullname') }}</span>
              @endif
              <input type="text" id="name" name="fullname" class="form-control" placeholder="Nama Lengkap" value="{{ $user->fullname }}">
              <label class="norm-label">Nama Lengkap</label>
            </div>
            <div class="form-group">
              @if ($errors->has('email'))
                <span style="color: red;">{{ $errors->first('email') }}</span>
              @endif
              <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ $user->email }}">
              <label class="norm-label">Email</label>
            </div>
            <div class="form-group pass-group">
              <input type="password" id="password" name="password" class="form-control" placeholder="Password" value="password" disabled>
              <label class="norm-label">Password</label>
              <a href="{{ route('frontend.profile.edit_password') }}">Ubah</a>
            </div>
            <div class="form-group">
              @if ($errors->has('phone'))
                <span style="color: red;">{{ $errors->first('phone') }}</span>
              @endif
              <input type="number" id="no-hp" name="phone" class="form-control no-spinner" placeholder="No. HP" value="{{ $user->phone }}">
              <label class="norm-label">No. HP</label>
            </div>
            <div class="dashed-title">
              <span>Alamat</span>
            </div>
            <div class="form-group">
              <input type="text" id="alamat" name="alamat" class="form-control"
                placeholder="Kecamatan - Kabupaten - Provinsi">
              <label class="norm-label">Alamat</label>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="address" id="detail-alamat" rows="3" placeholder="Detail Alamat">{{ $user->address }}</textarea>
              <label class="norm-label">Detail Alamat</label>
            </div>
            <input type="submit" class="btn" value="Simpan Perubahan">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
