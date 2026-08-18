<?php
  use App\Models\Option;

  $option = Option::where('key', 'notif_email')
            ->where('type', 'user_unverify')
            ->select('value')
            ->first();
  
  $find = [
      '[fullname]',
  ];

  $replace = [
      $user['name'],
  ];

  $messageText = str_replace($find, $replace, $option->value);
?>

{!! $messageText !!}