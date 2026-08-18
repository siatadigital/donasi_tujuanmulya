<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Paginator;
use App\Models\Supporter;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Zakat;
use App\Models\User;
use Illuminate\Http\Request;
use Datatables;
use Excel;
use DB;

class ReferralController extends Controller
{
    public function getAll(Request $request)
    {
        $supporters = Supporter::select(DB::raw("
                        users.name AS referrer_name,
                        supporters.code_referral,
                        supporters.project_id,
                        supporters.fullname AS fullname,
                        supporters.payment_method AS bank,
                        supporters.phone AS phone,
                        NULL AS type,
                        payment_methods.name AS data_payment_method,
                        projects.title AS project_title,
                        supporters.money + IFNULL(supporters.unique_code, 0) AS amount,
                        supporters.created_at AS created_at,
                        'Infak Terikat' as akad
                    "))
                    ->join('users', 'users.code_referral', '=', 'supporters.code_referral')
                    ->join('projects', 'projects.id', '=', 'supporters.project_id')
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                    ->whereNotNull('supporters.code_referral')
                    ->where('supporters.status', 'accept');

        $donations = Donation::select(DB::raw("
                        users.name AS referrer_name,
                        donations.code_referral,
                        NULL as project_id,
                        donations.fullname AS fullname,
                        donations.payment_method AS bank,
                        donations.phone AS phone,
                        NULL AS type,
                        payment_methods.name AS data_payment_method,
                        NULL AS project_title,
                        donations.amount + IFNULL(donations.unique_code, 0) AS amount,
                        donations.created_at AS created_at,
                        'Infak Umum' as akad
                    "))
                    ->join('users', 'users.code_referral', '=', 'donations.code_referral')
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                    ->whereNotNull('donations.code_referral')
                    ->where('donations.status', 'success');

        $zakats = Zakat::select(DB::raw("
                    users.name AS referrer_name,
                    zakat.code_referral,
                    NULL as project_id,
                    zakat.fullname AS fullname,
                    zakat.payment_method AS bank,
                    zakat.phone AS phone,
                    zakat.type AS type,
                    payment_methods.name AS data_payment_method,
                    NULL AS project_title,
                    zakat.amount + IFNULL(zakat.unique_code, 0) AS amount,
                    zakat.created_at AS created_at,
                    'Zakat' as akad
                "))
                ->join('users', 'users.code_referral', '=', 'zakat.code_referral')
                ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                ->whereNotNull('zakat.code_referral')
                ->where('zakat.status', 'success');

        if (!empty(request('from_date'))) {
            $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:00'))));
        }

        if (!empty(request('cari'))) {
            switch (request('type_cari')) {
                case 'Nama Referrer':
                    $supporters = $supporters->where('users.name','like',"%".request('cari')."%");
                    $donations = $donations->where('users.name','like',"%".request('cari')."%");
                    $zakats = $zakats->where('users.name','like',"%".request('cari')."%");
                    break;

                case 'Kode Referrer':
                    $supporters = $supporters->where('supporters.code_referral','like',"%".request('cari')."%");
                    $donations = $donations->where('donations.code_referral','like',"%".request('cari')."%");
                    $zakats = $zakats->where('zakat.code_referral','like',"%".request('cari')."%");
                    break;

                case 'Nama Pemberi Infak':
                    $supporters = $supporters->where('supporters.fullname','like',"%".request('cari')."%");
                    $donations = $donations->where('donations.fullname','like',"%".request('cari')."%");
                    $zakats = $zakats->where('zakat.fullname','like',"%".request('cari')."%");
                    break;

                default:
                    break;
            }
        }

        $categories = Category::all('id', 'category_name');
        $supporters = $this->filterCategories(request('category_ids'), $supporters);

        $transactions = $donations
                        ->union($supporters)
                        ->union($zakats);

        $count = $transactions->get()->count();
        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $page = request('page') ?: 1;
        $paginator = (new Paginator())->setQuery($transactions)->setCurrentPage($page);

        $data = [
            'title' => 'Referral Semua Transaksi',
            'items' => $paginator->getData(),
            'paginator' => $paginator,
            'total' => $total,
            'count' => $count,
            'categories' => $categories,
        ];

        return view('admin::contents.referral.all', $data);
    }

    public function getAllExport(Request $request)
	{
        Excel::create('Referral Semua Transaksi', function($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                $supporters = Supporter::select(DB::raw("
                                users.name AS referrer_name,
                                supporters.code_referral,
                                supporters.project_id,
                                supporters.fullname AS fullname,
                                supporters.payment_method AS bank,
                                supporters.phone AS phone,
                                NULL AS type,
                                payment_methods.name AS data_payment_method,
                                projects.title AS project_title,
                                supporters.money + IFNULL(supporters.unique_code, 0) AS amount,
                                supporters.created_at AS created_at,
                                'Infak Terikat' as akad
                            "))
                            ->join('users', 'users.code_referral', '=', 'supporters.code_referral')
                            ->join('projects', 'projects.id', '=', 'supporters.project_id')
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->whereNotNull('supporters.code_referral')
                            ->where('supporters.status', 'accept');

                $donations = Donation::select(DB::raw("
                                users.name AS referrer_name,
                                donations.code_referral,
                                NULL as project_id,
                                donations.fullname AS fullname,
                                donations.payment_method AS bank,
                                donations.phone AS phone,
                                NULL AS type,
                                payment_methods.name AS data_payment_method,
                                NULL AS project_title,
                                donations.amount + IFNULL(donations.unique_code, 0) AS amount,
                                donations.created_at AS created_at,
                                'Infak Umum' as akad
                            "))
                            ->join('users', 'users.code_referral', '=', 'donations.code_referral')
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->whereNotNull('donations.code_referral')
                            ->where('donations.status', 'success');

                $zakats = Zakat::select(DB::raw("
                            users.name AS referrer_name,
                            zakat.code_referral,
                            NULL as project_id,
                            zakat.fullname AS fullname,
                            zakat.payment_method AS bank,
                            zakat.phone AS phone,
                            zakat.type AS type,
                            payment_methods.name AS data_payment_method,
                            NULL AS project_title,
                            zakat.amount + IFNULL(zakat.unique_code, 0) AS amount,
                            zakat.created_at AS created_at,
                            'Zakat' as akad
                        "))
                        ->join('users', 'users.code_referral', '=', 'zakat.code_referral')
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->whereNotNull('zakat.code_referral')
                        ->where('zakat.status', 'success');

                if (!empty(request('from_date'))) {
                    $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:00'))));
                    $donations = $donations->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:00'))));
                    $zakats = $zakats->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:00'))));
                }

                if (!empty(request('cari'))) {
                    switch (request('type_cari')) {
                        case 'Nama Referrer':
                            $supporters = $supporters->where('users.name','like',"%".request('cari')."%");
                            $donations = $donations->where('users.name','like',"%".request('cari')."%");
                            $zakats = $zakats->where('users.name','like',"%".request('cari')."%");
                            break;

                        case 'Kode Referrer':
                            $supporters = $supporters->where('supporters.code_referral','like',"%".request('cari')."%");
                            $donations = $donations->where('donations.code_referral','like',"%".request('cari')."%");
                            $zakats = $zakats->where('zakat.code_referral','like',"%".request('cari')."%");
                            break;

                        case 'Nama Pemberi Infak':
                            $supporters = $supporters->where('supporters.fullname','like',"%".request('cari')."%");
                            $donations = $donations->where('donations.fullname','like',"%".request('cari')."%");
                            $zakats = $zakats->where('zakat.fullname','like',"%".request('cari')."%");
                            break;

                        default:
                            break;
                    }
                }

                $categories = Category::all('id', 'category_name');
                $supporters = $this->filterCategories(request('category_ids'), $supporters);

                $transactions = $donations
                                ->union($supporters)
                                ->union($zakats)
                                ->orderByRaw('(created_at) desc')
                                ->get();

                $items = $transactions
                        ->map(function($item) {
                            return [
                                $item->referrer_name,
                                $item->code_referral,
                                $item->fullname,
                                ($item['data_payment_method'] ? $item['data_payment_method'] : ''),
                                $item->phone,
                                priceFormat($item['amount']),
                                $item->akad,
                                $item->project_title ?: '-',
                                $item->type ?: '-',
                                $item->created_at,
                            ];
                        })
                        ->toArray();

                $sheet->fromArray($items ,null, 'A1', false, false)->prependRow([
                    'Nama Referrer',
                    'Kode Referrer',
                    'Nama Pemberi Infak',
                    'Bank',
                    'No. WhatsApp',
                    'Nominal',
                    'Jenis Akad',
                    'Judul Campaign',
                    'Jenis Zakat',
                    'Tanggal Transaksi',
                ]);
            });
        })->export('xlsx');
    }

    public function getProject(Request $request)
    {
        $data['title'] = 'Referral Infak Terikat';
        $supporters = Supporter::select(DB::raw('
                    users.name AS referrer_name,
                    supporters.code_referral,
                    supporters.project_id,
                    supporters.fullname AS fullname,
                    supporters.payment_method AS bank,
                    supporters.phone AS phone,
                    payment_methods.name AS data_payment_method,
                    projects.title AS project_title,
                    supporters.money + IFNULL(supporters.unique_code, 0) AS money,
                    supporters.created_at AS created_at
                '))
                ->join('users', 'users.code_referral', '=', 'supporters.code_referral')
                ->join('projects', 'projects.id', '=', 'supporters.project_id')
                ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                ->whereNotNull('supporters.code_referral')
                ->where('supporters.status', 'accept');

        if(!empty(request('from_date')) && empty(request('cari'))) {
            $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
        }elseif(!empty(request('cari'))){
            if(!empty(request('from_date'))){
                $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
            }
            if(request('type_cari') == 'Nama Referrer'){
                $supporters = $supporters->where('users.name','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Kode Referrer'){
                $supporters = $supporters->where('supporters.code_referral','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Nama Pemberi Infak'){
                $supporters = $supporters->where('supporters.fullname','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Nama Campaign'){
                $supporters = $supporters->where('projects.title','like',"%".request('cari')."%");
            }
        }
        $categories = Category::all('id', 'category_name');
        $supporters = $this->filterCategories(request('category_ids'), $supporters);

        $count = $supporters->get()->count();
        $total = $supporters->get();
        $total = $total->sum('money') + $total->sum('unique_code');

        $supporters = $supporters->orderBy('supporters.created_at', 'DESC')
        ->paginate(10)
        ->appends(request()->query());

        $data['supporters'] = $supporters;
        $data['total'] = $total;
        $data['count'] = $count;
        $data['categories'] = $categories;

        return view('admin::contents.referral.supporter', $data);
    }

    public function getProjectExport(Request $request)
	{
        Excel::create('Referral Infak Terikat', function($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                $supporter = Supporter::select(DB::raw('
                            users.name AS referrer_name,
                            supporters.code_referral,
                            supporters.project_id,
                            supporters.fullname AS fullname,
                            supporters.payment_method AS bank,
                            supporters.phone AS phone,
                            payment_methods.name AS data_payment_method,
                            projects.title AS project_title,
                            supporters.money + IFNULL(supporters.unique_code, 0) AS money,
                            supporters.created_at AS created_at
                        '))
                        ->join('users', 'users.code_referral', '=', 'supporters.code_referral')
                        ->join('projects', 'projects.id', '=', 'supporters.project_id')
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->whereNotNull('supporters.code_referral')
                        ->where('supporters.status', 'accept');
                if(!empty(request('from_date')) && empty(request('cari'))) {
                    $supporter = $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
                }elseif(!empty(request('cari'))){
                    if(!empty(request('from_date'))){
                        $supporter = $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if(request('type_cari') == 'Nama Referrer'){
                        $supporter = $supporter->where('users.name','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Kode Referrer'){
                        $supporter = $supporter->where('supporters.code_referral','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Nama Pemberi Infak'){
                        $supporter = $supporter->where('supporters.fullname','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Nama Campaign'){
                        $supporter = $supporter->where('projects.title','like',"%".request('cari')."%");
                    }
                }
                $categories = Category::all('id', 'category_name');
                $supporter = $this->filterCategories(request('category_ids'), $supporter);

                $supporter = $supporter->orderBy('supporters.created_at', 'DESC')
                ->get();

                $arr =array();
                foreach($supporter as $item) {
                    $data =  array($item['referrer_name'], $item['code_referral'], $item['fullname'], ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item['phone'], $item['project_title'], priceFormat($item['money']), $item['created_at']);
                    array_push($arr, $data);
                }
                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                    'Nama Referrer','Kode Referrer', 'Nama Pemberi Infak', 'Bank', 'No. WhatsApp', 'Nama Campaign', 'Nominal', 'Tanggal Transaksi'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getDonation()
    {
        $data['title'] = 'Referral Infak Umum';
        $donations = Donation::select(DB::raw('
                    users.name AS referrer_name,
                    donations.code_referral AS code_referral,
                    donations.fullname AS fullname,
                    donations.payment_method AS bank,
                    payment_methods.name AS data_payment_method,
                    donations.phone AS phone,
                    donations.amount + IFNULL(donations.unique_code, 0) AS amount,
                    donations.created_at AS created_at
                '))
                ->join('users', 'users.code_referral', '=', 'donations.code_referral')
                ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                ->whereNotNull('donations.code_referral')
                ->where('donations.status', 'success');
        if(!empty(request('from_date')) && empty(request('cari'))) {
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
        }elseif(!empty(request('cari'))){
            if(!empty(request('from_date'))){
                $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
            }
            if(request('type_cari') == 'Nama Referrer'){
                $donations = $donations->where('users.name','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Kode Referrer'){
                $donations = $donations->where('donations.code_referral','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Nama Pemberi Infak'){
                $donations = $donations->where('donations.fullname','like',"%".request('cari')."%");
            }
        }
        $count = $donations->get()->count();
        $total = $donations->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $donations = $donations->orderBy('donations.created_at', 'DESC')
        ->paginate(10)
        ->appends(request()->query());

        $data['donations'] = $donations;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.referral.donation', $data);
    }

    public function getDonationExport(Request $request)
	{
        Excel::create('Referral Infak Umum', function($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                $donation = Donation::select(DB::raw('
                            users.name AS referrer_name,
                            donations.code_referral AS code_referral,
                            donations.fullname AS fullname,
                            donations.payment_method AS bank,
                            payment_methods.name AS data_payment_method,
                            donations.phone AS phone,
                            donations.amount + IFNULL(donations.unique_code, 0) AS amount,
                            donations.created_at AS created_at
                        '))
                        ->join('users', 'users.code_referral', '=', 'donations.code_referral')
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->whereNotNull('donations.code_referral')
                        ->where('donations.status', 'success');
                if(!empty(request('from_date')) && empty(request('cari'))) {
                    $donation = $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
                }elseif(!empty(request('cari'))){
                    if(!empty(request('from_date'))){
                        $donation = $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if(request('type_cari') == 'Nama Referrer'){
                        $donation = $donation->where('users.name','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Kode Referrer'){
                        $donation = $donation->where('donations.code_referral','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Nama Pemberi Infak'){
                        $donation = $donation->where('donations.fullname','like',"%".request('cari')."%");
                    }
                }
                $donation = $donation->orderBy('donations.created_at', 'DESC')
                ->get();

                $arr =array();
                foreach($donation as $item) {
                    $data =  array($item['referrer_name'], $item['code_referral'], $item['fullname'], ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item['phone'], priceFormat($item['amount']), $item['created_at']);
                    array_push($arr, $data);
                }
                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                    'Nama Referrer','Kode Referrer', 'Nama Pemberi Infak', 'Bank', 'No. WhatsApp', 'Nominal', 'Tanggal Transaksi'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getZakat()
    {
        $data['title'] = 'Referral Zakat';
        $zakat = Zakat::select(DB::raw('
                    users.name AS referrer_name,
                    zakat.code_referral,
                    zakat.fullname AS fullname,
                    zakat.payment_method AS bank,
                    payment_methods.name AS data_payment_method,
                    zakat.phone AS phone,
                    zakat.type AS type,
                    zakat.amount + IFNULL(zakat.unique_code, 0) AS amount,
                    zakat.created_at AS created_at
                '))
                ->join('users', 'users.code_referral', '=', 'zakat.code_referral')
                ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                ->whereNotNull('zakat.code_referral')
                ->where('zakat.status', 'success');
        if(!empty(request('from_date')) && empty(request('cari'))) {
            $zakat = $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
        }elseif(!empty(request('cari'))){
            if(!empty(request('from_date'))){
                $zakat = $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
            }
            if(request('type_cari') == 'Nama Referrer'){
                $zakat = $zakat->where('users.name','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Kode Referrer'){
                $zakat = $zakat->where('zakat.code_referral','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Nama Pemberi Zakat'){
                $zakat = $zakat->where('zakat.fullname','like',"%".request('cari')."%");
            }elseif(request('type_cari') == 'Tipe Zakat'){
                $zakat = $zakat->where('zakat.type','like',"%".request('cari')."%");
            }
        }
        $count = $zakat->get()->count();
        $total = $zakat->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $zakat = $zakat->orderBy('zakat.created_at', 'DESC')
        ->paginate(10)
        ->appends(request()->query());

        $data['zakat'] = $zakat;
        $data['total'] = $total;
        $data['count'] = $count;

        return view('admin::contents.referral.zakat', $data);
    }

    public function getZakatExport(Request $request)
	{
        Excel::create('Referral Zakat', function($excel) {
            $excel->sheet('Sheet1', function($sheet) {
                $zakat = Zakat::select(DB::raw('
                            users.name AS referrer_name,
                            zakat.code_referral AS code_referral,
                            zakat.fullname AS fullname,
                            zakat.payment_method AS bank,
                            payment_methods.name AS data_payment_method,
                            zakat.phone AS phone,
                            zakat.type AS type,
                            zakat.amount + IFNULL(zakat.unique_code, 0) AS amount,
                            zakat.created_at AS created_at
                        '))
                        ->join('users', 'users.code_referral', '=', 'zakat.code_referral')
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->whereNotNull('zakat.code_referral')
                        ->where('zakat.status', 'success');
                if(!empty(request('from_date')) && empty(request('cari'))) {
                    $zakat = $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
                }elseif(!empty(request('cari'))){
                    if(!empty(request('from_date'))){
                        $zakat = $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s',strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if(request('type_cari') == 'Nama Referrer'){
                        $zakat = $zakat->where('users.name','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Kode Referrer'){
                        $zakat = $zakat->where('zakat.code_referral','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Nama Pemberi Zakat'){
                        $zakat = $zakat->where('zakat.fullname','like',"%".request('cari')."%");
                    }elseif(request('type_cari') == 'Tipe Zakat'){
                        $zakat = $zakat->where('zakat.type','like',"%".request('cari')."%");
                    }
                }
                $zakat = $zakat->orderBy('zakat.created_at', 'DESC')
                ->get();

                $arr =array();
                foreach($zakat as $item) {
                    $data =  array($item['referrer_name'], $item['code_referral'], $item['fullname'], ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item['phone'], $item['type'], priceFormat($item['amount']), $item['created_at']);
                    array_push($arr, $data);
                }
                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                    'Nama Referrer','Kode Referrer', 'Nama Pemberi Zakat', 'Bank', 'No. WhatsApp', 'Tipe Zakat', 'Nominal', 'Tanggal Transaksi'
                    )
                );
            });
        })->export('xlsx');
    }

    public function filterCategories($category_ids, $supporters) {
        if(!empty($category_ids)) {
            // lakukan filter category
            $supporters = $supporters->whereIn('projects.category_id', $category_ids);
        }
        return $supporters;
    }

}
