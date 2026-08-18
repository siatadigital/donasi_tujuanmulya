<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Supporter;
use App\Models\Donation;
use App\Models\Category;
use App\Models\Zakat;
use App\Models\User;
use App\Libraries\Paginator;
use Illuminate\Http\Request;
use Datatables;
use Excel;
use Illuminate\Support\Facades\Crypt;

use PDF;

class InvoiceController extends Controller
{
    public function getSuccessTransaksi(Request $request)
    {
        $supporters = Supporter::success()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('projects', 'projects.id', '=', 'supporters.project_id')
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::success()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::success()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');
        $categories = Category::all('id', 'category_name');

        if (!empty($request->from_date)) {
            $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if (!empty($request->cari)) {
            switch ($request->type_cari) {
                case 'Judul Infak Terikat':
                    $supporters = $supporters->whereHas('project', function ($query) use ($request) {
                        $query->where('title', 'like', "%" . $request->cari . "%");
                    });

                    $donations = $donations->where(\DB::raw('donations.id'), 0);
                    $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                    break;

                case 'Nama Pemberi Infak':
                    $supporters = $supporters->where('fullname', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('fullname', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('fullname', 'like', "%" . $request->cari . "%");
                    break;

                case 'No. WhatsApp':
                    $supporters = $supporters->where('phone', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('phone', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('phone', 'like', "%" . $request->cari . "%");
                    break;

                case 'Bank Tujuan':
                    $supporters = $supporters->where('payment_method', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('payment_method', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('payment_method', 'like', "%" . $request->cari . "%");
                    break;

                case 'Nominal/Kode Unik':
                    $supporters = $supporters->where(function ($q) use ($request) {
                        $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . $request->cari . "%")->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))->orWhere('unique_code', 'like', "%" . $request->cari . "%")->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))->orWhere('unique_code', 'like', "%" . $request->cari . "%")->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Invoice':
                    $supporters = $supporters->where('supporters.id', "=", substr($request->cari, 3));
                    $donations = $donations->where('donations.id', "=", substr($request->cari, 3));
                    $zakats = $zakats->where('zakat.id', "=", substr($request->cari, 3));
                    break;

                default:
                    break;
            }
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where(\DB::raw('donations.id'), 0);
                    $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where(\DB::raw('supporters.id'), 0);
                    $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where(\DB::raw('supporters.id'), 0);
                    $donations = $donations->where(\DB::raw('donations.id'), 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);
        $transactions = $supporters
            ->union($zakats)
            ->union($donations)
            ->orderByRaw('(created_at) desc');

        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $page = $request->get('page') ?: 1;
        $paginator = (new Paginator())->setQuery($transactions)->setCurrentPage($page);

        $data = [
            'title' => 'Invoice Transaksi Success',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $paginator->getData(),
            'paginator' => $paginator,
            'categories' => $categories,
        ];

        return view('admin::contents.transaksi.invoice', $data);
    }

    public function getSuccessTransaksiPdf(Request $request, $data)
    {
        $data = Crypt::decrypt($data);
        // $params = ['id' => $data];
        $params = explode("-", $data);
        $params = [
            'type_akad' => $params[1],
            'id' => $params[0],
        ];
        $requestset = (object)$params;
        // $requestset = json_decode($requestset);
        $supporters = Supporter::success()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('projects', 'projects.id', '=', 'supporters.project_id')
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::success()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::success()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        switch ($requestset->type_akad) {
            case 'project':
                $transactions  = $supporters->where('supporters.id', "=", $requestset->id);
                break;
            case 'donation':
                $transactions  = $donations->where('donations.id', "=",  $requestset->id);
                break;
            case 'zakat':
                $transactions  =  $zakats->where('zakat.id', "=", $requestset->id);
                break;

            default:
                $transactions = $supporters
                    ->union($zakats)
                    ->union($donations)
                    ->orderByRaw('(created_at) desc');
                break;
        }


        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        // $paginator = (new Paginator())->setQuery($transactions);

        $data = [
            'title' => 'Invoice Transaksi Success',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $transactions->first(),
            // 'paginator' => $paginator,
            'categories' =>  $requestset->id,
        ];
        $pdf = \PDF::loadView('admin::contents.transaksi.invoicepdf', $data);
        // return $pdf->stream('INVOICE #MH' .  $requestset->id . '.pdf');

        // Menyimpan PDF ke file
        // $pdfFilePath = public_path('pdf/MH' . $requestset->id . '.pdf'); // Ganti dengan path yang sesuai
        // $pdf->save($pdfFilePath); 

        return $pdf->stream('Invoice' . $requestset->id   . "-" .   $requestset->type_akad . '.pdf');
        // return response()->json(['message' => 'PDF generated and saved!', 'path' => url('pdf/report' . $requestset->id . '.pdf')]);
    }
    public function CreateTransaksiPdf(Request $request, $data)
    {
        // $data = Crypt::decrypt($data);
        // $params = ['id' => $data];
        $params = explode("-", $data);
        $params = [
            'type_akad' => $params[1],
            'id' => $params[0],
        ];
        $requestset = (object)$params;
        $supporters = Supporter::success()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('projects', 'projects.id', '=', 'supporters.project_id')
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::success()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::success()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        // $donations = $donations->where('donations.id', "=",  $requestset->id);
        // $zakats = $zakats->where('zakat.id', "=", $requestset->id);
        // $supporters = $supporters->where('supporters.id', $requestset->id);

        switch ($requestset->type_akad) {
            case 'project':
                $transactions  = $supporters->where('supporters.id', "=", $requestset->id);
                break;
            case 'donation':
                $transactions  = $donations->where('donations.id', "=",  $requestset->id);
                break;
            case 'zakat':
                $transactions  =  $zakats->where('zakat.id', "=", $requestset->id);
                break;

                // default:
                //     $transactions = $supporters
                //         ->union($zakats)
                //         ->union($donations)
                //         ->orderByRaw('(created_at) desc');
                //     break;
        }
        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        // $paginator = (new Paginator())->setQuery($transactions);

        $data = [
            'title' => 'Invoice Transaksi Success',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $transactions->first(),
            // 'paginator' => $paginator,
            'categories' =>  $requestset->id,
        ];
        $pdf = \PDF::loadView('admin::contents.transaksi.invoicepdf', $data);

        // Menyimpan PDF ke file
        $pdfFilePath = public_path('pdf/' . $requestset->id . "-" . $requestset->type_akad . '.pdf'); // Ganti dengan path yang sesuai
        $pdf->save($pdfFilePath);

        return response()->json(['message' => 'PDF generated and saved!', 'path' => url() . '/pdf/' . $requestset->id . "-" . $requestset->type_akad . '.pdf']);
        // return $pdf->stream('INVOICE #MH' .  $requestset->id . '.pdf');
    }
    public function getSuccessTransaksiView(Request $request, $data)
    {
        $data = Crypt::decrypt($data);
        // $params = ['id' => $data];
        $params = explode("-", $data);
        $params = [
            'type_akad' => $params[1],
            'id' => $params[0],
        ];
        $requestset = (object)$params;
        // $requestset = json_decode($requestset);
        $supporters = Supporter::success()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('projects', 'projects.id', '=', 'supporters.project_id')
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::success()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::success()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        switch ($requestset->type_akad) {
            case 'project':
                $transactions  = $supporters->where('supporters.id', "=", $requestset->id);
                break;
            case 'donation':
                $transactions  = $donations->where('donations.id', "=",  $requestset->id);
                break;
            case 'zakat':
                $transactions  =  $zakats->where('zakat.id', "=", $requestset->id);
                break;

            default:
                $transactions = $supporters
                    ->union($zakats)
                    ->union($donations)
                    ->orderByRaw('(created_at) desc');
                break;
        }
        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        // $paginator = (new Paginator())->setQuery($transactions);

        $data = [
            'title' => 'Invoice Transaksi Success',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $transactions->first(),
            // 'paginator' => $paginator,
            'categories' =>  $requestset->id,
        ];
        // $pdf = \PDF::loadView('admin::contents.transaksi.invoicepdf', $data);
        // return $pdf->stream('Invoice ' . $requestset->type_akad . '.pdf');
        echo json_encode($data);
    }

    public function filterCategories($request, $supporters)
    {
        if (!empty($request->id)) {
            // lakukan filter category
            $category_ids = $request->id;
            $supporters = $supporters->whereHas('project', function ($query) use ($category_ids) {
                $query->where('id', $category_ids);
            });
        }
        return $supporters;
    }


    public function putSuccessDonation($id)
    {

        try {
            // Tentukan lokasi untuk menyimpan file PDF
            $pdfPath = public_path("/pdf/" . $id . "-donation.pdf");
            // Mengecek apakah file PDF sudah ada
            if (!file_exists($pdfPath)) {
                $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 10]); // Timeout lebih lama

                // Melakukan permintaan untuk membuat PDF
                $res = $client->request('GET', url() . '/create-invoice/' . Crypt::encrypt($id . "-donation"));

                // Cek apakah respons sukses
                if ($res->getStatusCode() === 200) {
                    // Mendapatkan isi file PDF dari response body
                    $pdfContent = $res->getBody(); //->getContents();
                    $filejson = json_decode($pdfContent);
                    //    $this-> SendMessagesData($data, $filejson->path);
                } else {
                    // Log jika status bukan 200
                    echo ("Failed to generate PDF. HTTP Status: " . $res->getStatusCode());
                }
            } else {
                echo ("PDF already exists at: " . $pdfPath);
            }
        } catch (\Throwable $th) {
            // Log atau tangani error jika diperlukan
            echo ("Error occurred while generating PDF: " . $th->getMessage());
        }
    }

    function SendMessagesData($data, $file)
    {

        try {
            $phone = '081232619333'; // $data['phone']; ganti dengan phone dari data jika diperlukan
            $nohp = strpos($phone, "62") === 0 ? $phone : "62" . ltrim($phone, '0');

            $option = Option::where('key', 'notif_wa')->where('type', 'confirm_success')->select('value')->first();
            $find = array("[fullname]", "[id]", "[amount]", "[space1]", "[space2]");
            $replace = array($data['fullname'], $data['id'], priceFormat($data['amount']), "\n", "\n\n");
            $pesan = str_replace($find, $replace, $option->value);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://app.wapanels.com/api/create-message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'appkey' => 'c963538d-bc1d-4aa2-80b0-4a81501249f9',
                    'authkey' => '0KdzDWoX3QpLomXrCjc8kOJmcAPFTXFh1JjbUKn5decVg7LK8w',
                    'to' => $nohp,
                    'message' => $pesan,
                    'file' => $file, // Menambahkan file PDF jika dibutuhkan
                    'sandbox' => 'false'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
            // Asumsikan jika response berhasil
            $success = true;
        } catch (\Exception $e) {
        }
    }
}
