<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zakat;
use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use Datatables;
use Excel;
use Illuminate\Support\Facades\Crypt;

class ZakatController extends Controller
{
    public function __construct() {}

    public function confirmCheck($id)
    {
        Zakat::where('id', $id)->update([
            'is_checked' => true
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function cancelCheck($id)
    {
        Zakat::where('id', $id)->update([
            'is_checked' => false
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function submitNote(Request $request)
    {
        Zakat::where('id', request('id'))->update([
            'check_note' => request('note')
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function getSuccessZakatExport(Request $request)
    {
        Excel::create('Zakat Success', function ($excel) {
            $excel->sheet('Sheet1', function ($sheet) {
                $zakat = Zakat::success();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Nama Pemberi Zakat') {
                        $zakat->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Tipe Zakat') {
                        $zakat->where('type', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $zakat->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $zakat->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $zakat->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $zakat->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $zakat->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                foreach ($zakat->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        $item['type'],
                        ($item['data_payment_method'] ? $item['data_payment_method']['name'] : ''),
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
                        'Nama Pemberi Zakat',
                        'Nominal',
                        'Tipe',
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

    public function getPendingZakatExport(Request $request)
    {
        Excel::create('Zakat Pending', function ($excel) {
            $excel->sheet('Sheet1', function ($sheet) {
                $zakat = Zakat::pending();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Nama Pemberi Zakat') {
                        $zakat->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Tipe Zakat') {
                        $zakat->where('type', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $zakat->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $zakat->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $zakat->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $zakat->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $zakat->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                foreach ($zakat->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        $item['type'],
                        ($item['data_payment_method'] ? $item['data_payment_method']['name'] : ''),
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
                        'Nama Pemberi Zakat',
                        'Nominal',
                        'Tipe',
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

    public function getExpiredZakatExport(Request $request)
    {
        Excel::create('Zakat Expired', function ($excel) {
            $excel->sheet('Sheet1', function ($sheet) {
                $zakat = Zakat::expired();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Nama Pemberi Zakat') {
                        $zakat->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $zakat->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Tipe Zakat') {
                        $zakat->where('type', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $zakat->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $zakat->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $zakat->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $zakat->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                foreach ($zakat->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        $item['type'],
                        ($item['data_payment_method'] ? $item['data_payment_method']['name'] : ''),
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
                        'Nama Pemberi Zakat',
                        'Nominal',
                        'Tipe',
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

    public function getSuccessZakat()
    {
        $data['title'] = 'Success Zakat';
        $zakat = Zakat::success();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $zakat = $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $zakat = $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }
            if (request('type_cari') == 'Nama Pemberi Zakat') {
                $zakat = $zakat->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Tipe Zakat') {
                $zakat = $zakat->where('type', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $zakat = $zakat->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $zakat = $zakat->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $zakat = $zakat->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $zakat = $zakat->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $zakat = $zakat->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $count = $zakat->get()->count();
        $total = $zakat->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $zakat = $zakat->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['zakat'] = $zakat;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.zakat.index', $data);
    }

    public function getPendingZakat()
    {
        $data['title'] = 'Pending Zakat';
        $zakat = Zakat::pending();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $zakat = $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $zakat = $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }
            if (request('type_cari') == 'Nama Pemberi Zakat') {
                $zakat = $zakat->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Tipe Zakat') {
                $zakat = $zakat->where('type', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $zakat = $zakat->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $zakat = $zakat->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $zakat = $zakat->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $zakat = $zakat->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $zakat = $zakat->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $count = $zakat->get()->count();
        $total = $zakat->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $zakat = $zakat->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['zakat'] = $zakat;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.zakat.index', $data);
    }

    public function getExpiredZakat()
    {
        $data['title'] = 'Expired Zakat';
        $zakat = Zakat::expired();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $zakat = $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $zakat = $zakat->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }
            if (request('type_cari') == 'Nama Pemberi Zakat') {
                $zakat = $zakat->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Tipe Zakat') {
                $zakat = $zakat->where('type', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $zakat = $zakat->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $zakat = $zakat->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $zakat = $zakat->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('amount + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $zakat = $zakat->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $zakat = $zakat->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $count = $zakat->get()->count();
        $total = $zakat->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $zakat = $zakat->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['zakat'] = $zakat;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.zakat.index', $data);
    }

    public function putSuccessZakat($id)
    {
        $zakat = Zakat::findOrFail($id);
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
            // Tentukan lokasi untuk menyimpan file PDF
            $pdfPath = public_path("/pdf/" . $data['id'] . "-zakat.pdf");

            // Mengecek apakah file PDF sudah ada
            if (!file_exists($pdfPath)) {
                $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]); // Timeout lebih lama

                // Melakukan permintaan untuk membuat PDF
                $res = $client->request('GET', url() . '/create-invoice/' . Crypt::encrypt($data['id'] . "-zakat"));
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
                $this->SendMessagesData($data, url() . "/pdf/" . $data['id'] . "-zakat.pdf");
            }
        } catch (\Throwable $th) {
            // Log atau tangani error jika diperlukan
            // Log::error("Error occurred while generating PDF: " . $th->getMessage());
        }
        try {
            \Mail::queue('emails.thanks', $data, function ($message) use ($zakat) {
                $message->to($zakat->email)->subject('Konfirmasi Zakat ' . $zakat->type . ' Berhasil');
            });
        } catch (\Exception $e) {
            // failed send email
        }

        try {
            if ($zakat->code_referral) {
                $user = User::where('is_internal', TRUE)
                    ->where('code_referral', $zakat->code_referral)
                    ->first();

                $emailPayload = [
                    'user' => $user,
                    'donorName' => !$zakat->is_anonim ? $zakat->fullname : 'Hamba Allah',
                    'type' => 'Zakat',
                    'amount' => $zakat->amount,
                ];

                \Mail::queue('emails.referral-donate', $emailPayload, function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Zakat masuk melalui link referral Anda');
                });
            }
        } catch (\Exception $e) {
            // failed send email
        }

        return redirectMessage(
            route('admin.zakat.getPendingZakat'),
            'Successfully Accept !!',
            '',
            'success'
        );
    }

    public function putRejectZakat($id)
    {
        $zakat = Zakat::findOrFail($id);
        $zakat->setPending();

        return redirectMessage(
            route('admin.zakat.getPendingZakat'),
            'Successfully Reject !!',
            '',
            'success'
        );
    }

    function SendMessagesData($data, $file)
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
            //         'file' => $file,
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
            // $phone = $data['phone']; //'081232619333'; // $data['phone']; ganti dengan phone dari data jika diperlukan
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
