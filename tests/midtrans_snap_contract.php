<?php

// Lightweight regression contract for the Snap integration.
$root = dirname(__DIR__);
$helper = file_get_contents($root . '/app/Helpers/PaymentNotif.php');
$controller = file_get_contents($root . '/app/Http/Controllers/MidtransController.php');
$homepage = file_get_contents($root . '/resources/views/contents/page/index.blade.php');
$project = file_get_contents($root . '/resources/views/contents/project/show.blade.php');

$checks = [
  'server-side Snap endpoint' => strpos($helper, '/snap/v1/transactions') !== false,
  'Snap token persisted' => strpos($helper, '$model->snap_token = $response[\'token\'];') !== false,
  'Snap used for all three transaction types' => substr_count($controller, 'payment_midtrans_snap(') >= 3,
  'Snap response exposes token' => strpos($controller, '\'snap_token\' => $transaction->snap_token') !== false,
  'notification signature validation' => strpos($controller, "'sha512'") !== false && strpos($controller, 'hash_equals(') !== false,
  'notification amount validation' => strpos($controller, 'Nominal transaksi tidak cocok.') !== false,
  'homepage opens Snap popup' => strpos($homepage, 'window.snap.pay(data.snap_token') !== false,
  'project page opens Snap popup' => strpos($project, 'window.snap.pay(data.snap_token') !== false,
];

$failed = [];
foreach ($checks as $name => $passed) {
  if (!$passed) {
    $failed[] = $name;
  }
}

if (count($failed) > 0) {
  fwrite(STDERR, "FAIL\n- " . implode("\n- ", $failed) . "\n");
  exit(1);
}

echo 'PASS: ' . count($checks) . " Midtrans Snap contracts\n";
