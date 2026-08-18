<?php

namespace App\Http\Controllers\Api\v1;

use Cache;
use App\Http\Controllers\Controller;
use App\Models\Zakat;
use App\Models\Supporter;
use App\Models\Donation;
use App\Models\Option;

class StaticController extends Controller
{
	public function getLocation()
	{
		ini_set('memory_limit', '-1');
		$query = \DB::table('locations')->select('name')->get();

		$key = 'site.location';
		$cached = Cache::get($key);

		if ($cached) {
			return $cached;
		}

		$query = app('App\Models\Location')->get()->toArray();
		Cache::forever($key, $query);

		return $query;
	}

	public function checkExpiredTransaction()
	{
		$now = date('Y-m-d H:i:s');
		$supporter = Supporter::where('expired_at', '<=', $now)->where('status', 'pending')->get();
		foreach ($supporter as $item) {
			$item->setExpired();
			$project = $item->project;

			$typeNotif = 'confirm_expired';
			if (strpos($project->title, "Qurban") !== false) {
				$typeNotif = 'qurban_confirm_expired';
			} else if (strpos($project->title, "Zakat Fitrah") !== false) {
				$typeNotif = 'zakat_fitrah_confirm_expired';
			}

			$data = [
				'id' => $item->id,
				'fullname' => $item->fullname,
				'phone' => $item->phone,
				'unique_code' => $item->unique_code,
				'amount' => $item->unique_code ? $item->money + $item->unique_code : $item->money,
				'bank_name' => ($item->data_payment_method ? $item->data_payment_method->name : ''),
				'date_transfer' => date('d F Y', strtotime($item->created_at)),
				'type_notif' => $typeNotif,
			];

			// try {
			// 	$hplogin = "081357096599";
			// 	$secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
			// 	$nohp = strpos($data['phone'],'08') ? $data['phone'] : "0".substr($data['phone'], 1, strlen($data['phone']));
			// 	$option = Option::where('key','notif_wa')->where('type',$typeNotif)->select('value')->first();

			// 	$find = array("[fullname]","[id]","[amount]","[bank_name]","[date_transfer]","[space1]","[space2]");
			// 	$replace = array($data['fullname'],$data['id'],priceFormat($data['amount']),$data['bank_name'],$data['date_transfer'],"\n","\n\n");
			// 	$pesan = str_replace($find,$replace,$option->value);

			// 	$client = new \GuzzleHttp\Client(['verify' => false,'timeout' => 5]);
			// 	$res = $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
			// 		'form_params' => [
			// 			//'user' => $hplogin,
			// 			'token' => $secretcode,
			// 			'number' => $nohp,
			// 			'message' => $pesan,
			// 		],
			// 	]);
			// 	unset($pesan);
			// 	$response = json_decode($res->getBody(), true);
			// }catch(\Exception $e) {
			// 	// failed send notif wa
			// }

			try {
				\Mail::queue('emails.expired', $data, function ($message) use ($item) {
					$message->to($item->email)->subject('Konfirmasi Infak/Zakat Telah Lewat Batas Waktu');
				});
			} catch (\Exception $e) {
				// failed send email
			}
		}
		$zakat = Zakat::where('expired_at', '<=', $now)->where('status', 'pending')->get();
		foreach ($zakat as $item) {
			$item->setExpired();

			$data = [
				'id' => $item->id,
				'fullname' => $item->fullname,
				'phone' => $item->phone,
				'unique_code' => $item->unique_code,
				'amount' => $item->unique_code ? $item->amount + $item->unique_code : $item->amount,
				'bank_name' => ($item->data_payment_method ? $item->data_payment_method->name : ''),
				'date_transfer' => date('d F Y', strtotime($item->created_at)),
			];

			// try {
			// 	$hplogin = "081357096599";
			// 	$secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
			// 	$nohp = strpos($data['phone'],'08') ? $data['phone'] : "0".substr($data['phone'], 1, strlen($data['phone']));
			// 	$option = Option::where('key','notif_wa')->where('type','confirm_expired')->select('value')->first();

			// 	$find = array("[fullname]","[id]","[amount]","[bank_name]","[date_transfer]","[space1]","[space2]");
			// 	$replace = array($data['fullname'],$data['id'],priceFormat($data['amount']),$data['bank_name'],$data['date_transfer'],"\n","\n\n");
			// 	$pesan = str_replace($find,$replace,$option->value);

			// 	$client = new \GuzzleHttp\Client(['verify' => false,'timeout' => 5]);
			// 	$res = $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
			// 		'form_params' => [
			// 			//'user' => $hplogin,
			// 			'token' => $secretcode,
			// 			'number' => $nohp,
			// 			'message' => $pesan,
			// 		],
			// 	]);
			// 	unset($pesan);
			// 	$response = json_decode($res->getBody(), true);
			// }catch(\Exception $e) {
			// 	// failed send notif wa
			// }

			try {
				\Mail::queue('emails.expired', $data, function ($message) use ($item) {
					$message->to($item->email)->subject('Konfirmasi Infak/Zakat Telah Lewat Batas Waktu');
				});
			} catch (\Exception $e) {
				// failed send email
			}
		}
		$donation = Donation::where('expired_at', '<=', $now)->where('status', 'pending')->get();
		foreach ($donation as $item) {
			$item->setExpired();

			$data = [
				'id' => $item->id,
				'fullname' => $item->fullname,
				'phone' => $item->phone,
				'unique_code' => $item->unique_code,
				'amount' => $item->unique_code ? $item->amount + $item->unique_code : $item->amount,
				'bank_name' => ($item->data_payment_method ? $item->data_payment_method->name : ''),
				'date_transfer' => date('d F Y', strtotime($item->created_at)),
			];

			// try {
			// 	$hplogin = "081357096599";
			// 	$secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";
			// 	$nohp = strpos($data['phone'], '08') ? $data['phone'] : "0" . substr($data['phone'], 1, strlen($data['phone']));
			// 	$option = Option::where('key', 'notif_wa')->where('type', 'confirm_expired')->select('value')->first();

			// 	$find = array("[fullname]", "[id]", "[amount]", "[bank_name]", "[date_transfer]", "[space1]", "[space2]");
			// 	$replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), $data['bank_name'], $data['date_transfer'], "\n", "\n\n");
			// 	$pesan = str_replace($find, $replace, $option->value);

			// 	$client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
			// 	$res = $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
			// 		'form_params' => [
			// 			//'user' => $hplogin,
			// 			'token' => $secretcode,
			// 			'number' => $nohp,
			// 			'message' => $pesan,
			// 		],
			// 	]);
			// 	unset($pesan);
			// 	$response = json_decode($res->getBody(), true);
			// } catch (\Exception $e) {
			// 	// failed send notif wa
			// }

			try {
				\Mail::queue('emails.expired', $data, function ($message) use ($item) {
					$message->to($item->email)->subject('Konfirmasi Infak/Zakat Telah Lewat Batas Waktu');
				});
			} catch (\Exception $e) {
				// failed send email
			}
		}

		return 'success';
	}
}
