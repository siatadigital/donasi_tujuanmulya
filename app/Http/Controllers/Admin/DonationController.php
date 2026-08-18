<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use Datatables;
use Excel;
use Illuminate\Support\Facades\Crypt;

class DonationController extends Controller
{
    public function __construct() {}

    public function confirmCheck($id)

    {

        Donation::where('id', $id)->update([
            'is_checked' => true
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function cancelCheck($id)

    {

        Donation::where('id', $id)->update([
            'is_checked' => false
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function submitNote(Request $request)
    {

        Donation::where('id', request('id'))->update([
            'check_note' => request('note')
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function getSuccessDonationExport(Request $request)
    {
        Excel::create('Infak Umum Success', function ($excel) {
            $excel->sheet('Sheet1', function ($sheet) {
                $donation = Donation::success();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $donation->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $donation->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Nama Pemberi Infak') {
                        $donation->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $donation->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $donation->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $donation->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $donation->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $donation->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                foreach ($donation->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item->data_payment_method ? $item->data_payment_method->name : ''),
                        $item->email,
                        $item->phone,
                        $item->notes,
                        $item->city,
                        $item->status,
                        (string)$item->unique_code,
                        $item->created_at,
                        $item->check_note,
                        $is_checked
                    );
                    array_push($arr, $data);
                }
                //set the titles
                $sheet->fromArray($arr, null, 'A1', false, false)->prependRow(
                    array(
                        'Nama Pemberi Infak',
                        'Nominal',
                        'Bank',
                        'Email',
                        'No. Whatsapp',
                        'Dukungan/Doa',
                        'Kota',
                        'Status',
                        'Kode Unik',
                        'Tanggal',
                        'Catatan',
                        'Status Check'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getPendingDonationExport(Request $request)
    {
        Excel::create('Infak Umum Pending', function ($excel) {
            $excel->sheet('Sheet1', function ($sheet) {
                $donation = Donation::pending();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $donation->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $donation->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Nama Pemberi Infak') {
                        $donation->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $donation->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $donation->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $donation->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $donation->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $donation->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                foreach ($donation->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item->data_payment_method ? $item->data_payment_method->name : ''),
                        $item->email,
                        $item->phone,
                        $item->notes,
                        $item->city,
                        $item->status,
                        (string)$item->unique_code,
                        $item->created_at,
                        $item->check_note,
                        $is_checked
                    );
                    array_push($arr, $data);
                }
                //set the titles
                $sheet->fromArray($arr, null, 'A1', false, false)->prependRow(
                    array(
                        'Nama Pemberi Infak',
                        'Nominal',
                        'Bank',
                        'Email',
                        'No. Whatsapp',
                        'Dukungan/Doa',
                        'Kota',
                        'Status',
                        'Kode Unik',
                        'Tanggal',
                        'Catatan',
                        'Status Check'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getExpiredDonationExport(Request $request)
    {
        Excel::create('Infak Umum Expired', function ($excel) {
            $excel->sheet('Sheet1', function ($sheet) {
                $donation = Donation::expired();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $donation->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $donation->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Nama Pemberi Infak') {
                        $donation->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $donation->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $donation->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $donation->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $donation->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $donation->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                foreach ($donation->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item->data_payment_method ? $item->data_payment_method->name : ''),
                        $item->email,
                        $item->phone,
                        $item->notes,
                        $item->city,
                        $item->status,
                        (string)$item->unique_code,
                        $item->created_at,
                        $item->check_note,
                        $is_checked
                    );
                    array_push($arr, $data);
                }
                //set the titles
                $sheet->fromArray($arr, null, 'A1', false, false)->prependRow(
                    array(
                        'Nama Pemberi Infak',
                        'Nominal',
                        'Bank',
                        'Email',
                        'No. Whatsapp',
                        'Dukungan/Doa',
                        'Kota',
                        'Status',
                        'Kode Unik',
                        'Tanggal',
                        'Catatan',
                        'Status Check'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getSuccessDonation()
    {
        $data['title'] = 'Success Infak Umum';
        $donations = Donation::success();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }
            if (request('type_cari') == 'Nama Pemberi Infak') {
                $donations = $donations->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $donations = $donations->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $donations = $donations->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $donations = $donations->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $donations = $donations->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $donations = $donations->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $count = $donations->get()->count();
        $total = $donations->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $donations = $donations->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['donations'] = $donations;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.donation.index', $data);
    }

    public function getPendingDonation()
    {
        $data['title'] = 'Pending Infak Umum';
        $donations = Donation::pending();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }
            if (request('type_cari') == 'Nama Pemberi Infak') {
                $donations = $donations->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $donations = $donations->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $donations = $donations->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $donations = $donations->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $donations = $donations->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $donations = $donations->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $count = $donations->get()->count();
        $total = $donations->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $donations = $donations->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['donations'] = $donations;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.donation.index', $data);
    }

    public function getExpiredDonation()
    {
        $data['title'] = 'Expired Infak Umum';
        $donations = Donation::expired();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }
            if (request('type_cari') == 'Nama Pemberi Infak') {
                $donations = $donations->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $donations = $donations->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $donations = $donations->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $donations = $donations->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $donations = $donations->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $donations = $donations->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $count = $donations->get()->count();
        $total = $donations->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $donations = $donations->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['donations'] = $donations;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.donation.index', $data);
    }

    public function putSuccessDonation($id)
    {
        $donation = Donation::findOrFail($id);
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
            // Tentukan lokasi untuk menyimpan file PDF
            $pdfPath = public_path("/pdf/" . $data['id'] . "-donation.pdf");

            // Mengecek apakah file PDF sudah ada
            if (!file_exists($pdfPath)) {
                $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]); // Timeout lebih lama

                // Melakukan permintaan untuk membuat PDF
                $res = $client->request('GET', url() . '/create-invoice/' . Crypt::encrypt($data['id'] . '-donation'));
                // Cek apakah respons sukses
                if ($res->getStatusCode() === 200) {
                    // Mendapatkan isi file PDF dari response body
                    $pdfContent = $res->getBody(); //->getContents();
                    $filejson = json_decode($pdfContent);
                    $this->SendMessagetext($data);
                    $this->SendMessagesData($data, $filejson->path);
                    // Log keberhasilan
                    // Log::info("PDF successfully saved at: " . $pdfPath);
                } else {
                    // Log jika status bukan 200
                    // Log::error("Failed to generate PDF. HTTP Status: " . $res->getStatusCode());
                }
            } else {
                $this->SendMessagetext($data);
                $this->SendMessagesData($data, url() . "/pdf/" . $data['id'] . "-donation.pdf");
            }
        } catch (\Throwable $th) {
            // Log atau tangani error jika diperlukan
            // Log::error("Error occurred while generating PDF: " . $th->getMessage());
        }

        try {
            \Mail::queue('emails.thanks', $data, function ($message) use ($donation) {
                $message->to($donation->email)->subject('Konfirmasi Berinfak Berhasil');
            });
        } catch (\Exception $e) {
            // failed send email
        }

        try {
            if ($donation->code_referral) {
                $user = User::where('is_internal', TRUE)
                    ->where('code_referral', $donation->code_referral)
                    ->first();

                $emailPayload = [
                    'user' => $user,
                    'donorName' => !$donation->is_anonim ? $donation->fullname : 'Hamba Allah',
                    'type' => 'Infaq',
                    'amount' => $donation->amount,
                ];

                \Mail::queue('emails.referral-donate', $emailPayload, function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Infaq masuk melalui link referral Anda');
                });
            }
        } catch (\Exception $e) {
            // failed send email
        }

        return redirectMessage(
            route('admin.donation.getPendingDonation'),
            'Successfully Accept .. !!',
            '',
            'success'
        );
    }

    public function putRejectDonation($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->setPending();

        return redirectMessage(
            route('admin.donation.getPendingDonation'),
            'Successfully Reject !!',
            '',
            'success'
        );
    }


    function SendMessagesData($data, $file)
    {

        try {
            // $phone = $data['phone']; //'081232619333'; // $data['phone']; ganti dengan phone dari data jika diperlukan
            // // $nohp = strpos($phone, "62") === 0 ? $phone : "62" . ltrim($phone, '0');
            // $nohp = (strpos($phone, '62') === 0) ? $phone : (strpos($phone, '0') === 0 ? '62' . ltrim($phone, '0') : '62' . $phone);

            // $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();
            // $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
            // $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
            // $pesan = str_replace($find, $replace, $option->value);

            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://app.whacenter.com/api/send',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => array(
            //         'device_id' => '73ef092745ae40638766023def71b8b4',
            //         'number' => $nohp,
            //         'message' => $pesan,
            //         'file' => $file
            //     ),
            // ));
            // $response = curl_exec($curl);
            // curl_close($curl);
            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://app.wapanels.com/api/create-message',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => array(
            //         'appkey' => 'c963538d-bc1d-4aa2-80b0-4a81501249f9',
            //         'authkey' => '0KdzDWoX3QpLomXrCjc8kOJmcAPFTXFh1JjbUKn5decVg7LK8w',
            //         'to' => $nohp,
            //         'message' => $pesan,
            //         'file' => $file, // Menambahkan file PDF jika dibutuhkan
            //         'sandbox' => 'false'
            //     ),
            // ));

            // $response = curl_exec($curl);

            // curl_close($curl);
            // echo $response;
            // Asumsikan jika response berhasil
            $success = true;
        } catch (\Exception $e) {
        }
    }

    function SendMessagetext($data)
    {

        try {
            // $phone = $data['phone']; // '081232619333'; // $data['phone']; ganti dengan phone dari data jika diperlukan
            // $nohp = (strpos($phone, '62') === 0) ? $phone : (strpos($phone, '0') === 0 ? '62' . ltrim($phone, '0') : '62' . $phone);
            // // $nohp = strpos($phone, "62") === 0 ? $phone : "62" . ltrim($phone, '0');

            // $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();
            // $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
            // $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
            // $pesan = str_replace($find, $replace, $option->value);

            // $curl = curl_init();

            // curl_setopt_array($curl, array(
            //     CURLOPT_URL => 'https://app.whacenter.com/api/send',
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_ENCODING => '',
            //     CURLOPT_MAXREDIRS => 10,
            //     CURLOPT_TIMEOUT => 0,
            //     CURLOPT_FOLLOWLOCATION => true,
            //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            //     CURLOPT_CUSTOMREQUEST => 'POST',
            //     CURLOPT_POSTFIELDS => array(
            //         'device_id' => '73ef092745ae40638766023def71b8b4',
            //         'number' => $nohp,
            //         'message' => $pesan,
            //     ),
            // ));
            // $response = curl_exec($curl);
            // curl_close($curl);
            $success = true;
        } catch (\Exception $e) {
        }
    }
}
