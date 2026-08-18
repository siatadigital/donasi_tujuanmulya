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
@if ($sales->delivery->courier_id == 10)
<?php
    $isCash = $sales->payment->type == 'cash';
    $takeTime = $isCash ? '30 menit' : '1-2 hari';
?>
<!-- RTL PUSAT -->
<div class="container">
  <div class="pembayaran-main">
    <h1 class="pembayaran-head">
      Ambil di RTL Pusat
    </h1>
    <div class="pembayaran-pgf">
      <span>Mohon Segera Ambil Pesanan</span>
      <p>Anda memiliki waktu selama {{ $takeTime }} untuk mengambil pesanan di RTL Pusat. Jika lebih dari batas waktu, maka pesanan Anda otomatis akan dibatalkan oleh sistem dan lakukan pemesanan ulang atau menghubungi Customer Support untuk mengubah status pemesanan dari batal menjadi aktif kembali.</p>
    </div>
    @if ($isCash)
    <div class="pembayaran-waktu">
      <span id="interval" style="display: none;">{{ $minutes }}:{{ $seconds }}</span>
      <span id="total-seconds" style="display: none;">{{ $totalSeconds }}</span>
      <div class="time">
        <div id="menit" class="numbers">{{ $minutes }}</div>
        <span>menit</span>
      </div>
      <div class="dots">:</div>
      <div class="time">
        <div id="detik" class="numbers">{{ $seconds }}</div>
        <span>detik</span>
      </div>
    </div>
    @endif
    <div class="pembayaran-detail">
      <div class="detail-box">
        <div class="sub-title">Kode Pemesanan</div>
        <div class="value">{{ $sales->code }}</div>
      </div>
      <div class="salin-nominal">
        <a href="#" id="copy-code">Salin Kode Pemesanan</a>
      </div>
      <div class="detail-box">
        <div class="sub-title">Total Pembayaran</div>
        <div class="value">Rp. {{ number_format($totalPayment, 0, ',', '.') }}</div>
      </div>
      @if ($paidByDeposit)
      <div class="detail-box">
        <div class="sub-title">Terbayar Pakai Deposit</div>
        <div class="value">Rp. {{ number_format($paidByDeposit, 0, ',', '.') }}</div>
      </div>
      @endif
      @if ($isCash)
      <div class="tf-info">
        Silahkan mengambil pesanan Anda dan membayar langsung di RTL Pusat.
      </div>
      @else
      <div class="tf-info">
        Pembayaran telah dianggap lunas, silahkan mengambil pesanan Anda langsung di RTL Pusat
      </div>
      @endif
    </div>
  </div>
</div>
@else
<div class="container">
  <div class="pembayaran-main">
    <h1 class="pembayaran-head">
      Tagihan Pembayaran
    </h1>
    <div class="pembayaran-pgf">
      <span>Mohon Segera Selesaikan Pembayaran</span>
      <p>Anda memiliki waktu selama {{ $limitPaymentTime }} menit untuk menyelesaikan pembayaran. Jika lebih dari batas waktu, maka pesanan Anda otomatis akan dibatalkan oleh sistem dan lakukan pemesanan ulang atau menghubungi Customer Support untuk mengubah status pemesanan dari batal menjadi aktif kembali.</p>
    </div>
    <div class="pembayaran-waktu">
      <span id="interval" style="display: none;">{{ $minutes }}:{{ $seconds }}</span>
      <span id="total-seconds" style="display: none;">{{ $totalSeconds }}</span>
      <div class="time">
        <div id="menit" class="numbers">{{ $minutes }}</div>
        <span>menit</span>
      </div>
      <div class="dots">:</div>
      <div class="time">
        <div id="detik" class="numbers">{{ $seconds }}</div>
        <span>detik</span>
      </div>
    </div>
    <div class="pembayaran-detail">
      <div class="detail-box">
        <div class="sub-title">Kode Pemesanan</div>
        <div class="value">{{ $sales->code }}</div>
      </div>
      <div class="salin-nominal">
        <a href="#" id="copy-code">Salin Kode Pemesanan</a>
      </div>
      <div class="detail-box">
        <div class="sub-title">Kode Unik</div>
        <div class="value">{{ $sales->payment->unique_code }}</div>
      </div>
      <div class="detail-box">
        <div class="sub-title">Total Pembayaran</div>
        <div class="value">Rp. {{ number_format($totalPayment, 0, ',', '.') }}</div>
      </div>
      @if ($paidByDeposit)
      <div class="detail-box">
        <div class="sub-title">Terbayar Pakai Deposit</div>
        <div class="value">Rp. {{ number_format($paidByDeposit, 0, ',', '.') }}</div>
      </div>
      @endif
      <div class="detail-box small-marg">
        <div class="sub-title">Nominal Transfer</div>
        <div class="value">Rp. {{ number_format($amountTransfer, 0, ',', '.') }}</div>
      </div>
      <div class="salin-nominal">
        <a href="#" id="copy-amount">Salin Nominal</a>
      </div>
      <div class="tf-info">
        Harap mengirimkan uang sesuai nominal transfer hingga digit terakhir. Jika tidak sesuai, pesanan tidak dapat kami proses.
      </div>
      @if ($sales->payment->type === 'transfer' || $sales->payment->type === 'edc')
      <div class="detail-box mini">
        <div class="sub-title">Bank Tujuan</div>
      </div>
      <div class="detail-box">
        @if ($sales->payment->bank)
          <div class="sub-title">
            <img src="{{ $sales->payment->bank->getLogo() }}" width="128px">
          </div>
          <div class="value bank">
            <div class="no-rek">{{ $sales->payment->bank->account_number }}</div>
            <div class="nama-rek">a.n. {{ $sales->payment->bank->account_name }}</div>
            <a href="#" id="copy-account-number">Salin Nomor Rekening</a>
          </div>
        @endif
      </div>
      @endif
    </div>
    <a href="{{ route('frontend.order.payment_confirm', array('id' => $sales->id)) }}" class="btn pembayaran-btn">Saya sudah membayar</a>
  </div>
</div>
@endif
@endsection

@section('js')
<script>
$('#copy-code').on('click', function() {
    navigator.permissions.query({name: "clipboard-write"}).then(result => {
        if (result.state == "granted" || result.state == "prompt") {
            navigator.clipboard.writeText('{{ $sales->code }}');
            alert("Berhasil salin kode pemesanan");
        }
    });

    return false;
});

$('#copy-amount').on('click', function() {
    navigator.permissions.query({name: "clipboard-write"}).then(result => {
        if (result.state == "granted" || result.state == "prompt") {
            navigator.clipboard.writeText('{{ $amountTransfer }}');
            alert("Berhasil salin nominal transfer");
        }
    });

    return false;
});

@if ($sales->payment->type === 'transfer' || $sales->payment->type === 'edc')
$('#copy-account-number').on('click', function() {
    navigator.permissions.query({name: "clipboard-write"}).then(result => {
        if (result.state == "granted" || result.state == "prompt") {
            navigator.clipboard.writeText('{{ $sales->payment->bank->account_number }}');
            alert("Berhasil salin nomor rekening");
        }
    });

    return false;
});
@endif
</script>
@endsection
