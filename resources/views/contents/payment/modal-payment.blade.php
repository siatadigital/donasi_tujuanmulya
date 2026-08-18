<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">Transfer sesuai nominal dibawah ini:</h4>

<div class="">
  <?php
    $nominal = 0;
    if ($typeTransaction == "project") {
      $nominal = $data['unique_code'] ? $data['money'] + $data['unique_code'] : $data['money'];
      $money = $data['money'];
      $paymentMethod = $data['bank_type'];
    }else {
      $nominal = $data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount'];
      $money = $data['amount'];
      $paymentMethod = $data['payment_method'];
    }
  ?>
  @if($typeTransaction == "project")
    <div class="row text-center">
      <div class="col-md-6 col-md-offset-3" style="font-size: 25px;font-weight: bold;">{{ substr(priceFormat($nominal), 0, strlen(priceFormat($nominal)) - 3) }}<span style="color: #a68b4b;">{{ substr(priceFormat($nominal), strlen(priceFormat($nominal)) - 3, strlen(priceFormat($nominal))) }}</span></div>
      <div class="col-md-2" style="padding:10px;font-weight: bold;"><a href="#" id="nominal-salin">SALIN</a></div>
		</div><br>
			<input type="text" style="opacity: 0;margin-top: -30px;display: block;" id="nominal-value" value="{{ $nominal }}" />
  @else
    <div class="row text-center">
      <div class="col-md-6 col-md-offset-3" style="font-size: 25px;font-weight: bold;">{{ substr(priceFormat($nominal), 0, strlen(priceFormat($nominal)) - 3) }}<span style="color: #a68b4b;">{{ substr(priceFormat($nominal), strlen(priceFormat($nominal)) - 3, strlen(priceFormat($nominal))) }}</span></div>
      <div class="col-md-2" style="padding:10px;font-weight: bold;"><a href="#" id="nominal-salin">SALIN</a></div>
		</div><br>
			<input type="text" style="opacity: 0;margin-top: -30px;display: block;" id="nominal-value" value="{{ $nominal }}" />
  @endif

		<div class="alert alert-warning" role="alert">
			<table>
				<tr>
					<td valign="top"><i class="fa fa-warning"></i> </td>
					<td>&nbsp;&nbsp;&nbsp;</td>
          @if($typePayment == 'manual')
					<td><b>PENTING!</b> Mohon transfer tepat sampai 3 angka terakhir agar infak/zakat terverifikasi otomatis</td>
          @endif
          @if($typePayment == 'midtrans')
            @if($paymentMethod == "echannel")
              <td><b>PENTING!</b> Mohon transfer melalui Kode Pembayaran Multi Payment Mandiri agar infak/zakat terverifikasi otomatis</td>
            @elseif($paymentMethod == "other_va")
              <td><b>PENTING!</b> Mohon transfer melalui Menu Transfer sesuai dengan rekening tujuan (support semua bank) agar infak/zakat terverifikasi otomatis</td>
            @elseif($paymentMethod == "gopay")
              <td><b>PENTING!</b> Mohon transfer melalui aplikasi Gopay dengan scan QR Code dibawah atau redirect ke aplikasi agar infak/zakat terverifikasi otomatis</td>
            @else
              <td><b>PENTING!</b> Mohon transfer melalui Nomor Virtual Account (VA) agar infak/zakat terverifikasi otomatis</td>
            @endif
          @endif
        </tr>
			</table>
		</div>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="pull-right">{{ priceFormat($money) }}</span>
				Jumlah Infak/Zakat
			</li>
      @if($typePayment == 'manual')
			<li class="list-group-item">
				<span class="pull-right">{{ substr(priceFormat($nominal), strlen(priceFormat($nominal)) - 3, strlen(priceFormat($nominal))) }}</span>
				Kode Unik (*)
			</li>
      @endif
      @if($typePayment == 'midtrans')
      @endif
		</ul>
    @if($typePayment == 'manual')
		<p>* 3 angka terakhir akan di infak-kan/zakat-kan.</p>
    @endif
    @if($typePayment == 'midtrans')
    @endif
		<br>
    @if($typePayment == 'manual')
		<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
      Pembayaran dilakukan ke rekening a/n
    </h4>
    @endif
    @if($typePayment == 'midtrans')
      @if($paymentMethod == "echannel")
        <h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
          Pembayaran dilakukan ke Multi Payment Mandiri a/n
        </h4>
      @elseif($paymentMethod == "other_va")
        <h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
          Pembayaran dilakukan ke rekening a/n
        </h4>
      @elseif($paymentMethod == "gopay")
        <h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
          Pembayaran dilakukan dengan scan QRCode melalui aplikasi Gopay
        </h4>
      @else
        <h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
          Pembayaran dilakukan ke Virtual Account (VA) a/n
        </h4>
      @endif
    @endif

    @if($typePayment == 'manual')
    <h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
      <b> {{ config('web.bank.'.str_replace('transfer_','',$paymentMethod).'.name') }}</b>
    </h4>
    @endif
    @if($typePayment == 'midtrans')
    <h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">
      <b> {{ config('web.midtrans_name') }}</b><br>
      @if($paymentMethod == "echannel")
      (masukkan kode perusahaan <strong>{{ config('web.biller_code_mandiri') }}</strong>)
      @endif 
    </h4>
    @endif 
    
    @if($paymentMethod == "gopay")
    <div class="panel panel-default">
      <div class="panel-body text-center">
        <img src="{{ str_replace('KODE',$data['va_number'],config('web.midtrans_gopay_qrcode_url')) }}" width="300" />
        <br>
        <strong>Scan QR Code diatas melalui aplikasi Gopay</strong>
        <br>
        <a href="{{ $data['redirect_url'] }}" target="_blank"><strong>atau redirect otomatis ke aplikasi Gopay dengan klik disini</strong></a>
      </div>
    </div>
    @else
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          @if($typePayment == 'manual')
          <div class="col-xs-3 col-sm-3 col-md-3">
          {{ config('web.bank.'.str_replace('transfer_','',$paymentMethod).'.bank') }}
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6 text-center">
            <b>{{ config('web.bank.'.str_replace('transfer_','',$paymentMethod).'.account_'.$type) }}</b>
            <input type="text" style="opacity: 0;margin-top: -80px;display: block;" id="nomor-rekening-value" value="{{ str_replace('-','',config('web.bank.'.str_replace('transfer_','',$paymentMethod).'.account_'.$type)) }}" />
          </div>
          @endif
          @if($typePayment == 'midtrans')
          <div class="col-xs-3 col-sm-3 col-md-3">
          {{ config('web.bank.'.str_replace('','',$paymentMethod).'.bank') }}
          </div>
          <div class="col-xs-6 col-sm-6 col-md-6 text-center">
            <b>{{ $data['va_number'] }}</b>
            <input type="text" style="opacity: 0;margin-top: -80px;display: block;" id="nomor-rekening-value" value="{{ $data['va_number'] }}" />
          </div>
          @endif
          <div class="col-xs-2 col-sm-2 col-md-2">
            <a href="#" id="nomor-rekening">
              <b>SALIN</b>
            </a>
          </div>
        </div>
      </div>
    </div>
    @endif 

		<div class="panel panel-default">
			<div class="panel-body">
				<p>Transfer infak/zakat sebelum <b>{{ date('d F Y, H:i:s', strtotime($data['expired_at'])) }} WIB</b> atau infak/zakat kamu otomatis dibatalkan oleh sistem.</p>
			</div>
		</div>
		<ul class="list-group">
			<li class="list-group-item">
				<span class="pull-right">{{ strtoupper($type) }}</span>
				<strong>Jenis Transaksi</strong>
			</li>
			<li class="list-group-item">
				<span class="pull-right">
          @if ($data['is_anonim'])
            Hamba Allah
          @else
            {{ $data['fullname'] }}
          @endif
        </span>
				<strong>Nama</strong>
			</li>
			<li class="list-group-item">
				<span class="pull-right">{{ $data['phone'] }}</span>
				<strong>No. Whatsapp</strong>
			</li>
			<li class="list-group-item">
				<span class="pull-right">{{ $data['email'] }}</span>
				<strong>Email</strong>
			</li>
		</ul>
		<br>
		<button type="button" class="btn btn-blue-large" data-dismiss="modal">{{ trans('homepage.kembali') }}</button>
</div>


<script>
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
$(document).ready(function(){
  if (isMobile.any()) {
    window.open("{{ $data['redirect_url'] }}", '_blank');
  }
  
  $('#nomor-rekening').click(function(){
      var copyText = document.getElementById("nomor-rekening-value");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      document.execCommand("copy");
      $(this).html("BERHASIL SALIN");
  });
  $('#nominal-salin').click(function(){
      var copyText = document.getElementById("nominal-value");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      document.execCommand("copy");
      $(this).html("BERHASIL SALIN");
  });
});
</script>