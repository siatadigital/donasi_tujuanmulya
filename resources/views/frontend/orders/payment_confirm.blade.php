@extends('frontend.master')

@section('header')
<header class="navbar fixed-top navbar-expand-lg">
  <div class="container">
    <div class="top-nav with-border">
      <a href="{{ route('frontend.home') }}" class="web-logo mx-auto">
        <img src="{{ url('img/RTL_Logo.png') }}">
      </a>
    </div>
  </div>
</header>
@endsection

@section('content')
<div class="konfirmasi-pembayaran-main">
  <div class="container">
    <h1 class="konfirmasi-pembayaran-head">
      Konfirmasi Pembayaran
    </h1>
    <p class="konfirmasi-pembayaran-texts">
      Admin akan mengkonfirmasi pembayaran dalam kurun waktu <b>1x24</b> Jam. Jika anda belum mendapatkan pemberitahuan dalam jangka waktu tersebut, harap menghubungi admin kami melalui email <u>rumahtaslucuRTL@gmail.com</u>
    </p>

    <form method="POST" action="" class="konfirmasi-pembayaran-form" enctype="multipart/form-data">
      {{ csrf_field() }}
      <div class="form-group">
        @if ($errors->has('from_account_name'))
        <span style="color: red;">{{ $errors->first('from_account_name') }}</span>
        @endif
        <input type="text" id="konfirm-namaLengkap" name="from_account_name" class="form-control"
        placeholder="Nama Pen-transfer" value="{{ $payment ? $payment->from_account_name : '' }}">
        <label class="norm-label">Nama Pen-transfer</label>
      </div>
      <div class="form-group">
        @if ($errors->has('bank_id'))
        <span style="color: red;">{{ $errors->first('bank_id') }}</span>
        @endif
        <select class="form-control" name="bank_id">
          <option value="" selected>-- Pilih Bank --</option>
          @foreach($banks as $bank)
          @if($payment && $payment->bank_id === $bank->id)
          <option value="{{ $bank->id }}" selected>{{ $bank->bank_name }}</option>
          @else
          <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
          @endif
          @endforeach
        </select>
        <i class="select-icon fas fa-angle-down"></i>
        <label class="norm-label show">Nama Bank</label>
      </div>
      <div class="form-group">
        @if ($errors->has('from_amount_transfer'))
        <span style="color: red;">{{ $errors->first('from_amount_transfer') }}</span>
        @endif
        <input type="number" id="konfirm-nominal" name="from_amount_transfer" class="form-control no-spinner"
        placeholder="Nominal Transfer" value="{{ $payment ? $payment->from_amount_transfer : '' }}">
        <label class="norm-label">Nominal Transfer</label>
      </div>
      <div class="form-group">
        @if ($errors->has('date'))
        <span style="color: red;">{{ $errors->first('date') }}</span>
        @endif
        <input type="text" id="date" name="date" class="form-control"
        placeholder="Tanggal Transfer">
        <label class="norm-label">Tanggal Transfer</label>
      </div>
      <div class="dashed-title">
        <span>Bukti Transfer</span>
      </div>
      <div class="form-group upload-img" style="margin-bottom: 0px;">
        <img id="uploaded-img" src="{{ url('img/upload.png') }}">
        <input id="konfirm-buktiTf" name="photo" type="file" accept="image/*">
      </div>
      @if ($errors->has('photo'))
      <p class="text-center" style="color: red;">{{ $errors->first('photo') }}</p>
      @endif
      <p class="text-center" style="margin-top: 32px;">
        <input type="submit" class="btn" id="konfirm-submit" value="Konfirmasi Pembayaran" data-toggle="modal" data-target="#konfirm-bayar-modal">
      </p>
    </form>
  </div>
</div>
@endsection

@section('js')
<script>
$('#date').daterangepicker({
    singleDatePicker: true,
    locale: {
      format: 'DD-MM-YYYY'
    }
});
</script>
@endsection
