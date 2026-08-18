<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Supporter;
use App\Models\Donation;
use App\Models\Zakat;
use App\Models\Category;
use App\Models\User;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Option;
use Illuminate\Http\Request;
use Datatables;
use Excel;
use Mail;

class CrmController extends Controller
{
    public function getSuccessTransaksi()
    {
        $sum1 = Supporter::success()->sum('money');
        $sum2 = Donation::success()->sum('amount');
        $sum3 = Zakat::success()->sum('amount');

        $projects = Project::where('is_fundraiser', 0)->get();
        $fundraisers = Project::where('is_fundraiser', 1)->get();
        $provinces = Provinsi::all();
        $categories = Category::all('id', 'category_name');

        $data = [
            'title' => 'Success Semua Transaksi',
            'total' => 'Total : ' . priceFormat($sum1 + $sum2 + $sum3),
            'projects' => $projects,
            'fundraisers' => $fundraisers,
            'provinces' => $provinces,
            'categories' => $categories,
        ];

        return view('admin::contents.crm.index', $data);
    }

    public function getJsonSuccessTransaksi(Request $request)
    {
        if (!$request->ajax()) return;

        $supporters = Supporter::success()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::success()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::success()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        if ($request->has('from_date')) {
            $supporter = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if ($request->has('cari') && $request->has('type_cari')) {
            switch ($request->type_cari) {
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
                        $q->where('money', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Kota':
                    $supporters = $supporters->where('city', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('city', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('city', 'like', "%" . $request->cari . "%");
                    break;

                default:
                    break;
            }
        }

        if ($request->has('project_id')) {
            $supporters = $supporters->where('project_id', $request->project_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('age')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->whereRaw('(year(CURRENT_TIMESTAMP) - year(birth_date)) = ?', [$request->age]);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('member_status')) {
            if ($request->member_status === 'member') {
                $supporters = $supporters->has('user');

                // Force to get empty records for unwanted keyword
                $donations = $donations->where('id', 0);
                $zakats = $zakats->where('id', 0);
            } else if ($request->member_status === 'non-member') {
                $supporters = $supporters->doesntHave('user');
            }
        }

        if ($request->has('gender')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->where('gender', $request->gender);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('fundraiser_id')) {
            $supporters = $supporters->where('project_id', $request->fundraiser_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where('id', 0);
                    $donations = $donations->where('id', 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);

        $data = $donations
            ->union($supporters)
            ->union($zakats)
            ->orderByRaw('(created_at) desc')
            ->get();

        return Datatables::of($data)
            ->editColumn('details', function ($data) {
                $project = Project::where('id', $data['project_id'])->first();

                if ($project) {
                    return '<div><label>Nama Project/Campaign : </label>' . $project->title . '</div>
                        <div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>
                        <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                        <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                } else {
                    return '<div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>
                        <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                        <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                }
            })
            ->editColumn('status_donation', function ($data) {
                return strtoupper($data['status']);
            })
            ->editColumn('akad', function ($data) {
                return $data['akad'];
            })
            ->editColumn('kode_unik', function ($data) {
                return $data['unique_code'] ? $data['amount'] + $data['unique_code'] : '-';
            })
            ->editColumn('tanggal', function ($data) {
                return formatTime($data['created_at'], 'd F Y, H:i');
            })
            ->make(true);
    }

    public function getSuccessTransaksiExport(Request $request)
    {
        $supporters = Supporter::success()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::success()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::success()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        if ($request->has('from_date')) {
            $supporter = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if ($request->has('cari') && $request->has('type_cari')) {
            switch ($request->type_cari) {
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
                        $q->where('money', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Kota':
                    $supporters = $supporters->where('city', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('city', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('city', 'like', "%" . $request->cari . "%");
                    break;

                default:
                    break;
            }
        }

        if ($request->has('project_id')) {
            $supporters = $supporters->where('project_id', $request->project_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('age')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->whereRaw('(year(CURRENT_TIMESTAMP) - year(birth_date)) = ?', [$request->age]);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('member_status')) {
            if ($request->member_status === 'member') {
                $supporters = $supporters->has('user');

                // Force to get empty records for unwanted keyword
                $donations = $donations->where('id', 0);
                $zakats = $zakats->where('id', 0);
            } else if ($request->member_status === 'non-member') {
                $supporters = $supporters->doesntHave('user');
            }
        }

        if ($request->has('gender')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->where('gender', $request->gender);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('fundraiser_id')) {
            $supporters = $supporters->where('project_id', $request->fundraiser_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where('id', 0);
                    $donations = $donations->where('id', 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);

        $items = $donations
            ->union($supporters)
            ->union($zakats)
            ->orderByRaw('(created_at) desc')
            ->get();

        Excel::create('Semua Transaksi Success', function ($excel) use ($items) {
            $excel->sheet('Sheet1', function ($sheet) use ($items) {
                $records = collect($items)->map(function ($item) {
                    return [
                        $item->fullname,
                        $item->project ? $item->project->title : '-',
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item->data_payment_method ? $item->data_payment_method->name : ''),
                        $item->email,
                        $item->phone,
                        $item->akad,
                        strtoupper($item->status),
                        (string)$item->unique_code,
                        $item->created_at,
                    ];
                });

                $sheet->fromArray($records, null, 'A1', false, false)->prependRow([
                    'Nama Pemberi Infak',
                    'Nama Project/Campaign',
                    'Nominal',
                    'Bank',
                    'Email',
                    'No. Whatsapp',
                    'Akad',
                    'Status',
                    'Kode Unik',
                    'Tanggal',
                ]);
            });
        })->export('xlsx');
    }

    public function getPendingTransaksi()
    {
        $sum1 = Supporter::pending()->sum('money');
        $sum2 = Donation::pending()->sum('amount');
        $sum3 = Zakat::pending()->sum('amount');

        $projects = Project::where('is_fundraiser', 0)->get();
        $fundraisers = Project::where('is_fundraiser', 1)->get();
        $provinces = Provinsi::all();
        $categories = Category::all('id', 'category_name');

        $data = [
            'title' => 'Pending Semua Transaksi',
            'total' => 'Total : ' . priceFormat($sum1 + $sum2 + $sum3),
            'projects' => $projects,
            'fundraisers' => $fundraisers,
            'provinces' => $provinces,
            'categories' => $categories,
        ];

        return view('admin::contents.crm.index', $data);
    }

    public function getJsonPendingTransaksi(Request $request)
    {
        if (!$request->ajax()) return;

        $supporters = Supporter::pending()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::pending()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::pending()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        if ($request->has('from_date')) {
            $supporter = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if ($request->has('cari') && $request->has('type_cari')) {
            switch ($request->type_cari) {
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
                        $q->where('money', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Kota':
                    $supporters = $supporters->where('city', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('city', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('city', 'like', "%" . $request->cari . "%");
                    break;

                default:
                    break;
            }
        }

        if ($request->has('project_id')) {
            $supporters = $supporters->where('project_id', $request->project_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('age')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->whereRaw('(year(CURRENT_TIMESTAMP) - year(birth_date)) = ?', [$request->age]);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('member_status')) {
            if ($request->member_status === 'member') {
                $supporters = $supporters->has('user');

                // Force to get empty records for unwanted keyword
                $donations = $donations->where('id', 0);
                $zakats = $zakats->where('id', 0);
            } else if ($request->member_status === 'non-member') {
                $supporters = $supporters->doesntHave('user');
            }
        }

        if ($request->has('gender')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->where('gender', $request->gender);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('fundraiser_id')) {
            $supporters = $supporters->where('project_id', $request->fundraiser_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where('id', 0);
                    $donations = $donations->where('id', 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);

        $data = $donations
            ->union($supporters)
            ->union($zakats)
            ->orderByRaw('(created_at) desc')
            ->get();

        return Datatables::of($data)
            ->editColumn('details', function ($data) {
                $project = Project::where('id', $data['project_id'])->first();

                if ($project) {
                    return '<div><label>Nama Project/Campaign : </label>' . $project->title . '</div>
                        <div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>
                        <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                        <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                } else {
                    return '<div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>
                        <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                        <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                }
            })
            ->editColumn('status_donation', function ($data) {
                return strtoupper($data['status']);
            })
            ->editColumn('akad', function ($data) {
                return $data['akad'];
            })
            ->editColumn('kode_unik', function ($data) {
                return $data['unique_code'] ? $data['amount'] + $data['unique_code'] : '-';
            })
            ->editColumn('tanggal', function ($data) {
                return formatTime($data['created_at'], 'd F Y, H:i');
            })
            ->make(true);
    }

    public function getPendingTransaksiExport(Request $request)
    {
        $supporters = Supporter::pending()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::pending()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::pending()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        if ($request->has('from_date')) {
            $supporter = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if ($request->has('cari') && $request->has('type_cari')) {
            switch ($request->type_cari) {
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
                        $q->where('money', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Kota':
                    $supporters = $supporters->where('city', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('city', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('city', 'like', "%" . $request->cari . "%");
                    break;

                default:
                    break;
            }
        }

        if ($request->has('project_id')) {
            $supporters = $supporters->where('project_id', $request->project_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('age')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->whereRaw('(year(CURRENT_TIMESTAMP) - year(birth_date)) = ?', [$request->age]);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('member_status')) {
            if ($request->member_status === 'member') {
                $supporters = $supporters->has('user');

                // Force to get empty records for unwanted keyword
                $donations = $donations->where('id', 0);
                $zakats = $zakats->where('id', 0);
            } else if ($request->member_status === 'non-member') {
                $supporters = $supporters->doesntHave('user');
            }
        }

        if ($request->has('gender')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->where('gender', $request->gender);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('fundraiser_id')) {
            $supporters = $supporters->where('project_id', $request->fundraiser_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where('id', 0);
                    $donations = $donations->where('id', 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);

        $items = $donations
            ->union($supporters)
            ->union($zakats)
            ->orderByRaw('(created_at) desc')
            ->get();

        Excel::create('Semua Transaksi Pending', function ($excel) use ($items) {
            $excel->sheet('Sheet1', function ($sheet) use ($items) {
                $records = collect($items)->map(function ($item) {
                    return [
                        $item->fullname,
                        $item->project ? $item->project->title : '-',
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item->data_payment_method ? $item->data_payment_method->name : ''),
                        $item->email,
                        $item->phone,
                        $item->akad,
                        strtoupper($item->status),
                        (string)$item->unique_code,
                        $item->created_at,
                    ];
                });

                $sheet->fromArray($records, null, 'A1', false, false)->prependRow([
                    'Nama Pemberi Infak',
                    'Nama Project/Campaign',
                    'Nominal',
                    'Bank',
                    'Email',
                    'No. Whatsapp',
                    'Akad',
                    'Status',
                    'Kode Unik',
                    'Tanggal',
                ]);
            });
        })->export('xlsx');
    }

    public function getExpiredTransaksi()
    {
        $sum1 = Supporter::expired()->sum('money');
        $sum2 = Donation::expired()->sum('amount');
        $sum3 = Zakat::expired()->sum('amount');

        $projects = Project::where('is_fundraiser', 0)->get();
        $fundraisers = Project::where('is_fundraiser', 1)->get();
        $provinces = Provinsi::all();
        $categories = Category::all('id', 'category_name');

        $data = [
            'title' => 'Expired Semua Transaksi',
            'total' => 'Total : ' . priceFormat($sum1 + $sum2 + $sum3),
            'projects' => $projects,
            'fundraisers' => $fundraisers,
            'provinces' => $provinces,
            'categories' => $categories,
        ];

        return view('admin::contents.crm.index', $data);
    }

    public function getJsonExpiredTransaksi(Request $request)
    {
        if (!$request->ajax()) return;

        $supporters = Supporter::expired()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::expired()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::expired()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');
        if ($request->has('from_date')) {
            $supporter = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if ($request->has('cari') && $request->has('type_cari')) {
            switch ($request->type_cari) {
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
                        $q->where('money', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Kota':
                    $supporters = $supporters->where('city', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('city', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('city', 'like', "%" . $request->cari . "%");
                    break;

                default:
                    break;
            }
        }

        if ($request->has('project_id')) {
            $supporters = $supporters->where('project_id', $request->project_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('age')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->whereRaw('(year(CURRENT_TIMESTAMP) - year(birth_date)) = ?', [$request->age]);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('member_status')) {
            if ($request->member_status === 'member') {
                $supporters = $supporters->has('user');

                // Force to get empty records for unwanted keyword
                $donations = $donations->where('id', 0);
                $zakats = $zakats->where('id', 0);
            } else if ($request->member_status === 'non-member') {
                $supporters = $supporters->doesntHave('user');
            }
        }

        if ($request->has('gender')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->where('gender', $request->gender);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('fundraiser_id')) {
            $supporters = $supporters->where('project_id', $request->fundraiser_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where('id', 0);
                    $donations = $donations->where('id', 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);

        $data = $donations
            ->union($supporters)
            ->union($zakats)
            ->orderByRaw('(created_at) desc')
            ->get();

        return Datatables::of($data)
            ->editColumn('details', function ($data) {
                $project = Project::where('id', $data['project_id'])->first();

                if ($project) {
                    return '<div><label>Nama Project/Campaign : </label>' . $project->title . '</div>
                        <div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>
                        <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                        <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                } else {
                    return '<div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>
                        <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                        <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                }
            })
            ->editColumn('status_donation', function ($data) {
                return strtoupper($data['status']);
            })
            ->editColumn('akad', function ($data) {
                return $data['akad'];
            })
            ->editColumn('kode_unik', function ($data) {
                return $data['unique_code'] ? $data['amount'] + $data['unique_code'] : '-';
            })
            ->editColumn('tanggal', function ($data) {
                return formatTime($data['created_at'], 'd F Y, H:i');
            })
            ->make(true);
    }

    public function getExpiredTransaksiExport(Request $request)
    {
        $supporters = Supporter::expired()
            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, supporters.created_at, expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::expired()
            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, donations.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::expired()
            ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,status, zakat.created_at,expired_at, is_checked, check_note"))
            ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        if ($request->has('from_date')) {
            $supporter = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:00'))));
        }

        if ($request->has('cari') && $request->has('type_cari')) {
            switch ($request->type_cari) {
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
                        $q->where('money', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $donations = $donations->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    $zakats = $zakats->where(function ($q) use ($request) {
                        $q->where('amount', request('cari'))
                            ->orWhere('unique_code', 'like', "%" . $request->cari . "%")
                            ->orWhereRaw('amount + unique_code LIKE "%' . $request->cari . '%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('email', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('email', 'like', "%" . $request->cari . "%");
                    break;

                case 'Kota':
                    $supporters = $supporters->where('city', 'like', "%" . $request->cari . "%");
                    $donations = $donations->where('city', 'like', "%" . $request->cari . "%");
                    $zakats = $zakats->where('city', 'like', "%" . $request->cari . "%");
                    break;

                default:
                    break;
            }
        }

        if ($request->has('project_id')) {
            $supporters = $supporters->where('project_id', $request->project_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('age')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->whereRaw('(year(CURRENT_TIMESTAMP) - year(birth_date)) = ?', [$request->age]);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('member_status')) {
            if ($request->member_status === 'member') {
                $supporters = $supporters->has('user');

                // Force to get empty records for unwanted keyword
                $donations = $donations->where('id', 0);
                $zakats = $zakats->where('id', 0);
            } else if ($request->member_status === 'non-member') {
                $supporters = $supporters->doesntHave('user');
            }
        }

        if ($request->has('gender')) {
            $supporters = $supporters->whereHas('user', function ($query) use ($request) {
                return $query->where('gender', $request->gender);
            });

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if ($request->has('fundraiser_id')) {
            $supporters = $supporters->where('project_id', $request->fundraiser_id);

            // Force to get empty records for unwanted keyword
            $donations = $donations->where('id', 0);
            $zakats = $zakats->where('id', 0);
        }

        if (!empty($request->type_akad)) {
            // Only get records based on akad type
            // So I force other transaction except selected type to get inexistent id
            switch ($request->type_akad) {
                case 'Infak Terikat':
                    $donations = $donations->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Infak Umum':
                    $supporters = $supporters->where('id', 0);
                    $zakats = $zakats->where('id', 0);
                    break;

                case 'Zakat':
                    $supporters = $supporters->where('id', 0);
                    $donations = $donations->where('id', 0);
                    break;

                default:
                    break;
            }
        }

        $supporters = $this->filterCategories($request, $supporters);

        $items = $donations
            ->union($supporters)
            ->union($zakats)
            ->orderByRaw('(created_at) desc')
            ->get();

        Excel::create('Semua Transaksi Expired', function ($excel) use ($items) {
            $excel->sheet('Sheet1', function ($sheet) use ($items) {
                $records = collect($items)->map(function ($item) {
                    return [
                        $item->fullname,
                        $item->project ? $item->project->title : '-',
                        priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item->data_payment_method ? $item->data_payment_method->name : ''),
                        $item->email,
                        $item->phone,
                        $item->akad,
                        strtoupper($item->status),
                        (string)$item->unique_code,
                        $item->created_at,
                    ];
                });

                $sheet->fromArray($records, null, 'A1', false, false)->prependRow([
                    'Nama Pemberi Infak',
                    'Nama Project/Campaign',
                    'Nominal',
                    'Bank',
                    'Email',
                    'No. Whatsapp',
                    'Akad',
                    'Status',
                    'Kode Unik',
                    'Tanggal',
                ]);
            });
        })->export('xlsx');
    }

    public function getCitiesJson(Request $request)
    {
        $cities = Kota::query();

        if ($request->has('provinsi_id')) {
            $cities = $cities->where('provinsi_id', $request->provinsi_id);
        }

        $cities = $cities->get();

        return response()->json(['data' => $cities]);
    }

    public function sendMessage(Request $request)
    {
        $fullnames = $request->fullnames;
        $message = $request->message;
        $media = $request->send_via;

        switch ($media) {
            case 'email':
                $emails = $request->contacts;

                foreach ($emails as $index => $email) {
                    $data = [
                        'fullname' => $fullnames[$index],
                        'content' => $message,
                    ];

                    Mail::queue('emails.crm-message', $data, function ($message) use ($email) {
                        $message->to($email)->subject('Penawaran dari tujuanmulia.id');
                    });
                }

                return response()->json([
                    'message' => 'Email berhasil dikirim',
                    'data' => $emails
                ], 200);
                break;

            case 'whatsapp':
                // $phones = $request->contacts;
                // $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);

                // $hplogin = "081357096599";
                // $secretcode = "47jk5tH21cv3Zek87iMEfH8gBnqVR67XNnU81755cUff4t6HYg";

                // $option = Option::where('key', 'notif_wa')
                //     ->where('type', 'crm_offer')
                //     ->select('value')
                //     ->first();

                // foreach ($phones as $index => $phone) {
                //     $find = [
                //         '[fullname]',
                //         '[content]',
                //     ];

                //     $replace = [
                //         $fullnames[$index],
                //         $message,
                //     ];

                //     $message = str_replace($find, $replace, $option->value);

                //     $client->request('POST', 'http://itnh.systems/wazakatkita.php', [
                //         'form_params' => [
                //             //'user' => $hplogin,
                //             'token' => $secretcode,
                //             'number' => $phone,
                //             'message' => $message,
                //         ],
                //     ]);
                // }

                // return response()->json([
                //     'message' => 'Pesan Whatsapp berhasil dikirim',
                //     'data' => $phones
                // ], 200);
                break;

            default:
                break;
        }
    }

    public function filterCategories($request, $supporters)
    {
        if (!empty($request->category_ids)) {
            // lakukan filter category
            $category_ids = $request->category_ids;
            $supporters = $supporters->whereHas('project', function ($query) use ($category_ids) {
                $query->whereIn('category_id', $category_ids);
            });
        }
        return $supporters;
    }
}
