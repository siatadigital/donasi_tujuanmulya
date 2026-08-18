<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Zakat;
use App\Models\Supporter;
use App\Models\SupporterDetail;
use App\Models\Option;
use App\Models\Project;

use Illuminate\Support\Facades\Crypt;

class MidtransController extends Controller
{
  /**
   * Class constructor.
   *
   * @param \Illuminate\Http\Request $request User Request
   *
   * @return void
   */
  public function __construct()
  {
    // Set midtrans configuration
    \Veritrans_Config::$serverKey = config('services.midtrans.serverKey');
    \Veritrans_Config::$isProduction = config('services.midtrans.isProduction');
    \Veritrans_Config::$isSanitized = config('services.midtrans.isSanitized');
    \Veritrans_Config::$is3ds = config('services.midtrans.is3ds');
  }

  private function generateUniqueCode($type)
  {
    $uniqueCode = rand(1, 999);
    if ($type == 'donation') {
      $donationCount = Donation::where('status', 'pending')->where('unique_code', $uniqueCode)->count();
      $try = 0;
      while ($donationCount > 0) {
        if ($try > 3) {
          $uniqueCode = rand(999, 1999);
        } else {
          $uniqueCode = rand(1, 999);
        }
        $donationCount = Donation::where('status', 'pending')->where('unique_code', $uniqueCode)->count();
        $try++;
      }
    }
    if ($type == 'zakat') {
      $zakatCount = Zakat::where('status', 'pending')->where('unique_code', $uniqueCode)->count();
      $try = 0;
      while ($zakatCount > 0) {
        if ($try > 3) {
          $uniqueCode = rand(999, 1999);
        } else {
          $uniqueCode = rand(1, 999);
        }
        $zakatCount = Zakat::where('status', 'pending')->where('unique_code', $uniqueCode)->count();
        $try++;
      }
    }
    if ($type == 'supporter') {
      $supportCount = Supporter::where('status', 'pending')->where('unique_code', $uniqueCode)->count();
      $try = 0;
      while ($supportCount > 0) {
        if ($try > 3) {
          $uniqueCode = rand(999, 1999);
        } else {
          $uniqueCode = rand(1, 999);
        }
        $supportCount = Supporter::where('status', 'pending')->where('unique_code', $uniqueCode)->count();
        $try++;
      }
    }

    return $uniqueCode;
  }

