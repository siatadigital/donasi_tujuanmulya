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
  $kodeunik = $data['unique_code'];
  $nominal_kodeunik_html = substr(priceFormat($nominal_kodeunik), 0, strlen(priceFormat($nominal_kodeunik)) - 3).'<span style="color: #008797;">'.substr(priceFormat($nominal_kodeunik), strlen(priceFormat($nominal_kodeunik)) - 3, strlen(priceFormat($nominal_kodeunik))).'</span>';
  $input_copy_nominal_kodeunik = '<input type="text" style="opacity: 0;margin-top: -40px;display: block;" id="nominal-value" value="'.$nominal_kodeunik.'" />';
  $bank_account_name = $paymentMethod['account_name'];
  $bank_name = $paymentMethod['name'];
  $bank_account_number = $paymentMethod['account_number_'.$type];
  $input_copy_bank_account_number = '<input type="text" style="opacity: 0;margin-top: -40px;display: block;" id="nomor-rekening-value" value="'.$bank_account_number.'" />';
  $type_transaction = strtoupper($type);
  $user_name = $data['is_anonim'] ? 'Hamba Allah' : $data['fullname'];
  $user_phone = $data['phone'];
  $user_email = $data['email'];
  $date_expired = date('d F Y, H:i:s', strtotime($data['expired_at']));

  $option = App\Models\Option::where('key','transaksi')
      ->where('type','transaksi_transfer_success')
      ->select('value')
      ->first();

  $find = array("[nominal_kodeunik_html]","[input_copy_nominal_kodeunik]","[nominal]","[kodeunik]","[nominal_format]","[bank_account_name]","[bank_name]","[bank_account_number]","[input_copy_bank_account_number]","[type_transaction]","[date_expired]","[user_name]","[user_phone]","[user_email]","[share_url]");
  $replace = array($nominal_kodeunik_html,$input_copy_nominal_kodeunik,$nominal,$kodeunik,$nominal_format,$bank_account_name,$bank_name,$bank_account_number,$input_copy_bank_account_number,$type_transaction,$date_expired,$user_name,$user_phone,$user_email,$share_url);
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