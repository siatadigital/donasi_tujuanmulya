<?php
  $nominal_kodeunik = 0;
  $nominal = 0;
  $paymentMethod = $data['data_payment_method'];
  if ($typeTransaction == "project") {
    $nominal = $data['money'];
    $share_url = "https://api.whatsapp.com/send?text=".$project['title']."%0a".$project['summary']."%0a%0aMari infak dengan klik:%0a".route('project.newGetShow', $project['slug']);
  }else {
    $nominal = $data['amount'];
    $share_url = "https://api.whatsapp.com/send?text=Platform Zakat, Infak, Sedekah dan Kemanusiaan Online Indonesia%0a%0aMari infak/zakat dengan klik:%0a".url('/');
  }
  $nominal_kodeunik = $data['unique_code'] ? $nominal + $data['unique_code'] : $nominal;
  $nominal_format = priceFormat($data['amount']);

  $text_info_payment = '';
  if($typePayment == 'midtrans') {
    if($paymentMethod['code'] == "echannel") {
      $text_info_payment = 'Kode Pembayaran Multi Payment Mandiri';
    }else if($paymentMethod['code'] == "other_va") {
      $text_info_payment = 'Menu Transfer sesuai dengan rekening tujuan (support semua bank)';
    }else if($paymentMethod['code'] == "gopay") {
      $text_info_payment = 'aplikasi Gopay dengan scan QR Code dibawah atau redirect ke aplikasi';
    }else {
      $text_info_payment = 'Nomor Virtual Account (VA)';
    }
  }
  if($typePayment == 'xendit') {
    if($paymentMethod['code'] == "DANA") {
      $text_info_payment = 'aplikasi Dana dengan klik link dibawah';
    }
  }
  if($typePayment == 'duitku') {
    if($paymentMethod['code'] == "SA") {
      $text_info_payment = 'aplikasi Shopee Pay dengan klik link dibawah';
    }
  }
  if($typePayment == 'doku') {
    if($paymentMethod['code'] == "OVO") {
      $text_info_payment = 'aplikasi OVO dengan klik link dibawah';
    }else if($paymentMethod['code'] == "JeniusPay") {
      $text_info_payment = 'aplikasi JeniusPay dengan klik link dibawah';
    }else if($paymentMethod['code'] == "OCTO Clicks") {
      $text_info_payment = 'aplikasi OCTO Clicks dengan klik link dibawah';
    }else if($paymentMethod['code'] == "LinkAja") {
      $text_info_payment = 'aplikasi LinkAja dengan klik link dibawah';
    }else if($paymentMethod['code'] == "KlikBCA") {
      $text_info_payment = 'aplikasi KlikBCA dengan klik link dibawah';
    }
  }
  if($typePayment == 'muamalat') {
    if($paymentMethod['code'] == "muamalat_va") {
      $text_info_payment = 'Nomor Virtual Account (VA)';
    }
  }
  if($typePayment == 'va_bca') {
    if($paymentMethod['code'] == "va_bca") {
      $text_info_payment = 'Nomor Virtual Account (VA)';
    }
  }

  $text_info_tujuan = '';
  if($typePayment == 'midtrans') {
    if($paymentMethod['code'] == "echannel") {
      $text_info_tujuan = 'ke Multi Payment Mandiri a/n';
    }else if($paymentMethod['code'] == "other_va") {
      $text_info_tujuan = 'ke rekening a/n';
    }else if($paymentMethod['code'] == "gopay") {
      $text_info_tujuan = 'dengan scan QRCode melalui aplikasi Gopay';
    }else {
      $text_info_tujuan = 'ke Virtual Account (VA) a/n';
    }
    $text_info_tujuan .= '<h4 class="text-center" style="font-weight: normal;font-size: 16px;margin-top: 10px;">';
      $text_info_tujuan .= "<b> ".config('web.midtrans_name')."</b><br>";
      if($paymentMethod['code'] == "echannel") {
        $text_info_tujuan .= "(masukkan kode perusahaan <strong>".config('web.biller_code_mandiri')."</strong>)";
      }
    $text_info_tujuan .= '</h4>';
  }
  if($typePayment == 'xendit') {
    if($paymentMethod['code'] == "DANA") {
      $text_info_tujuan = 'dengan link dibawah pada DANA';
    }else {
      $text_info_tujuan = 'ke Virtual Account (VA) a/n';
    }
  }
  if($typePayment == 'duitku') {
    if($paymentMethod['code'] == "SA") {
      $text_info_tujuan = 'dengan link dibawah pada Shopee Pay';
    }
  }
  if($typePayment == 'doku') {
    if($paymentMethod['code'] == "OVO") {
      $text_info_tujuan = 'dengan link dibawah pada OVO';
    }else if($paymentMethod['code'] == "JeniusPay") {
      $text_info_tujuan = 'dengan link dibawah pada JeniusPay';
    }else if($paymentMethod['code'] == "OCTO Clicks") {
      $text_info_tujuan = 'dengan link dibawah pada OCTO Clicks';
    }else if($paymentMethod['code'] == "LinkAja") {
      $text_info_tujuan = 'dengan link dibawah pada LinkAja';
    }else if($paymentMethod['code'] == "KlikBCA") {
      $text_info_tujuan = 'dengan link dibawah pada KlikBCA';
    }
  }
  if($typePayment == 'muamalat') {
    if($paymentMethod['code'] == "muamalat_va") {
      $text_info_tujuan = 'ke Virtual Account (VA) a/n';
    }
  }
  if($typePayment == 'va_bca') {
    if($paymentMethod['code'] == "va_bca") {
      $text_info_payment = 'ke Virtual Account (VA)';
    }
  }

  $doku_data = '';
  if ($typePayment == 'doku') {
    $doku_data = json_decode($data['doku_data'], TRUE);
  }

  $action_payment = '';
  if($paymentMethod['code'] == "gopay") {
    $action_payment .= '<div class="panel-body text-center">';
      $action_payment .= '<a href="'.$data['redirect_url'].'" target="_blank"><strong>Klik disini untuk bayar dengan Gopay</strong></a>';
      $action_payment .= '<br>';
      $action_payment .= '<strong>atau</strong>';
      $action_payment .= '<br>';
      $action_payment .= '<strong>Scan QR Code dibawah melalui aplikasi Gopay</strong>';
      $action_payment .= '<br>';
      $action_payment .= '<img src="'.str_replace('KODE',$data['va_number'],config('web.midtrans_gopay_qrcode_url')).'" width="200" align="center" />';
    $action_payment .= '</div>';
  } else if($paymentMethod['code'] == "DANA") {
    $action_payment .= '<div class="panel-body text-center">';
      $action_payment .= '<a href="'.$data['redirect_url'].'" target="_blank"><strong>Klik disini untuk bayar dengan DANA</strong></a>';
    $action_payment .= '</div>';
  } else if($paymentMethod['code'] == "SA") {
    $action_payment .= '<div class="panel-body text-center">';
      $action_payment .= '<a href="'.$data['redirect_url'].'" target="_blank"><strong>Klik disini untuk bayar dengan Shopee Pay</strong></a>';
    $action_payment .= '</div>';
  } else if($paymentMethod['code'] == "OVO" or $paymentMethod['code'] == "JeniusPay" or $paymentMethod['code'] == "OCTO Clicks" or $paymentMethod['code'] == "LinkAja" or $paymentMethod['code'] == "KlikBCA") {
    $action_payment .= '<div class="panel-body text-center">';
      if ($doku_data != '') {
        $action_payment .= '<form target="_blank" action="'.$data['redirect_url'].'" method="POST">';
          $action_payment .= '<input type="hidden" name="MALLID" value="'.$doku_data['MALLID'].'" />';
          $action_payment .= '<input type="hidden" name="CHAINMERCHANT" value="'.$doku_data['CHAINMERCHANT'].'" />';
          $action_payment .= '<input type="hidden" name="PAYMENTCHANNEL" value="'.$doku_data['PAYMENTCHANNEL'].'" />';
          $action_payment .= '<input type="hidden" name="AMOUNT" value="'.$doku_data['AMOUNT'].'" />';
          $action_payment .= '<input type="hidden" name="PURCHASEAMOUNT" value="'.$doku_data['PURCHASEAMOUNT'].'" />';
          $action_payment .= '<input type="hidden" name="TRANSIDMERCHANT" value="'.$doku_data['TRANSIDMERCHANT'].'" />';
          $action_payment .= '<input type="hidden" name="REQUESTDATETIME" value="'.$doku_data['REQUESTDATETIME'].'" />';
          $action_payment .= '<input type="hidden" name="CURRENCY" value="'.$doku_data['CURRENCY'].'" />';
          $action_payment .= '<input type="hidden" name="PURCHASECURRENCY" value="'.$doku_data['PURCHASECURRENCY'].'" />';
          $action_payment .= '<input type="hidden" name="SESSIONID" value="'.$doku_data['SESSIONID'].'" />';
          $action_payment .= '<input type="hidden" name="NAME" value="'.$doku_data['NAME'].'" />';
          $action_payment .= '<input type="hidden" name="EMAIL" value="'.$doku_data['EMAIL'].'" />';
          $action_payment .= '<input type="hidden" name="MOBILEPHONE" value="'.$doku_data['MOBILEPHONE'].'" />';
          $action_payment .= '<input type="hidden" name="BASKET" value="'.$doku_data['BASKET'].'" />';
          $action_payment .= '<input type="hidden" name="WORDS" value="'.$doku_data['WORDS'].'" />';
          $action_payment .= '<button type="submit" style="background: transparent;border: 0;color: #a68b4b;"><strong>Klik disini untuk bayar dengan '.$paymentMethod['name'].'</strong></button>';
        $action_payment .= '</form>';
      }else {
        $action_payment .= 'Terjadi kesalahan sistem, silahkan hubungi admin.';
      }
    $action_payment .= '</div>';
  }else {
    $action_payment .= '<div class="panel-body">';
      $action_payment .= '<div class="row">';
        if($typePayment == 'midtrans' || $typePayment == 'muamalat' || $typePayment == 'va_bca') {
          $action_payment .= '<div class="col-xs-3 col-sm-3 col-md-3">';
            $action_payment .= $paymentMethod['name'];
          $action_payment .= '</div>';
          $action_payment .= '<div class="col-xs-6 col-sm-6 col-md-6 text-center">';
            if($typePayment == 'va_bca') {
              $action_payment .= '<b>14182'.$data['va_number'].'</b>';
              $action_payment .= '<input type="text" style="opacity: 0;margin-top: -80px;display: block;" id="nomor-rekening-value" value="14182'.$data['va_number'].'" />';
            }else if($typePayment == 'muamalat') {
              $action_payment .= '<b>'.($type === "zakat" ? "857247" : "857248").$data['va_number'].'</b>';
              $action_payment .= '<input type="text" style="opacity: 0;margin-top: -80px;display: block;" id="nomor-rekening-value" value="'.($type === "zakat" ? "857247" : "857248").$data['va_number'].'" />';
            }else {
              $action_payment .= '<b>'.$data['va_number'].'</b>';
              $action_payment .= '<input type="text" style="opacity: 0;margin-top: -80px;display: block;" id="nomor-rekening-value" value="'.$data['va_number'].'" />';
            }
          $action_payment .= '</div>';
        }
        $action_payment .= '<div class="col-xs-2 col-sm-2 col-md-2">';
        $action_payment .= '<a href="#" id="nomor-rekening">SALIN</a>';
        $action_payment .= '</div>';
      $action_payment .= '</div>';
    $action_payment .= '</div>';
  }

  $nominal_kodeunik_html = substr(priceFormat($nominal_kodeunik), 0, strlen(priceFormat($nominal_kodeunik)) - 3).'<span style="color: #a68b4b;">'.substr(priceFormat($nominal_kodeunik), strlen(priceFormat($nominal_kodeunik)) - 3, strlen(priceFormat($nominal_kodeunik))).'</span>';
  $input_copy_nominal_kodeunik = '<input type="text" style="opacity: 0;margin-top: -40px;display: block;" id="nominal-value" value="'.$nominal_kodeunik.'" />';
  $type_transaction = strtoupper($type);
  $user_name = $data['is_anonim'] ? 'Hamba Allah' : $data['fullname'];
  $user_phone = $data['phone'];
  $user_email = $data['email'];
  $date_expired = date('d F Y, H:i:s', strtotime($data['expired_at']));

  $option = App\Models\Option::where('key','transaksi')
      ->where('type','transaksi_wallet_va_success')
      ->select('value')
      ->first();

  $find = array("[nominal_kodeunik_html]","[input_copy_nominal_kodeunik]","[nominal]","[text_info_payment]","[text_info_tujuan]","[nominal_format]","[action_payment]","[type_transaction]","[date_expired]","[user_name]","[user_phone]","[user_email]","[share_url]");
  $replace = array($nominal_kodeunik_html,$input_copy_nominal_kodeunik,$nominal,$text_info_payment,$text_info_tujuan,$nominal_format,$action_payment,$type_transaction,$date_expired,$user_name,$user_phone,$user_email,$share_url);
  $contentText = str_replace($find,$replace,$option->value);
?>

{!! $contentText !!}

<script>
$(document).ready(function(){
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