  /**
   * Submit donation.
   *
   * @return array
   */
  public function submitMidtrans($type, Request $request)
  {
    $req = $request->all();
    if (!in_array($type, ['donation', 'zakat', 'project'])) {
      abort(404);
    }
    if (empty($req['payment_method'])) {
      abort(422, 'Metode pembayaran wajib dipilih.');
    }
    if ($type == 'project') {
      $req['money'] = preg_replace('/\D/', '', isset($req['money']) ? (string) $req['money'] : '');
      if ((int) $req['money'] < 1) {
        abort(422, 'Nominal donasi tidak valid.');
      }
    } else {
      $req['amount'] = preg_replace('/\D/', '', isset($req['amount']) ? (string) $req['amount'] : '');
      if ((int) $req['amount'] < 1) {
        abort(422, 'Nominal donasi tidak valid.');
      }
    }

    \DB::transaction(function () use ($req, $type) {
      if ($type == 'donation') {
        $donationData = $req;
        foreach (['id', 'status', 'is_payment_confirmed', 'payment_confirm_at', 'snap_token', 'redirect_url', 'unique_code', 'va_number', 'expired_at', 'sent_expired_email', 'is_checked', 'check_note', 'doku_data'] as $field) {
          unset($donationData[$field]);
        }
        $donationData['status'] = 'pending';
        $donation = Donation::create($donationData);
        $date = date('Y-m-d H:i:s');
        $expiredAt = strtotime("+1440 minute", strtotime($date));
        $expiredAt = date('Y-m-d H:i:s', $expiredAt);

        $donation->expired_at = $expiredAt;

        if (strpos($donation->payment_method, 'transfer_') !== false) {
          $donation->unique_code = $this->generateUniqueCode('donation');
          $donation->save();

          $data = [
            'id' => $donation->id,
            'fullname' => $donation->fullname,
            'phone' => $donation->phone,
            'unique_code' => $donation->unique_code,
            'amount' => $donation->unique_code ? $donation->amount + $donation->unique_code : $donation->amount,
            'bank_name' => $donation->data_payment_method->name,
            'bank_number' => $donation->data_payment_method->account_number_infak,
            'bank_account' => $donation->data_payment_method->account_name,
            'expired_at' => date('d F Y, H:i:s', strtotime($donation->expired_at)),
          ];

          try {
            if ($req['email'] != '' || !empty($req['email'])) {
              \Mail::queue('emails.wait', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Terimakasih Atas Niat Berinfak');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }

          // manual transfer
          $this->response['data'] = $donation;
          $this->response['type'] = 'infak';
          $this->response['typeTransaction'] = $type;
          $this->response['typePayment'] = 'manual';
        } else {
          $donation->save();
          if ($donation->payment_method == 'DANA') {
            // Xendit
            \PaymentNotif::payment_xendit($donation, $req, $date, $type);

            $this->response['data'] = $donation;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'xendit';
          } elseif ($donation->payment_method == 'SA') {
            // Duitku
            \PaymentNotif::payment_duitku($donation, $req, $date, $type);

            $this->response['data'] = $donation;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'duitku';
          } elseif ($donation->payment_method == 'OVO' or $donation->payment_method == 'JeniusPay' or $donation->payment_method == 'OCTO Clicks' or $donation->payment_method == 'LinkAja' or $donation->payment_method == 'KlikBCA') {
            // DOKU
            \PaymentNotif::payment_doku($donation, $req, $date, $type);

            $this->response['data'] = $donation;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'doku';
          } elseif ($donation->payment_method == 'muamalat_va') {
            // Muamalat
            $randomVANumber = rand(1000000000, 9999999999); //10 digit

            $donationVACheck = Donation::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            while ($donationVACheck > 0) {
              $randomVANumber = rand(1000000000, 9999999999); //10 digit
              $donationVACheck = Donation::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            }

            $donation->va_number = $randomVANumber;
            $donation->save();

            $res = \PaymentNotif::payment_muamalat($donation, $req, $date, $type);

            $this->response['data'] = $donation;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'muamalat';
            $this->response['res'] = $res;
          } elseif ($donation->payment_method == 'va_bca') {
            // Muamalat
            $randomVANumber = rand(1000000000, 9999999999); //10 digit

            $donationVACheck = Donation::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            while ($donationVACheck > 0) {
              $randomVANumber = rand(1000000000, 9999999999); //10 digit
              $donationVACheck = Donation::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            }

            $donation->va_number = $randomVANumber;
            $donation->save();

            // $res = \PaymentNotif::payment_muamalat($donation, $req, $date, $type);

            $this->response['data'] = $donation;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'va_bca';
            $this->response['res'] = "";
          } else {
            // Midtrans
            \PaymentNotif::payment_midtrans_snap($donation, $req, $date, $type);

            $this->response['data'] = $donation;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'midtrans';
          }
        }
      } else if ($type == 'zakat') {
        // Save zakat ke database
        $zakatData = $req;
        foreach (['id', 'status', 'is_payment_confirmed', 'payment_confirm_at', 'snap_token', 'redirect_url', 'unique_code', 'va_number', 'expired_at', 'sent_expired_email', 'is_checked', 'check_note'] as $field) {
          unset($zakatData[$field]);
        }
        $zakatData['status'] = 'pending';
        $zakat = Zakat::create($zakatData);
        $date = date('Y-m-d H:i:s');
        $expiredAt = strtotime("+1440 minute", strtotime($date));
        $expiredAt = date('Y-m-d H:i:s', $expiredAt);

        $zakat->expired_at = $expiredAt;

        if (strpos($zakat->payment_method, 'transfer_') !== false) {
          $zakat->unique_code = $this->generateUniqueCode('zakat');
          $zakat->save();

          $data = [
            'id' => $zakat->id,
            'fullname' => $zakat->fullname,
            'phone' => $zakat->phone,
            'unique_code' => $zakat->unique_code,
            'amount' => $zakat->unique_code ? $zakat->amount + $zakat->unique_code : $zakat->amount,
            'bank_name' => $zakat->data_payment_method->name,
            'bank_number' => $zakat->data_payment_method->account_number_zakat,
            'bank_account' => $zakat->data_payment_method->account_name,
            'expired_at' => date('d F Y, H:i:s', strtotime($zakat->expired_at)),
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_payment')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[bank_name]", "[bank_number]", "[bank_account]", "[unique_code]", "[expired_at]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), $data['bank_name'], $data['bank_number'], $data['bank_account'], $data['unique_code'], $data['expired_at'], "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($req['email'] != '' || !empty($req['email'])) {
              \Mail::queue('emails.wait', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Terimakasih Atas Niat Zakat ' . $zakat->type);
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }

          // manual transfer
          $this->response['data'] = $zakat;
          $this->response['type'] = 'zakat';
          $this->response['typeTransaction'] = $type;
          $this->response['typePayment'] = 'manual';
        } else {
          $zakat->save();
          if ($zakat->payment_method == 'DANA') {
            // Xendit
            \PaymentNotif::payment_xendit($zakat, $req, $date, $type);

            $this->response['data'] = $zakat;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'xendit';
          } elseif ($zakat->payment_method == 'SA') {
            // Duitku
            \PaymentNotif::payment_duitku($zakat, $req, $date, $type);

            $this->response['data'] = $zakat;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'duitku';
          } elseif ($zakat->payment_method == 'OVO' or $zakat->payment_method == 'JeniusPay' or $zakat->payment_method == 'OCTO Clicks' or $zakat->payment_method == 'LinkAja' or $zakat->payment_method == 'KlikBCA') {
            // DOKU
            \PaymentNotif::payment_doku($zakat, $req, $date, $type);

            $this->response['data'] = $zakat;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'doku';
          } elseif ($zakat->payment_method == 'muamalat_va') {
            // Muamalat
            $randomVANumber = rand(1000000000, 9999999999); //10 digit

            $zakatVACheck = Zakat::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            while ($zakatVACheck > 0) {
              $randomVANumber = rand(1000000000, 9999999999); //10 digit
              $zakatVACheck = Zakat::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            }

            $zakat->va_number = $randomVANumber;
            $zakat->save();

            \PaymentNotif::payment_muamalat($zakat, $req, $date, $type);

            $this->response['data'] = $zakat;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'muamalat';
          } elseif ($zakat->payment_method == 'va_bca') {
            // Muamalat
            $randomVANumber = rand(1000000000, 9999999999); //10 digit

            $zakatVACheck = Zakat::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            while ($zakatVACheck > 0) {
              $randomVANumber = rand(1000000000, 9999999999); //10 digit
              $zakatVACheck = Zakat::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            }

            $zakat->va_number = $randomVANumber;
            $zakat->save();

            // \PaymentNotif::payment_muamalat($zakat, $req, $date, $type);

            $this->response['data'] = $zakat;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'va_bca';
          } else {
            // Midtrans
            \PaymentNotif::payment_midtrans_snap($zakat, $req, $date, $type);

            $this->response['data'] = $zakat;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'midtrans';
          }
        }
      } else if ($type == 'project') {
        // Save support project ke database
        $project = isset($req['project_id']) ? Project::find((int) $req['project_id']) : null;
        if (!$project || $project->status != 'active' || strtotime($project->time_end) < time()) {
          abort(422, 'Campaign sudah tidak menerima donasi.');
        }

        $rewardItems = [];
        if (!empty($req['reward_id'])) {
          $rewardList = json_decode($req['reward_id'], true);
          if (!is_array($rewardList) || count($rewardList) < 1) {
            abort(422, 'Pilihan campaign tidak valid.');
          }

          $rewardTotal = 0;
          foreach ($rewardList as $item) {
            $rewardId = isset($item['id']) ? (int) $item['id'] : 0;
            $quantity = isset($item['qty']) ? (int) $item['qty'] : 0;
            $reward = $rewardId > 0 ? $project->rewards()->where('id', $rewardId)->first() : null;
            if (!$reward || $quantity < 1 || $quantity > 100) {
              abort(422, 'Pilihan campaign tidak valid.');
            }

            $rewardTotal += ((int) $reward->price * $quantity);
            $rewardItems[] = [
              'project_id' => $project->id,
              'name' => isset($item['name']) ? substr(strip_tags((string) $item['name']), 0, 500) : '',
              'item' => $reward->content,
              'price' => (int) $reward->price,
              'quantity' => $quantity,
            ];
          }

          if ((int) $req['money'] !== $rewardTotal) {
            abort(422, 'Nominal pilihan campaign tidak cocok.');
          }
        }
        if ((int) $req['money'] > ((int) $project->money_target - (int) $project->money_progress)) {
          abort(422, 'Nominal melebihi sisa target campaign.');
        }

        $req['user_id'] = auth()->check() ? auth()->user()->id : 0;
        $req['noauth'] = true;
        if ($req['user_id'] != '') {
          $req['noauth'] = false;
        }
        $req['payment_method'] = $req['payment_method'];
        $supporterData = $req;
        foreach (['id', 'status', 'has_confirm_payment', 'payment_confirm_at', 'snap_token', 'redirect_url', 'unique_code', 'va_number', 'expired_at', 'sent_expired_email', 'is_checked', 'check_note'] as $field) {
          unset($supporterData[$field]);
        }
        $supporterData['status'] = 'pending';
        $supporter = Supporter::create($supporterData);

        if (count($rewardItems) > 0) {
          foreach ($rewardItems as $rewardItem) {
            SupporterDetail::create(array_merge($rewardItem, [
              'supporter_id' => $supporter->id,
            ]));
          }
        }

        $date = date('Y-m-d H:i:s');
        $expiredAt = strtotime("+1440 minute", strtotime($date));
        $expiredAt = date('Y-m-d H:i:s', $expiredAt);

        $supporter->expired_at = $expiredAt;

        if (strpos($supporter->payment_method, 'transfer_') !== false) {
          $project = $supporter->project;

          $supporter->unique_code = $this->generateUniqueCode('supporter');
          $supporter->save();

          $typeNotif = 'confirm_payment';
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_payment';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_payment';
          }

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'bank_number' => $supporter->data_payment_method->account_number_infak,
            'bank_account' => $supporter->data_payment_method->account_name,
            'expired_at' => date('d F Y, H:i:s', strtotime($supporter->expired_at)),
            'type_notif' => $typeNotif,
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[bank_name]", "[bank_number]", "[bank_account]", "[unique_code]", "[expired_at]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), $data['bank_name'], $data['bank_number'], $data['bank_account'], $data['unique_code'], $data['expired_at'], "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($req['email'] != '' || !empty($req['email'])) {
              \Mail::queue('emails.wait', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Terimakasih Atas Niat Berinfak pada Campaign');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }

          // manual transfer
          $this->response['data'] = $supporter;
          $this->response['type'] = 'infak';
          $this->response['typeTransaction'] = $type;
          $this->response['project'] = $project;
          $this->response['typePayment'] = 'manual';
        } else {
          $project = $supporter->project;

          $supporter->save();
          if ($supporter->payment_method == 'DANA') {
            // Xendit
            \PaymentNotif::payment_xendit($supporter, $req, $date, $type);

            $this->response['data'] = $supporter;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['project'] = $project;
            $this->response['typePayment'] = 'xendit';
          } elseif ($supporter->payment_method == 'SA') {
            // Duitku
            \PaymentNotif::payment_duitku($supporter, $req, $date, $type);

            $this->response['data'] = $supporter;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['project'] = $project;
            $this->response['typePayment'] = 'duitku';
          } elseif ($supporter->payment_method == 'OVO' or $supporter->payment_method == 'JeniusPay' or $supporter->payment_method == 'OCTO Clicks' or $supporter->payment_method == 'LinkAja' or $supporter->payment_method == 'KlikBCA') {
            // DOKU
            \PaymentNotif::payment_doku($supporter, $req, $date, $type);

            $this->response['data'] = $supporter;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['project'] = $project;
            $this->response['typePayment'] = 'doku';
          } elseif ($supporter->payment_method == 'muamalat_va') {
            // Muamalat
            $randomVANumber = rand(1000000000, 9999999999); //10 digit

            $supporterVACheck = Supporter::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            while ($supporterVACheck > 0) {
              $randomVANumber = rand(1000000000, 9999999999); //10 digit
              $supporterVACheck = Supporter::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            }

            $supporter->va_number = $randomVANumber;
            $supporter->save();

            \PaymentNotif::payment_muamalat($supporter, $req, $date, $type);

            $this->response['data'] = $supporter;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'muamalat';
          } elseif ($supporter->payment_method == 'va_bca') {
            // Muamalat
            $randomVANumber = rand(1000000000, 9999999999); //10 digit

            $supporterVACheck = Supporter::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            while ($supporterVACheck > 0) {
              $randomVANumber = rand(1000000000, 9999999999); //10 digit
              $supporterVACheck = Supporter::where('status', 'pending')->where('va_number', $randomVANumber)->count();
            }

            $supporter->va_number = $randomVANumber;
            $supporter->save();

            // \PaymentNotif::payment_muamalat($supporter, $req, $date, $type);

            $this->response['data'] = $supporter;
            $this->response['type'] = 'zakat';
            $this->response['typeTransaction'] = $type;
            $this->response['typePayment'] = 'va_bca';
          } else {
            // Midtrans
            \PaymentNotif::payment_midtrans_snap($supporter, $req, $date, $type);

            $this->response['data'] = $supporter;
            $this->response['type'] = 'infak';
            $this->response['typeTransaction'] = $type;
            $this->response['project'] = $project;
            $this->response['typePayment'] = 'midtrans';
          }
        }
      }
    });

    if (strpos($req['payment_method'], 'transfer_') !== false) {
      // manual transfer
      return view('contents.payment.modal-transfer', $this->response);
    } elseif ($this->response['typePayment'] == 'midtrans') {
      $transaction = $this->response['data'];
      return response()->json([
        'snap_token' => $transaction->snap_token,
        'redirect_url' => $transaction->redirect_url,
        'transaction_id' => $transaction->id,
        'transaction_type' => $type,
      ]);
    } else {
      // midtrans, xendit
      //return $this->response;
      return view('contents.payment.modal-midtrans', $this->response);
    }
  }

  /**
   * Midtrans notification handler.
   *
   * @param Request $request
   * 
   * @return void
   */
  public function notificationHandler(Request $request)
  {
    $notif = $request->all();
    $data = null;
    $required = ['order_id', 'status_code', 'gross_amount', 'signature_key', 'transaction_status', 'payment_type'];
    foreach ($required as $field) {
      if (!isset($notif[$field]) || $notif[$field] === '') {
        return response()->json(['success' => false, 'message' => 'Notifikasi tidak lengkap.'], 400);
      }
    }

    $signature = hash(
      'sha512',
      $notif['order_id'] . $notif['status_code'] . $notif['gross_amount'] . config('services.midtrans.serverKey')
    );
    if (!hash_equals($signature, (string) $notif['signature_key'])) {
      \Log::warning('Midtrans notification signature mismatch.', [
        'order_id' => $notif['order_id'],
      ]);
      return response()->json(['success' => false, 'message' => 'Notifikasi tidak valid.'], 403);
    }

    $orderParts = explode('-', (string) $notif['order_id'], 2);
    if (count($orderParts) !== 2 || !ctype_digit($orderParts[0]) || (int) $orderParts[0] < 1) {
      return response()->json(['success' => false, 'message' => 'Order ID tidak valid.'], 400);
    }

    $orderId = (int) $orderParts[0];
    $typeOrder = $orderParts[1];
    $transaction = $notif['transaction_status'];
    $type = $notif['payment_type'];
    $fraud = isset($notif['fraud_status']) ? $notif['fraud_status'] : '';

    if (!in_array($typeOrder, ['donation', 'zakat', 'project'])) {
      return response()->json(['success' => false, 'message' => 'Tipe order tidak valid.'], 400);
    }

    if ($typeOrder == 'donation') {
      $donation = Donation::find($orderId);
      $expectedAmount = $donation ? $donation->amount : null;
    } else if ($typeOrder == 'zakat') {
      $zakat = Zakat::find($orderId);
      $expectedAmount = $zakat ? $zakat->amount : null;
    } else {
      $supporter = Supporter::find($orderId);
      $expectedAmount = $supporter ? $supporter->money : null;
    }

    if (!$expectedAmount || !preg_match('/^\d+(\.\d{1,2})?$/', (string) $notif['gross_amount'])) {
      return response()->json(['success' => false, 'message' => 'Transaksi tidak ditemukan.'], 404);
    }

    if (number_format((float) $notif['gross_amount'], 2, '.', '') !== number_format((float) $expectedAmount, 2, '.', '')) {
      \Log::warning('Midtrans notification amount mismatch.', [
        'order_id' => $notif['order_id'],
      ]);
      return response()->json(['success' => false, 'message' => 'Nominal transaksi tidak cocok.'], 403);
    }

    \DB::transaction(function () use ($transaction, $type, $fraud, $typeOrder, $orderId) {
      if ($typeOrder == 'donation') {
        $donation = Donation::findOrFail($orderId);
      } else if ($typeOrder == 'zakat') {
        $zakat = Zakat::findOrFail($orderId);
      } else if ($typeOrder == 'project') {
        $supporter = Supporter::findOrFail($orderId);
      }

      $alreadySettled = ($typeOrder == 'donation' && $donation->status == 'success')
        || ($typeOrder == 'zakat' && $zakat->status == 'success')
        || ($typeOrder == 'project' && $supporter->status == 'accept');
      if ($alreadySettled && in_array($transaction, ['capture', 'settlement'])) {
        return;
      }

      if ($transaction == 'capture') {
        // For credit card transaction, we need to check whether transaction is challenge by FDS or not
        if ($type == 'credit_card') {
          if ($fraud == 'challenge') {
            // TODO set payment status in merchant's database to 'Challenge by FDS'
            // TODO merchant should decide whether this transaction is authorized or not in MAP
            // $donation->addUpdate("Transaction order_id: " . $orderId ." is challenged by FDS");
            if ($typeOrder == 'donation') {
              $donation->setPending();
            } else if ($typeOrder == 'zakat') {
              $zakat->setPending();
            } else if ($typeOrder == 'project') {
              $supporter->setPending();
            }
          } else {
            // TODO set payment status in merchant's database to 'Success'
            // $donation->addUpdate("Transaction order_id: " . $orderId ." successfully captured using " . $type);
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


              try {
                if ($donation->email != '' || !empty($donation->email)) {
                  \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                    $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
                  });
                }
              } catch (\Exception $e) {
                // failed send email
              }
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


              try {
                if ($zakat->email != '' || !empty($zakat->email)) {
                  \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                    $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
                  });
                }
              } catch (\Exception $e) {
                // failed send email
              }
            } else if ($typeOrder == 'project') {

              $project = $supporter->project;
              $projectRepository = app(\App\Repositories\Project\ProjectRepository::class);
              $projectRepository->acceptSupporter($project, $supporter, $supporter->unique_code);

              $typeNotif = 'confirm_success';
              if (strpos($project->title, "Qurban") !== false) {
                $typeNotif = 'qurban_confirm_success';
              } else if (strpos($project->title, "Zakat Fitrah") !== false) {
                $typeNotif = 'zakat_fitrah_confirm_success';
              }

              $data = [
                'id' => $supporter->id,
                'fullname' => $supporter->fullname,
                'phone' => $supporter->phone,
                'unique_code' => $supporter->unique_code,
                'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
                'bank_name' => $supporter->data_payment_method->name,
                'date_transfer' => date('d F Y'),
                'type_notif' => $typeNotif,
              ];

              try {
                if ($supporter->email != '' || !empty($supporter->email)) {
                  \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                    $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
                  });
                }
              } catch (\Exception $e) {
                // failed send email
              }
            }
          }
        }
      } elseif ($transaction == 'settlement') {
        // TODO set payment status in merchant's database to 'Settlement'
        // $donation->addUpdate("Transaction order_id: " . $orderId ." successfully transfered using " . $type);
        $typeNotif = 'confirm_success';

        if ($typeOrder == 'donation') {
          $donation->setSuccess();
          $typeNotif = 'confirm_success';
          $data = [
            'id' => $donation->id,
            'fullname' => $donation->fullname,
            'phone' => $donation->phone,
            'unique_code' => $donation->unique_code,
            'amount' => $donation->unique_code ? $donation->amount + $donation->unique_code : $donation->amount,
            'bank_name' => $donation->data_payment_method->name,
            'date_transfer' => date('d F Y'),
          ];

          try {
            $this->SendMessagetext($data);
            if ($donation->email != '' || !empty($donation->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        } else if ($typeOrder == 'zakat') {
          $zakat->setSuccess();
          $typeNotif = 'confirm_success';
          $data = [
            'id' => $zakat->id,
            'fullname' => $zakat->fullname,
            'phone' => $zakat->phone,
            'unique_code' => $zakat->unique_code,
            'amount' => $zakat->unique_code ? $zakat->amount + $zakat->unique_code : $zakat->amount,
            'bank_name' => $zakat->data_payment_method->name,
            'date_transfer' => date('d F Y'),
          ];

          try {
            $this->SendMessagetext($data);
            if ($zakat->email != '' || !empty($zakat->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        } else if ($typeOrder == 'project') {
          $project = $supporter->project;

          $typeNotif = 'confirm_success';
          $projectRepository = app(\App\Repositories\Project\ProjectRepository::class);
          $projectRepository->acceptSupporter($project, $supporter, $supporter->unique_code);
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_success';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_success';
          }

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];

          try {
            $this->SendMessagetext($data);
            if ($supporter->email != '' || !empty($supporter->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        }
        try {
          // sleep(3);
          // Tentukan lokasi untuk menyimpan file PDF
          $pdfPath = public_path("/pdf/" . $data['id'] . "-" . $typeOrder . ".pdf");

          // Mengecek apakah file PDF sudah ada
          if (!file_exists($pdfPath)) {
            $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 10]); // Timeout lebih lama

            // Melakukan permintaan untuk membuat PDF
            $res = $client->request('GET', url() . '/create-invoice/' . $data['id'] . "-" . $typeOrder);
            // Cek apakah respons sukses
            if ($res->getStatusCode() === 200) {
              // Mendapatkan isi file PDF dari response body
              $pdfContent = $res->getBody(); //->getContents();
              $filejson = json_decode($pdfContent);
              //kirim pesan ke whatsapp
              // $this->SendMessagetext($data);
              $this->SendMessagesData($data, $filejson->path);
            } else {
              // Log jika status bukan 200
              // Log::error("Failed to generate PDF. HTTP Status: " . $res->getStatusCode());
            }
          } else {
            //kirim pesan ke whatsapp
            // $this->SendMessagetext($data);
            $this->SendMessagesData($data, url() . "/pdf/" .  $data['id'] . "-" . $typeOrder . ".pdf");
          }
        } catch (\Throwable $th) {
          // Log atau tangani error jika diperlukan
          // Log::error("Error occurred while generating PDF: " . $th->getMessage());
        }
      } elseif ($transaction == 'pending') {
        // TODO set payment status in merchant's database to 'Pending'
        // $donation->addUpdate("Waiting customer to finish transaction order_id: " . $orderId . " using " . $type);
        if ($typeOrder == 'donation') {
          $donation->setPending();
        } else if ($typeOrder == 'zakat') {
          $zakat->setPending();
        } else if ($typeOrder == 'project') {
          $supporter->setPending();
        }
      } elseif ($transaction == 'deny') {
        // TODO set payment status in merchant's database to 'Failed'
        // $donation->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is Failed.");
        if ($typeOrder == 'donation') {
          $donation->setFailed();
        } else if ($typeOrder == 'zakat') {
          $zakat->setFailed();
        } else if ($typeOrder == 'project') {
          $supporter->setFailed();
        }
      } elseif ($transaction == 'expire') {

        $typeNotif = 'confirm_expired';
        // TODO set payment status in merchant's database to 'expire'
        // $donation->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is expired.");
        if ($typeOrder == 'donation') {
          $donation->setExpired();
          $data = [
            'id' => $donation->id,
            'fullname' => $donation->fullname,
            'phone' => $donation->phone,
            'unique_code' => $donation->unique_code,
            'amount' => $donation->unique_code ? $donation->money + $donation->unique_code : $donation->money,
            'bank_name' => $donation->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];
        } else if ($typeOrder == 'zakat') {
          $zakat->setExpired();
          $data = [
            'id' => $zakat->id,
            'fullname' => $zakat->fullname,
            'phone' => $zakat->phone,
            'unique_code' => $zakat->unique_code,
            'amount' => $zakat->unique_code ? $zakat->money + $zakat->unique_code : $zakat->money,
            'bank_name' => $zakat->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];
        } else if ($typeOrder == 'project') {
          $supporter->setExpired();
          $project = $supporter->project;

          $typeNotif = 'confirm_expired';
          // app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

          // $projectRepository = app(\App\Repositories\Project\ProjectRepository::class);
          // $projectRepository->acceptSupporter($project, $supporter, $supporter->unique_code);
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_expired';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_expired';
          }
          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];
        }

        // try {
        //   $api_key   = 'c963538d-bc1d-4aa2-80b0-4a81501249f9'; // API KEY 
        //   $id_device = '0KdzDWoX3QpLomXrCjc8kOJmcAPFTXFh1JjbUKn5decVg7LK8w'; // ID DEVICE 
        //   $url       = 'https://app.wapanels.com/api/create-message'; // URL API 

        //   $nohp = strpos($data['phone'], '08') !== false ? $data['phone'] : '0' . substr($data['phone'], 0, strlen($data['phone']));

        //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

        //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
        //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
        //   $pesan = str_replace($find, $replace, $option->value);
        //   // echo json_encode([
        //   //   "number" =>  $nohp,
        //   //   "message" =>  $pesan
        //   // ]);

        //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
        //   $res = $client->request('POST', $url, [
        //     'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
        //     'body'    => json_encode([
        //       "appkey" => $api_key,
        //       "authkey" => $id_device,
        //       "to" =>  $nohp,
        //       "message" => $pesan,
        //       'sandbox' => 'false'
        //     ])
        //   ]);
        //   unset($pesan);
        //   $response = json_decode($res->getBody(), true);
        //   // echo $response;
        // } catch (\Exception $e) {
        //   // failed send notif wa
        // }
      } elseif ($transaction == 'cancel') {
        // TODO set payment status in merchant's database to 'Failed'
        // $donation->addUpdate("Payment using " . $type . " for transaction order_id: " . $orderId . " is canceled.");
        if ($typeOrder == 'donation') {
          $donation->setFailed();
        } else if ($typeOrder == 'zakat') {
          $zakat->setFailed();
        } else if ($typeOrder == 'project') {
          $supporter->setFailed();
        }
      }
    });

    if ($typeOrder == 'donation') {
      $data = $donation;
    } else if ($typeOrder == 'zakat') {
      $data = $zakat;
    } else if ($typeOrder == 'project') {
      $data = $supporter;
    }

    return response()->json([
      'success' => true,
      'data' => $data,
    ], 200);
  }

  public function notificationMoota(Request $request)
  {
    $req = $request->all();
    $now = date('Y-m-d H:i:s');
    foreach ($req as $item) {
      if ($item['type'] == 'CR') {
        // type CR is uang masuk
        $donation = Donation::where('status', 'pending')->where('expired_at', '<=', $now)->whereRaw(\DB::raw('amount + unique_code = ' . $item['amount']))->count();
        if ($donation > 0) {
          $donation = Donation::where('status', 'pending')->where('expired_at', '<=', $now)->whereRaw(\DB::raw('amount + unique_code = ' . $item['amount']))->first();
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($donation->email != '' || !empty($donation->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }

          return response()->json([
            'success' => true,
            'data' => $donation,
          ], 200);
        }

        $zakat = Zakat::where('status', 'pending')->where('expired_at', '<=', $now)->whereRaw(\DB::raw('amount + unique_code = ' . $item['amount']))->count();
        if ($zakat > 0) {
          $zakat = Zakat::where('status', 'pending')->where('expired_at', '<=', $now)->whereRaw(\DB::raw('amount + unique_code = ' . $item['amount']))->first();
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($zakat->email != '' || !empty($zakat->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }

          return response()->json([
            'success' => true,
            'data' => $zakat,
          ], 200);
        }

        $supporter = Supporter::with('project')->where('status', 'pending')->where('expired_at', '<=', $now)->whereRaw(\DB::raw('money + unique_code = ' . $item['amount']))->count();
        if ($supporter > 0) {
          $supporter = Supporter::with('project')->where('status', 'pending')->where('expired_at', '<=', $now)->whereRaw(\DB::raw('money + unique_code = ' . $item['amount']))->first();
          $project = $supporter->project;
          app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

          $typeNotif = 'confirm_success';
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_success';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_success';
          }

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($supporter->email != '' || !empty($supporter->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }

          return response()->json([
            'success' => true,
            'data' => $supporter,
          ], 200);
        }
      }
    }

    return response()->json([
      'success' => false,
      'data' => $req,
    ], 200);
  }

  public function notificationXendit(Request $request)
  {
    $req = $request->all();
    $headers = $request->header();
    $status = $req['data']['status'];
    $orderId = $req['data']['reference_id'];
    $typeOrder = explode('-', $orderId)[1];
    $orderId = explode('-', $orderId)[0];

    if (isset($headers['x-callback-token'][0]) and $headers['x-callback-token'][0] != config('services.xendit.verifToken')) {
      // stop action
      return response()->json([
        'success' => false,
      ], 500);
    }

    if ($typeOrder == 'donation') {
      $donation = Donation::findOrFail($orderId);
    } else if ($typeOrder == 'zakat') {
      $zakat = Zakat::findOrFail($orderId);
    } else if ($typeOrder == 'project') {
      $supporter = Supporter::findOrFail($orderId);
    }

    \DB::transaction(function () use ($status, $typeOrder, $orderId) {
      if ($typeOrder == 'donation') {
        $donation = Donation::findOrFail($orderId);
      } else if ($typeOrder == 'zakat') {
        $zakat = Zakat::findOrFail($orderId);
      } else if ($typeOrder == 'project') {
        $supporter = Supporter::findOrFail($orderId);
      }
      if ($status == 'PAID') {
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($donation->email != '' || !empty($donation->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($zakat->email != '' || !empty($zakat->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        } else if ($typeOrder == 'project') {
          $project = $supporter->project;
          app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

          $typeNotif = 'confirm_success';
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_success';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_success';
          }

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));

          //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($supporter->email != '' || !empty($supporter->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        }
      } elseif ($status == 'FAILED') {
        if ($typeOrder == 'donation') {
          $donation->setFailed();
        } else if ($typeOrder == 'zakat') {
          $zakat->setFailed();
        } else if ($typeOrder == 'project') {
          $supporter->setFailed();
        }
      } else {
        if ($typeOrder == 'donation') {
          $donation->setPending();
        } else if ($typeOrder == 'zakat') {
          $zakat->setPending();
        } else if ($typeOrder == 'project') {
          $supporter->setPending();
        }
      }
    });

    if ($typeOrder == 'donation') {
      $data = $donation;
    } else if ($typeOrder == 'zakat') {
      $data = $zakat;
    } else if ($typeOrder == 'project') {
      $data = $supporter;
    }

    return response()->json([
      'success' => true,
    ], 200);
  }

  public function notificationDuitku(Request $request)
  {
    $req = $request->all();
    $apiKey = config('services.duitku.key');
    $merchantCode = isset($req['merchantCode']) ? $req['merchantCode'] : null;
    $amount = isset($req['amount']) ? $req['amount'] : null;

    $merchantOrderId = isset($req['merchantOrderId']) ? $req['merchantOrderId'] : null;
    $typeOrder = explode('-', $merchantOrderId)[1];
    $orderId = explode('-', $merchantOrderId)[0];

    $productDetail = isset($req['productDetail']) ? $req['productDetail'] : null;
    $additionalParam = isset($req['additionalParam']) ? $req['additionalParam'] : null;
    $paymentMethod = isset($req['paymentCode']) ? $req['paymentCode'] : null;
    $resultCode = isset($req['resultCode']) ? $req['resultCode'] : null;
    $merchantUserId = isset($req['merchantUserId']) ? $req['merchantUserId'] : null;
    $reference = isset($req['reference']) ? $req['reference'] : null;
    $signature = isset($req['signature']) ? $req['signature'] : null;

    if (!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
      $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
      $calcSignature = md5($params);

      if ($signature == $calcSignature) {
        \DB::transaction(function () use ($resultCode, $typeOrder, $orderId) {
          if ($typeOrder == 'donation') {
            $donation = Donation::findOrFail($orderId);
          } else if ($typeOrder == 'zakat') {
            $zakat = Zakat::findOrFail($orderId);
          } else if ($typeOrder == 'project') {
            $supporter = Supporter::findOrFail($orderId);
          }
          if ($resultCode == '00') {
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
              //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
              //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

              //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
              //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
              //   $pesan = str_replace($find, $replace, $option->value);

              //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
              // } catch (\Exception $e) {
              //   // failed send notif wa
              // }

              try {
                if ($donation->email != '' || !empty($donation->email)) {
                  \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                    $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
                  });
                }
              } catch (\Exception $e) {
                // failed send email
              }
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
              //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
              //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

              //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
              //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
              //   $pesan = str_replace($find, $replace, $option->value);

              //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
              // } catch (\Exception $e) {
              //   // failed send notif wa
              // }

              try {
                if ($zakat->email != '' || !empty($zakat->email)) {
                  \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                    $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
                  });
                }
              } catch (\Exception $e) {
                // failed send email
              }
            } else if ($typeOrder == 'project') {
              $project = $supporter->project;
              app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

              $typeNotif = 'confirm_success';
              if (strpos($project->title, "Qurban") !== false) {
                $typeNotif = 'qurban_confirm_success';
              } else if (strpos($project->title, "Zakat Fitrah") !== false) {
                $typeNotif = 'zakat_fitrah_confirm_success';
              }

              $data = [
                'id' => $supporter->id,
                'fullname' => $supporter->fullname,
                'phone' => $supporter->phone,
                'unique_code' => $supporter->unique_code,
                'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
                'bank_name' => $supporter->data_payment_method->name,
                'date_transfer' => date('d F Y'),
                'type_notif' => $typeNotif,
              ];

              // try {
              //   $hplogin = "081357096599";
              //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
              //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));

              //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

              //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
              //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
              //   $pesan = str_replace($find, $replace, $option->value);

              //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
              // } catch (\Exception $e) {
              //   // failed send notif wa
              // }

              try {
                if ($supporter->email != '' || !empty($supporter->email)) {
                  \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                    $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
                  });
                }
              } catch (\Exception $e) {
                // failed send email
              }
            }
          } elseif ($resultCode == '02') {
            if ($typeOrder == 'donation') {
              $donation->setFailed();
            } else if ($typeOrder == 'zakat') {
              $zakat->setFailed();
            } else if ($typeOrder == 'project') {
              $supporter->setFailed();
            }
          } else {
            if ($typeOrder == 'donation') {
              $donation->setPending();
            } else if ($typeOrder == 'zakat') {
              $zakat->setPending();
            } else if ($typeOrder == 'project') {
              $supporter->setPending();
            }
          }
        });
        return response()->json([
          'success' => true,
        ], 200);
      }
    }

    return response()->json([
      'success' => false,
    ], 500);
  }

  public function notificationDoku(Request $request)
  {
    $req = $request->all();

    $status = $req['RESPONSECODE'];
    $transIDMerchant = $req['TRANSIDMERCHANT'];
    $typeOrder = explode('-', $transIDMerchant)[1];
    $orderId = explode('-', $transIDMerchant)[0];

    if ($typeOrder == 'donation') {
      $donation = Donation::findOrFail($orderId);
    } else if ($typeOrder == 'zakat') {
      $zakat = Zakat::findOrFail($orderId);
    } else if ($typeOrder == 'project') {
      $supporter = Supporter::findOrFail($orderId);
    }

    \DB::transaction(function () use ($status, $typeOrder, $orderId) {
      if ($typeOrder == 'donation') {
        $donation = Donation::findOrFail($orderId);
      } else if ($typeOrder == 'zakat') {
        $zakat = Zakat::findOrFail($orderId);
      } else if ($typeOrder == 'project') {
        $supporter = Supporter::findOrFail($orderId);
      }
      if ($status == '0000') {
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($donation->email != '' || !empty($donation->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($zakat->email != '' || !empty($zakat->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        } else if ($typeOrder == 'project') {
          $project = $supporter->project;
          app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

          $typeNotif = 'confirm_success';
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_success';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_success';
          }

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));

          //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($supporter->email != '' || !empty($supporter->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
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

    if ($typeOrder == 'donation') {
      $data = $donation;
    } else if ($typeOrder == 'zakat') {
      $data = $zakat;
    } else if ($typeOrder == 'project') {
      $data = $supporter;
    }

    return "CONTINUE";
  }

  public function notificationMuamalat(Request $request)
  {
    $req = $request->all();
    $log  = "Server: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
      "Data Muamalat: " . json_encode($req) . PHP_EOL .
      "-------------------------" . PHP_EOL;
    //Save string to log, use FILE_APPEND to append.
    file_put_contents('./log_' . date("j.n.Y") . '.log', $log, FILE_APPEND);

    $status = $req['context']['status'];
    $transIDMerchant = $req['context']['invoice_number'];
    $typeOrder = explode('-', $transIDMerchant)[1];
    $orderId = explode('-', $transIDMerchant)[0];

    if ($typeOrder == 'donation') {
      $donation = Donation::findOrFail($orderId);
    } else if ($typeOrder == 'zakat') {
      $zakat = Zakat::findOrFail($orderId);
    } else if ($typeOrder == 'project') {
      $supporter = Supporter::findOrFail($orderId);
    }

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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($donation->email != '' || !empty($donation->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
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
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
          //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($zakat->email != '' || !empty($zakat->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
        } else if ($typeOrder == 'project') {
          $project = $supporter->project;
          app('ProjectRepository')->acceptSupporter($project, $supporter, $supporter->unique_code);

          $typeNotif = 'confirm_success';
          if (strpos($project->title, "Qurban") !== false) {
            $typeNotif = 'qurban_confirm_success';
          } else if (strpos($project->title, "Zakat Fitrah") !== false) {
            $typeNotif = 'zakat_fitrah_confirm_success';
          }

          $data = [
            'id' => $supporter->id,
            'fullname' => $supporter->fullname,
            'phone' => $supporter->phone,
            'unique_code' => $supporter->unique_code,
            'amount' => $supporter->unique_code ? $supporter->money + $supporter->unique_code : $supporter->money,
            'bank_name' => $supporter->data_payment_method->name,
            'date_transfer' => date('d F Y'),
            'type_notif' => $typeNotif,
          ];

          // try {
          //   $hplogin = "081357096599";
          //   $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
          //   $nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));

          //   $option = Option::where('key', 'notif_wa')->where('type', $typeNotif)->select('value')->first();

          //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
          //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
          //   $pesan = str_replace($find, $replace, $option->value);

          //   $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
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
          // } catch (\Exception $e) {
          //   // failed send notif wa
          // }

          try {
            if ($supporter->email != '' || !empty($supporter->email)) {
              \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
              });
            }
          } catch (\Exception $e) {
            // failed send email
          }
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

    if ($typeOrder == 'donation') {
      $data = $donation;
    } else if ($typeOrder == 'zakat') {
      $data = $zakat;
    } else if ($typeOrder == 'project') {
      $data = $supporter;
    }

    return response()->json([
      'success' => true,
    ], 200);
  }

  function SendMessagetext($data)
  {

    try {
    //   $phone = $data['phone'];
    //   $nohp = (strpos($phone, '62') === 0) ? $phone : (strpos($phone, '0') === 0 ? '62' . ltrim($phone, '0') : '62' . $phone);
    //   // $nohp = strpos($phone, "62") === 0 ? $phone : "62" . ltrim($phone, '0');

    //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();
    //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
    //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
    //   $pesan = str_replace($find, $replace, $option->value);

    //   $curl = curl_init();

    //   curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://app.whacenter.com/api/send',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS => array(
    //       'device_id' => '4a440cb139ebb103668d9f322305c359',
    //       'number' => $nohp,
    //       'message' => $pesan,
    //     ),
    //   ));
    //   $response = curl_exec($curl);
    //   curl_close($curl);
      $success = true;
    } catch (\Exception $e) {
    }
  }
  function SendMessagesData($data, $file)
  {

    try {
    //   $phone = $data['phone'];
    //   $nohp = (strpos($phone, '62') === 0) ? $phone : (strpos($phone, '0') === 0 ? '62' . ltrim($phone, '0') : '62' . $phone);

    //   $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();
    //   $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
    //   $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
    //   $pesan = str_replace($find, $replace, $option->value);

    //   $curl = curl_init();
    //   curl_setopt_array($curl, array(
    //     CURLOPT_URL => 'https://app.whacenter.com/api/send',
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => '',
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 0,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => 'POST',
    //     CURLOPT_POSTFIELDS => array(
    //       'device_id' => '4a440cb139ebb103668d9f322305c359',
    //       'number' => $nohp,
    //       'message' => $pesan,
    //       'file' => $file
    //     ),
    //   ));
    //   $response = curl_exec($curl);
    //   curl_close($curl);


      // $curl = curl_init();

      // curl_setopt_array($curl, array(
      //   CURLOPT_URL => 'https://app.wapanels.com/api/create-message',
      //   CURLOPT_RETURNTRANSFER => true,
      //   CURLOPT_ENCODING => '',
      //   CURLOPT_MAXREDIRS => 10,
      //   CURLOPT_TIMEOUT => 0,
      //   CURLOPT_FOLLOWLOCATION => true,
      //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //   CURLOPT_CUSTOMREQUEST => 'POST',
      //   CURLOPT_POSTFIELDS => array(
      //     'appkey' => 'c963538d-bc1d-4aa2-80b0-4a81501249f9',
      //     'authkey' => '0KdzDWoX3QpLomXrCjc8kOJmcAPFTXFh1JjbUKn5decVg7LK8w',
      //     'to' => $nohp,
      //     'message' => $pesan,
      //     'file' => $file, // Menambahkan file PDF jika dibutuhkan
      //     'sandbox' => 'false'
      //   ),
      // ));

      // $response = curl_exec($curl);

      // curl_close($curl);
      // echo $response;
      // Asumsikan jika response berhasil
      $success = true;
    } catch (\Exception $e) {
    }
  }
}
