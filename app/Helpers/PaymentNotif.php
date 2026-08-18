<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PaymentNotif
{
  /**
   * Create a Midtrans Snap token for an already persisted pending transaction.
   * The server key never leaves this method or the backend request.
   */
  public static function payment_midtrans_snap($model, $req, $now, $type)
  {
    $serverKey = config('services.midtrans.serverKey');
    if (empty($serverKey)) {
      throw new \RuntimeException('Midtrans server key belum dikonfigurasi.');
    }

    $amount = $type == 'project' ? $model->money : $model->amount;
    $amount = (int) $amount;
    if ($amount < 1) {
      throw new \InvalidArgumentException('Nominal transaksi tidak valid.');
    }

    $campaignName = 'Donasi di tujuanmulia.id';
    if ($type == 'project' && $model->project) {
      $campaignName = $model->project->title;
    } elseif ($type == 'zakat') {
      $campaignName = 'Zakat di tujuanmulia.id';
    } else {
      $campaignName = 'Infak di tujuanmulia.id';
    }

    $payload = [
      'transaction_details' => [
        'order_id' => $model->id . '-' . $type,
        'gross_amount' => $amount,
      ],
      'expiry' => [
        'start_time' => date('Y-m-d H:i:s O', strtotime($now)),
        'unit' => 'minute',
        'duration' => 1440,
      ],
      'customer_details' => [
        'first_name' => (string) $model->fullname,
        'email' => (string) $model->email,
        'phone' => (string) $model->phone,
      ],
      'item_details' => [
        [
          'id' => $type . '-' . $model->id,
          'price' => $amount,
          'quantity' => 1,
          'name' => substr($campaignName, 0, 50),
        ],
      ],
    ];

    // Keep the selected legacy channel when Snap supports the same code.
    $paymentMap = [
      'gopay' => 'gopay',
      'permata_va' => 'permata_va',
      'echannel' => 'echannel',
      'bni_va' => 'bni_va',
      'bri_va' => 'bri_va',
    ];
    $paymentMethod = isset($req['payment_method']) ? $req['payment_method'] : '';
    if (isset($paymentMap[$paymentMethod])) {
      $payload['enabled_payments'] = [$paymentMap[$paymentMethod]];
    }

    $endpoint = config('services.midtrans.isProduction')
      ? 'https://app.midtrans.com/snap/v1/transactions'
      : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

    try {
      $client = new \GuzzleHttp\Client([
        'timeout' => 15,
        'connect_timeout' => 5,
        'verify' => true,
      ]);
      $res = $client->request('POST', $endpoint, [
        'headers' => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json',
        ],
        'auth' => [$serverKey, ''],
        'json' => $payload,
      ]);
      $response = json_decode($res->getBody()->getContents(), true);
    } catch (\Exception $e) {
      \Log::error('Midtrans Snap token request failed.', [
        'type' => $type,
        'transaction_id' => $model->id,
        'message' => $e->getMessage(),
      ]);
      throw new \RuntimeException('Snap gagal membuat token pembayaran.', 0, $e);
    }

    if (!is_array($response) || empty($response['token'])) {
      \Log::error('Midtrans Snap token response is invalid.', [
        'type' => $type,
        'transaction_id' => $model->id,
      ]);
      throw new \RuntimeException('Snap tidak mengembalikan token pembayaran.');
    }

    $model->snap_token = $response['token'];
    $model->redirect_url = isset($response['redirect_url']) ? $response['redirect_url'] : null;
    $model->save();

    return $response;
  }

  public static function payment_midtrans($model, $req, $now, $type)
  {
    try {
      $payload = [
        'transaction_details' => [
          'order_id'      => $model->id . '-' . $type,
          'gross_amount'  => $type == "project" ? $model->money : $model->amount,
        ],
        'expiry' => array(
          'start_time' => date('Y-m-d H:i:s O', strtotime($now)),
          'unit' => 'minute',
          'duration' => 1440
        ),
        'enabled_payments' => array($req['payment_method']),
        'customer_details' => [
          'first_name'    => $model->fullname,
          'email'         => $model->email,
          'phone'         => $model->phone,
        ],
        'item_details' => [
          [
            'id'       => 1,
            'price'    => $type == "project" ? $model->money : $model->amount,
            'quantity' => 1,
            'name'     => $type == "zakat" ? 'Zakat di tujuanmulia.id' : 'Infak di tujuanmulia.id'
          ]
        ]
      ];

      if ($req['payment_method'] == 'permata_va') {
        $payload['payment_type'] = 'permata';
      }
      if ($req['payment_method'] == 'echannel') {
        $payload['payment_type'] = 'echannel';
        $payload['echannel'] = [
          'bill_info1' => 'Pembayaran untuk',
          'bill_info2' => 'Infak/Zakat',
        ];
      }
      if ($req['payment_method'] == 'bri_va') {
        $payload['payment_type'] = 'bank_transfer';
        $payload['bank_transfer'] = [
          'bank' => 'bri',
        ];
      }
      if ($req['payment_method'] == 'bni_va') {
        $payload['payment_type'] = 'bank_transfer';
        $payload['bank_transfer'] = [
          'bank' => 'bni',
        ];
      }
      if ($req['payment_method'] == 'other_va') {
        $payload['payment_type'] = 'bank_transfer';
        $payload['bank_transfer'] = [
          'bank' => 'permata',
        ];
      }
      if ($req['payment_method'] == 'gopay') {
        $payload['payment_type'] = 'gopay';
        $payload['gopay'] =  array(
          'enable_callback' => true,                // optional
          'callback_url' => url('/')   // optional
        );
      }

      $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
    //   $res = $client->request('POST', 'https://api.midtrans.com/v2/charge', [
        $res = $client->request('POST', 'https://api.sandbox.midtrans.com/v2/charge', [
        'headers' => [
          'Accept' => 'application/x-www-form-urlencoded',
          'Content-type' => 'application/json'
        ],
        'auth' => [
          config('services.midtrans.serverKey'), //username
          '', //password
        ],
        'json' => $payload,
      ]);
      $response = json_decode($res->getBody(), true);

      $model->va_number = $res->getBody();
      // "MOHON MAAF, PAYMENT BELUM AKTIF";
      // . $res->getBody();
      if ($req['payment_method'] == 'permata_va') {
        $model->va_number = $response['permata_va_number'];
      }
      if ($req['payment_method'] == 'echannel') {
        $model->va_number = $response['bill_key'];
      }
      if ($req['payment_method'] == 'other_va') {
        $model->va_number = $response['permata_va_number'];
      }
      if ($req['payment_method'] == 'gopay') {
        $model->va_number = $response['transaction_id'];
        $model->redirect_url = $response['actions'][1]['url'];
      }
      if ($req['payment_method'] == 'bri_va') {
        $model->va_number = $response['va_numbers'][0]['va_number'];
      }
      if ($req['payment_method'] == 'bni_va') {
        $model->va_number = $response['va_numbers'][0]['va_number'];
      }
      $model->save();
    } catch (\Exception $e) {
      // failed error
      return 'error:' . $e->getMessage();
    }
  }

  public static function payment_xendit($model, $req, $now, $type)
  {
    try {
      $payload = [
        'reference_id'      => $model->id . '-' . $type,
        'currency'          => 'IDR',
        'amount'            => $type == "project" ? (int) $model->money : (int) $model->amount,
        'checkout_method'   => 'ONE_TIME_PAYMENT',
        'channel_code'      => 'ID_' . $req['payment_method'],
        'channel_properties' => [
          'success_redirect_url' => $type == "project" ? route('project.newGetShow', $model->slug) : route('page.getIndex'),
        ],
      ];

      $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
      $res = $client->request('POST', 'https://api.xendit.co/ewallets/charges', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-type' => 'application/json'
        ],
        'auth' => [
          config('services.xendit.secretKey'), //username
          '', //password
        ],
        'json' => $payload,
      ]);
      $response = json_decode($res->getBody(), true);

      if ($req['payment_method'] == 'DANA') {
        $model->redirect_url = $response['actions']['mobile_web_checkout_url'];
      }
      $model->save();
    } catch (\Exception $e) {
      // failed error
      return 'error:' . $e->getMessage();
    }
  }

  public static function payment_duitku($model, $req, $now, $type)
  {
    try {
      $payload = [
        'merchantCode' => config('services.duitku.code'),
        'paymentAmount' => $type == "project" ? $model->money : $model->amount,
        'paymentMethod' => $req['payment_method'],
        'merchantOrderId' => $model->id . '-' . $type,
        'productDetails' => $type,
        'customerVaName' => $req['fullname'],
        'email' => $req['email'],
        'phoneNumber' => $req['phone'],
        'itemDetails' => [
          [
            'name' => $type,
            'price' => $type == "project" ? $model->money : $model->amount,
            'quantity' => 1
          ]
        ],
        'callbackUrl' => route('notification.duitku'),
        'returnUrl' => route('page.getIndex'),
        'signature' => md5(config('services.duitku.code') . ($model->id . '-' . $type) . ($type == "project" ? $model->money : $model->amount) . config('services.duitku.key')),
        'expiryPeriod' => 1440
      ];

      $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
      $res = $client->request('POST', 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-type' => 'application/json'
        ],
        'json' => $payload,
      ]);
      $response = json_decode($res->getBody(), true);

      if ($req['payment_method'] == 'SA') {
        $model->redirect_url = $response['paymentUrl'];
      }
      $model->save();
    } catch (\Exception $e) {
      // failed error
      return 'error:' . $e->getMessage();
    }
  }

  public static function payment_doku($model, $req, $now, $type)
  {
    try {
      $payment_method = '50';
      if ($req['payment_method'] == 'OVO') {
        $payment_method = '53';
      } else if ($req['payment_method'] == 'JeniusPay') {
        $payment_method = '51';
      } else if ($req['payment_method'] == 'OCTO Clicks') {
        $payment_method = '19';
      } else if ($req['payment_method'] == 'LinkAja') {
        $payment_method = '50';
      } else if ($req['payment_method'] == 'KlikBCA') {
        $payment_method = '03';
      }

      $payload = [
        'MALLID' => config('services.doku.mall_id'),
        'CHAINMERCHANT' => 'NA',
        'PAYMENTCHANNEL' => $payment_method,
        'AMOUNT' => $type == "project" ? $model->money : $model->amount,
        'PURCHASEAMOUNT' => $type == "project" ? $model->money : $model->amount,
        'TRANSIDMERCHANT' => $model->id . '-' . $type,
        'REQUESTDATETIME' => date('YmdHis', strtotime($now)),
        'CURRENCY' => '360',
        'PURCHASECURRENCY' => '360',
        'SESSIONID' => md5($model->id . '-' . $type),
        'NAME' => $req['fullname'],
        'EMAIL' => $req['email'],
        'MOBILEPHONE' => $req['phone'],
        'BASKET' => $type . ',' . ($type == "project" ? $model->money : $model->amount) . ',1,' . ($type == "project" ? $model->money : $model->amount) . '',
        'WORDS' => sha1(($type == "project" ? $model->money : $model->amount) . '.00' . config('services.doku.mall_id') . config('services.doku.shared_key') . ($model->id . '-' . $type)),
      ];

      $model->redirect_url = 'https://pay.doku.com/Suite/Receive';
      $model->doku_data = json_encode($payload);
      $model->save();
    } catch (\Exception $e) {
      // failed error
      return 'error:' . $e->getMessage();
    }
  }

  public static function payment_muamalat($model, $req, $now, $type)
  {
    try {
      $payload = [
        'grant_type' => 'client_credentials',
        'client_id' => '96aba0c4-6599-4fd3-a4c5-d3254fb2dc43',
        'client_secret' => 'LmQTY5v1UlHpHObmlJzbb5NGvQEq3i1wvac4rt6T',
        'scope' => '*',
      ];

      $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
      $res = $client->request('POST', 'https://v2.ipg.class.id/oauth/token', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-type' => 'application/json'
        ],
        'json' => $payload,
      ]);
      $response = json_decode($res->getBody(), true);

      $prefix = $type == "zakat" ? "857247" : "857248";

      $payload = [
        'va_number' => (string)$model->va_number,
        'invoice_number' => $model->id . '-' . $type,
        'channel' => 'muamalat',
        'prefix' => $prefix,
        'name' => $type,
        "total_amount" => $type == "project" ? $model->money : $model->amount,
        'institution_code' => 'gmNVpbe2',
        'type' => 'open',
        'valid_until' => date('c', strtotime("+1440 minute", strtotime($now))),
        'components' => [
          [
            'name' => $type,
            'qty' => 1,
            'price' => $type == "project" ? $model->money : $model->amount,
            'total' => $type == "project" ? $model->money : $model->amount,
          ]
        ],
        'customer_name' => $req['fullname'],
        'customer_email' => $req['email'],
        'customer_phone' => $req['phone'],
      ];
      $signature = hash_hmac('sha256', json_encode($payload), 'nz4NUNCtgAAYorkx0HMAQvZ2hbmZz2');

      $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
      $res = $client->request('POST', 'https://v2.ipg.class.id/api/v2/gw/bill', [
        'headers' => [
          'Accept' => 'application/json',
          'Content-type' => 'application/json',
          'Authorization' => 'Bearer ' . $response['access_token'],
          'Signature' => $signature,
        ],
        'json' => $payload,
      ]);
      $response = json_decode($res->getBody(), true);

      if ($req['payment_method'] == 'muamalat_va') {
        $model->va_number = $prefix . $payload['va_number'];
        $model->redirect_url = $response['data']['hash'];
      }
      $model->save();
    } catch (\Exception $e) {
      // failed error
      return 'error:' . $e->getMessage();
    }
  }
}
