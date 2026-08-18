<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Provinsi\ProvinsiRepository;
use App\Models\Option;
use App\Models\OauthBca;
use App\Models\Donation;
use App\Models\Zakat;
use App\Models\Supporter;
use Illuminate\Support\Facades\Validator;
use Input;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;

class BcaController extends Controller
{
  public function oauthToken(Request $request)
  {
    try {
      $client_id = Option::where('key', 'client_id')->first()->value;
      $client_secret = Option::where('key', 'client_secret')->first()->value;

      if (str_replace("Basic ", "", $request->header('X-Authorization')) != base64_encode($client_id . ':' . $client_secret)) {
        return [
          'status' => false,
          'message' => 'Authentication failed.',
        ];
      }

      if (request('grant_type') != 'client_credentials') {
        return [
          'status' => false,
          'message' => 'grant_type is wrong.',
        ];
      }

      $access_token = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 22)), 0, 22);

      $expired_at = Carbon::now()->addSecond(3600);

      OauthBca::create([
        'access_token' => $access_token,
        'token_type' => 'Bearer',
        'expires_in' => 3600,
        'expired_at' => date('Y-m-d H:i:s', strtotime($expired_at)),
        'scope' => 'resource.WRITE resource.READ',
      ]);

      return [
        'access_token' => $access_token,
        'token_type' => 'Bearer',
        'expires_in' => 3600,
        'scope' => 'resource.WRITE resource.READ',
      ];
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function vaBills(Request $request)
  {
    try {
      $validator = Validator::make(
        $request->all(),
        [
          "CompanyCode"     =>  "required",
          "CustomerNumber"     =>  "required",
          "RequestID"     =>  "required",
          "ChannelType"     =>  "required",
          "TransactionDate"     =>  "required",
        ]
      );

      $field = '';

      if (empty(request('CompanyCode'))) {
        $field = 'CompanyCode';
      } elseif (empty(request('CustomerNumber'))) {
        $field = 'CustomerNumber';
      } elseif (empty(request('RequestID'))) {
        $field = 'RequestID';
      } elseif (empty(request('ChannelType'))) {
        $field = 'ChannelType';
      } elseif (empty(request('TransactionDate'))) {
        $field = 'TransactionDate';
      }

      if ($validator->fails()) {
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'InquiryStatus' => '01',
          'InquiryReason' => array("Indonesian" => $field . ' harus diisi.', "English" => $field . ' field is required.'),
          'CustomerName' => "",
          'TotalAmount' => '0.00',
          'CurrencyCode' => 'IDR',
          'SubCompany' => '00000',
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }

      $d = DateTime::createFromFormat('d/m/Y H:i:s', request('TransactionDate'));

      if (!($d && $d->format('d/m/Y H:i:s') === request('TransactionDate'))) {
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'InquiryStatus' => '01',
          'InquiryReason' => array("Indonesian" => 'Format TransactionDate salah.', "English" => 'Incorrect Transaction Date Format.'),
          'CustomerName' => "",
          'TotalAmount' => '0.00',
          'CurrencyCode' => 'IDR',
          'SubCompany' => '00000',
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }

      $api_secret = Option::where('key', 'api_secret')->first()->value;
      $http_method = 'POST';
      $relative_url = '/va/bills';
      $access_token = str_replace("Bearer ", "", $request->header('X-Authorization'));
      $request_body = strtolower(bin2hex(hash('sha256', json_encode($request->all()))));
      $timestamp = $request->header('X-BCA-Timestamp');
      $string_to_sign = $http_method . ':' . $relative_url . ':' . $access_token . ':' . $request_body . ':' . $timestamp;
      $signature = hash_hmac('sha256', $api_secret, $string_to_sign);

      $get_access_token = OauthBca::where('access_token', $access_token)->first();

      if (!$get_access_token) {
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'InquiryStatus' => '01',
          'InquiryReason' => array("Indonesian" => "access_token tidak cocok", "English" => "access_token mismatch"),
          'CustomerName' => "",
          'TotalAmount' => '0.00',
          'CurrencyCode' => 'IDR',
          'SubCompany' => '00000',
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }

      // var_dump($signature).die();
      $api_key = Option::where('key', 'api_key')->first()->value;
      if ($request->header('X-BCA-Key') == $api_key) {

        $customer_name = '';
        $amount = '';

        $donation = Donation::where('payment_method', 'va_bca')->where('va_number', request('CustomerNumber'))->first();
        $zakat = Zakat::where('payment_method', 'va_bca')->where('va_number', request('CustomerNumber'))->first();
        $project = Supporter::where('payment_method', 'va_bca')->where('va_number', request('CustomerNumber'))->first();

        if ($donation) {
          $customer_name = $donation->fullname;
          $amount = $donation->amount;
        } elseif ($zakat) {
          $customer_name = $zakat->fullname;
          $amount = $zakat->amount;
        } elseif ($project) {
          $customer_name = $project->fullname;
          $amount = $project->money;
        } else {
          return [
            'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
            'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
            'RequestID' => request('RequestID') ? request('RequestID') : '',
            'InquiryStatus' => '01',
            'InquiryReason' => array("Indonesian" => "CustomerNumber tidak ditemukan", "English" => "CustomerNumber not found"),
            'CustomerName' => "",
            'TotalAmount' => '0.00',
            'CurrencyCode' => 'IDR',
            'SubCompany' => '00000',
            'DetailBills' => [],
            'FreeTexts' => [],
            'AdditionalData' => '',
          ];
        }

        $company_code = Option::where('key', 'company_code')->first()->value;

        return [
          'CompanyCode' => $company_code,
          'CustomerNumber' => request('CustomerNumber'),
          'RequestID' => request('RequestID'),
          'InquiryStatus' => '00',
          'InquiryReason' => array("Indonesian" => "Sukses", "English" => "Success"),
          'CustomerName' => $customer_name,
          'CurrencyCode' => 'IDR',
          'TotalAmount' => '0.00',
          'SubCompany' => '00000',
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      } else {
        // http_response_code(400);exit;
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'InquiryStatus' => '01',
          'InquiryReason' => array("Indonesian" => "X-BCA-Key tidak cocok", "English" => "X-BCA-Key mismatch"),
          'CustomerName' => "",
          'TotalAmount' => '0.00',
          'CurrencyCode' => 'IDR',
          'SubCompany' => '00000',
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function vaPayments(Request $request)
  {
    try {

      $validator = Validator::make(
        $request->all(),
        [
          "CompanyCode"     =>  "required",
          "CustomerNumber"     =>  "required",
          "RequestID"     =>  "required",
          "ChannelType"     =>  "required",
          "TransactionDate"     =>  "required",
          "CustomerName"     =>  "required",
          "CurrencyCode"     =>  "required",
          "PaidAmount"     =>  "required",
          "TotalAmount"     =>  "required",
          "FlagAdvice"     =>  "required",
          "SubCompany"     =>  "required",
          "Reference"     =>  "required",
        ]
      );

      $field = '';

      if (empty(request('CompanyCode'))) {
        $field = 'CompanyCode';
      } elseif (empty(request('CustomerNumber'))) {
        $field = 'CustomerNumber';
      } elseif (empty(request('RequestID'))) {
        $field = 'RequestID';
      } elseif (empty(request('ChannelType'))) {
        $field = 'ChannelType';
      } elseif (empty(request('TransactionDate'))) {
        $field = 'TransactionDate';
      } elseif (empty(request('CustomerName'))) {
        $field = 'CustomerName';
      } elseif (empty(request('CurrencyCode'))) {
        $field = 'CurrencyCode';
      } elseif (empty(request('PaidAmount'))) {
        $field = 'PaidAmount';
      } elseif (empty(request('TotalAmount'))) {
        $field = 'TotalAmount';
      } elseif (empty(request('FlagAdvice'))) {
        $field = 'FlagAdvice';
      } elseif (empty(request('SubCompany'))) {
        $field = 'SubCompany';
      } elseif (empty(request('Reference'))) {
        $field = 'Reference';
      }

      if ($validator->fails()) {
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'PaymentFlagStatus' => '01',
          'PaymentFlagReason' => array("Indonesian" => $field . ' harus diisi.', "English" => $field . ' field is required.'),
          'CustomerName' => request('CustomerName') ? request('CustomerName') : '',
          'CurrencyCode' => request('CurrencyCode') ? request('CurrencyCode') : '',
          'PaidAmount' => '0.00',
          'TotalAmount' => '0.00',
          "TransactionDate" => "",
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }

      $d = DateTime::createFromFormat('d/m/Y H:i:s', request('TransactionDate'));

      if (!($d && $d->format('d/m/Y H:i:s') === request('TransactionDate'))) {
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'PaymentFlagStatus' => '01',
          'PaymentFlagReason' => array("Indonesian" => 'Format TransactionDate salah.', "English" => 'Incorrect Transaction Date Format.'),
          'CustomerName' => request('CustomerName') ? request('CustomerName') : '',
          'CurrencyCode' => request('CurrencyCode') ? request('CurrencyCode') : '',
          'PaidAmount' => '0.00',
          'TotalAmount' => '0.00',
          "TransactionDate" => "",
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }

      if (request('FlagAdvice') != 'Y' && request('FlagAdvice') != 'N') {
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'PaymentFlagStatus' => '01',
          'PaymentFlagReason' => array("Indonesian" => 'Format FlagAdvice salah.', "English" => 'Incorrect FlagAdvice Format.'),
          'CustomerName' => request('CustomerName') ? request('CustomerName') : '',
          'CurrencyCode' => request('CurrencyCode') ? request('CurrencyCode') : '',
          'PaidAmount' => '0.00',
          'TotalAmount' => '0.00',
          "TransactionDate" => "",
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }

      $api_secret = Option::where('key', 'api_secret')->first()->value;
      $http_method = 'POST';
      $relative_url = '/va/payments';
      $access_token = str_replace("Bearer ", "", $request->header('X-Authorization'));
      $request_body = strtolower(bin2hex(hash('sha256', json_encode($request->all()))));
      $timestamp = $request->header('X-BCA-Timestamp');
      $string_to_sign = $http_method . ':' . $relative_url . ':' . $access_token . ':' . $request_body . ':' . $timestamp;
      $signature = hash_hmac('sha256', $api_secret, $string_to_sign);

      $get_access_token = OauthBca::where('access_token', $access_token)->first();

      if (!$get_access_token) {
        return [
          'ErrorCode' => "400",
          'ErrorMessage' => array("Indonesian" => "access_token tidak cocok", "English" => "access_token mismatch"),
        ];
      }

      // var_dump($signature).die();

      $api_key = Option::where('key', 'api_key')->first()->value;
      if ($request->header('X-BCA-Key') == $api_key) {

        $customer_name = '';
        $amount = '';

        $donation = Donation::where('payment_method', 'va_bca')->where('va_number', request('CustomerNumber'))->first();
        $zakat = Zakat::where('payment_method', 'va_bca')->where('va_number', request('CustomerNumber'))->first();
        $project = Supporter::where('payment_method', 'va_bca')->where('va_number', request('CustomerNumber'))->first();

        if ($donation) {
          $customer_name = $donation->fullname;
          $amount = $donation->amount;
        } elseif ($zakat) {
          $customer_name = $zakat->fullname;
          $amount = $zakat->amount;
        } elseif ($project) {
          $customer_name = $project->fullname;
          $amount = $project->money;
        } else {
          return [
            'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
            'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
            'RequestID' => request('RequestID') ? request('RequestID') : '',
            'PaymentFlagStatus' => '01',
            'PaymentFlagReason' => array("Indonesian" => "CustomerNumber tidak ditemukan", "English" => "CustomerNumber not found"),
            'CustomerName' => request('CustomerName') ? request('CustomerName') : '',
            'CurrencyCode' => request('CurrencyCode') ? request('CurrencyCode') : '',
            'PaidAmount' => '0.00',
            'TotalAmount' => '0.00',
            "TransactionDate" => "",
            'DetailBills' => [],
            'FreeTexts' => [],
            'AdditionalData' => '',
          ];
        }

        $company_code = Option::where('key', 'company_code')->first()->value;

        $this->notificationVaBCA(request('CustomerNumber'));

        return [
          'CompanyCode' => $company_code,
          'CustomerNumber' => request('CustomerNumber'),
          'RequestID' => request('RequestID'),
          'PaymentFlagStatus' => '00',
          'PaymentFlagReason' => array("Indonesian" => "Sukses", "English" => "Success"),
          'CustomerName' => $customer_name,
          'CurrencyCode' => request('CurrencyCode'),
          'PaidAmount' => '' . request('PaidAmount'),
          'TotalAmount' => '' . request('TotalAmount'),
          "TransactionDate" => request('TransactionDate'),
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      } else {
        // http_response_code(400);exit;
        return [
          'CompanyCode' => request('CompanyCode') ? request('CompanyCode') : '',
          'CustomerNumber' => request('CustomerNumber') ? request('CustomerNumber') : '',
          'RequestID' => request('RequestID') ? request('RequestID') : '',
          'PaymentFlagStatus' => '01',
          'PaymentFlagReason' => array("Indonesian" => "X-BCA-Key tidak cocok", "English" => "X-BCA-Key mismatch"),
          'CustomerName' => request('CustomerName') ? request('CustomerName') : '',
          'CurrencyCode' => request('CurrencyCode') ? request('CurrencyCode') : '',
          'PaidAmount' => '0.00',
          'TotalAmount' => '0.00',
          "TransactionDate" => "",
          'DetailBills' => [],
          'FreeTexts' => [],
          'AdditionalData' => '',
        ];
      }
    } catch (\Exception $e) {
      return $e;
    }
  }

  public function notificationVaBCA($customer_number)
  {
    // $req = $request->all();
    // $log  = "Server: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
    // "Data Muamalat: ".json_encode($req).PHP_EOL.
    // "-------------------------".PHP_EOL;
    // //Save string to log, use FILE_APPEND to append.
    // file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);

    $get_donation = Donation::where('payment_method', 'va_bca')->where('va_number', $customer_number)->first();
    $get_zakat = Zakat::where('payment_method', 'va_bca')->where('va_number', $customer_number)->first();
    $get_project = Supporter::where('payment_method', 'va_bca')->where('va_number', $customer_number)->first();

    $typeOrder = '';
    $orderId = '';

    if ($get_donation) {
      $typeOrder = 'donation';
      $orderId = $get_donation->id;
    } elseif ($get_zakat) {
      $typeOrder = 'zakat';
      $orderId = $get_zakat->id;
    } elseif ($get_project) {
      $typeOrder = 'project';
      $orderId = $get_project->id;
    }

    $status = 'paid';

    \DB::transaction(function () use ($status, $typeOrder, $orderId) {
      if ($typeOrder == 'donation') {
        $donation = Donation::findOrFail($orderId);
      } else if ($typeOrder == 'zakat') {
        $zakat = Zakat::findOrFail($orderId);
      } else if ($typeOrder == 'project') {
        $supporter = Supporter::findOrFail($orderId);
      }
      if ($status == 'paid') {
        if ($typeOrder == 'donation') {
          $donation->setSuccess();

          $data = [
            'id' => $donation->id,
            'fullname' => $donation->fullname,
            'phone' => $donation->phone,
            'unique_code' => $donation->unique_code,
            'amount' => $donation->unique_code ? $donation->amount + $donation->unique_code : $donation->amount,
            'bank_name' => $donation->data_payment_method->name,
            'date_transfer' => date('d F Y'),
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'],'08') ? $data['phone'] : "0".substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key','notif_wa')->where('type','confirm_success')->select('value')->first();

          //   $find = array("[fullname]","[id]","[amount]","[space1]","[space2]");
          //   $replace = array($data['fullname'],$data['id'],priceFormat($data['amount']),"\n","\n\n");
          //   $pesan = str_replace($find,$replace,$option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false,'timeout' => 5]);
          //   $res = $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
          //     'form_params' => [
          //       //'user' => $hplogin,
          //       'token' => $secretcode,
          //       'number' => $nohp,
          //       'message' => $pesan,
          //     ],
          //   ]);
          //   unset($pesan);
          //   $response = json_decode($res->getBody(), true);
          // }catch(\Exception $e) {
          //   // failed send notif wa
          // }

          // try {
          //   if($donation->email != '' || !empty($donation->email)){
          //     \Mail::queue('emails.thanks', $data, function ($message) use ($donation){
          //         $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
          //     });
          //   }
          // }catch(\Exception $e) {
          //   // failed send email
          // }
        } else if ($typeOrder == 'zakat') {
          $zakat->setSuccess();

          $data = [
            'id' => $zakat->id,
            'fullname' => $zakat->fullname,
            'phone' => $zakat->phone,
            'unique_code' => $zakat->unique_code,
            'amount' => $zakat->unique_code ? $zakat->amount + $zakat->unique_code : $zakat->amount,
            'bank_name' => $zakat->data_payment_method->name,
            'date_transfer' => date('d F Y'),
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'],'08') ? $data['phone'] : "0".substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key','notif_wa')->where('type','confirm_success')->select('value')->first();

          //   $find = array("[fullname]","[id]","[amount]","[space1]","[space2]");
          //   $replace = array($data['fullname'],$data['id'],priceFormat($data['amount']),"\n","\n\n");
          //   $pesan = str_replace($find,$replace,$option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false,'timeout' => 5]);
          //   $res = $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
          //     'form_params' => [
          //       //'user' => $hplogin,
          //       'token' => $secretcode,
          //       'number' => $nohp,
          //       'message' => $pesan,
          //     ],
          //   ]);
          //   unset($pesan);
          //   $response = json_decode($res->getBody(), true);
          // }catch(\Exception $e) {
          //   // failed send notif wa
          // }

          // try {
          //   if($zakat->email != '' || !empty($zakat->email)){
          //     \Mail::queue('emails.thanks', $data, function ($message) use ($zakat){
          //         $message->to($zakat->email)->subject('Konfirmasi Zakat '.$zakat->type.' Berhasil');
          //     });
          //   }
          // }catch(\Exception $e) {
          //   // failed send email
          // }
        } else if ($typeOrder == 'project') {
          $project = $supporter->project;
          app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'],'08') ? $data['phone'] : "0".substr($data['phone'], 1, strlen($data['phone']));

          //   $option = Option::where('key','notif_wa')->where('type','confirm_success')->select('value')->first();

          //   $find = array("[fullname]","[id]","[amount]","[space1]","[space2]");
          //   $replace = array($data['fullname'],$data['id'],priceFormat($data['amount']),"\n","\n\n");
          //   $pesan = str_replace($find,$replace,$option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false,'timeout' => 5]);
          //   $res = $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
          //     'form_params' => [
          //       //'user' => $hplogin,
          //       'token' => $secretcode,
          //       'number' => $nohp,
          //       'message' => $pesan,
          //     ],
          //   ]);
          //   unset($pesan);
          //   $response = json_decode($res->getBody(), true);
          // }catch(\Exception $e) {
          //   // failed send notif wa
          // }

          // try {
          //   if($supporter->email != '' || !empty($supporter->email)){
          //     \Mail::queue('emails.thanks', $data, function ($message) use ($supporter){
          //         $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
          //     });
          //   }
          // }catch(\Exception $e) {
          //   // failed send email
          // }
        }
      } else {
        if ($typeOrder == 'donation') {
          $donation->setFailed();
        } else if ($typeOrder == 'zakat') {
          $zakat->setFailed();
        } else if ($typeOrder == 'project') {
          $supporter->setFailed();
        }
      }
    });

    return true;
    // return response()->json([
    //   'success' => true,
    // ], 200);
  }
}
