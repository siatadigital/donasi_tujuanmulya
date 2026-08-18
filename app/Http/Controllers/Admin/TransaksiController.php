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

class TransaksiController extends Controller
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
            $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
        }

        if (!empty($request->cari)) {
            switch ($request->type_cari) {
                case 'Judul Infak Terikat':
                    $supporters = $supporters->whereHas('project', function($query) use ($request) {
                        $query->where('title', 'like', "%".$request->cari."%");
                    });

                    $donations = $donations->where(\DB::raw('donations.id'), 0);
                    $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                    break;

                case 'Nama Pemberi Infak':
                    $supporters = $supporters->where('fullname','like',"%".$request->cari."%");
                    $donations = $donations->where('fullname','like',"%".$request->cari."%");
                    $zakats = $zakats->where('fullname','like',"%".$request->cari."%");
                    break;

                case 'No. WhatsApp':
                    $supporters = $supporters->where('phone','like',"%".$request->cari."%");
                    $donations = $donations->where('phone','like',"%".$request->cari."%");
                    $zakats = $zakats->where('phone','like',"%".$request->cari."%");
                    break;

                case 'Bank Tujuan':
                    $supporters = $supporters->where('payment_method','like',"%".$request->cari."%");
                    $donations = $donations->where('payment_method','like',"%".$request->cari."%");
                    $zakats = $zakats->where('payment_method','like',"%".$request->cari."%");
                    break;

                case 'Nominal/Kode Unik':
                    $supporters = $supporters->where(function($q) use ($request) {
                        $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $donations = $donations->where(function($q) use ($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $zakats = $zakats->where(function($q) use ($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email','like',"%".$request->cari."%");
                    $donations = $donations->where('email','like',"%".$request->cari."%");
                    $zakats = $zakats->where('email','like',"%".$request->cari."%");
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
            'title' => 'Semua Transaksi Success',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $paginator->getData(),
            'paginator' => $paginator,
            'categories' => $categories,
        ];

        return view('admin::contents.transaksi.index', $data);
    }

    public function getJsonSuccessTransaksi(Request $request)
    {

        if(request()->ajax())
        {
            $supporters = Supporter::success()
                        ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                        ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                        ->join('projects', 'projects.id', '=', 'supporters.project_id')
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(supporters.created_at) desc');
    
            $donations = Donation::success()
                        ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                        ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(donations.created_at) desc');
    
            $zakats = Zakat::success()
                    ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                    ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                    ->orderByRaw('(zakat.created_at) desc');

            $supporter = $this->filterCategories($request, $supporter);

            if(!empty($request->from_date) && empty($request->cari))
            {
                $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
            }elseif(!empty($request->cari)){
                if(!empty($request->from_date))
                {
                    $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                }
                if($request->type_cari == 'Nama Pemberi Infak'){
                    $supporter->where('fullname','like',"%".$request->cari."%");
                    $donation->where('fullname','like',"%".$request->cari."%");
                    $zakat->where('fullname','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'No. WhatsApp'){
                    $supporter->where('phone','like',"%".$request->cari."%");
                    $donation->where('phone','like',"%".$request->cari."%");
                    $zakat->where('phone','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Bank Tujuan'){
                    $supporter->where('payment_method','like',"%".$request->cari."%");
                    $donation->where('payment_method','like',"%".$request->cari."%");
                    $zakat->where('payment_method','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Nominal/Kode Unik'){
                    $supporter->where(function($q) use ($request) {
                        $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $donation->where(function($q) use ($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $zakat->where(function($q) use ($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Email'){
                    $supporter->where('email','like',"%".$request->cari."%");
                    $donation->where('email','like',"%".$request->cari."%");
                    $zakat->where('email','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Kota'){
                    $supporter->where('city','like',"%".$request->cari."%");
                    $donation->where('city','like',"%".$request->cari."%");
                    $zakat->where('city','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Akad'){
                    if(strtolower($request->cari) == 'infak terikat'){
                        $data = $supporter->orderByRaw('(created_at) desc')->get();
                    }elseif(strtolower($request->cari) == 'infak umum'){
                        $data = $donation->orderByRaw('(created_at) desc')->get();
                    }elseif(strtolower($request->cari) == 'zakat'){
                        $data = $zakat->orderByRaw('(created_at) desc')->get();
                    }
                }
            } else{
                $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
            }

            return Datatables::of($data)
                ->editColumn('details', function ($data){
                    $project = Project::where('id',$data['project_id'])->first();
                    if($project){
                        $html = '<div><label>Nama Project/Campaign : </label>'.$project->title.'</div>';
                        if ($data['reward_id']) {
                            $reward = json_decode($data['reward_id'], true);
                            $html .= '<div><label>Opsi Dipilih : </label><br>';
                            foreach($reward as $item) {
                                $html .= $item['desc'].'('.$item['price'].' x '.$item['qty'].')<br>';
                            }
                            $html .= '</div>';
                        }
                        $html .= '<div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>';
                        $html .= '<div><label>Bank : </label>'. $data['data_payment_method'].'</div>';
                        $html .= '<div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa/Niat Atas Nama : </label><br>'.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        return $html;
                    }else{
                        if($data['akad'] == 'Zakat'){
                            return '<div><label>Tipe : </label> '.$data['type'].'</div><div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>
                            <div><label>Bank : </label>'. $data['data_payment_method'].'</div>
                            <div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa : </label> '.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        }else{
                            return '<div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>
                            <div><label>Bank : </label>'. $data['data_payment_method'].'</div>
                            <div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa : </label> '.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        }
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
    }

    public function getSuccessTransaksiExport(Request $request)
	{
		Excel::create('Semua Transaksi Success', function($excel) use ($request) {
            $excel->sheet('Sheet1', function($sheet) use ($request) {
                $supporters = Supporter::success()
                            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                            ->join('projects', 'projects.id', '=', 'supporters.project_id')
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->orderByRaw('(supporters.created_at) desc');
        
                $donations = Donation::success()
                            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->orderByRaw('(donations.created_at) desc');
        
                $zakats = Zakat::success()
                        ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                        ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(zakat.created_at) desc');
                $supporters = $this->filterCategories($request, $supporters);
                if (!empty($request->from_date)) {
                    $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                }

                if (!empty($request->cari)) {
                    switch ($request->type_cari) {
                        case 'Judul Infak Terikat':
                            $supporters = $supporters->whereHas('project', function($query) use ($request) {
                                $query->where('title', 'like', "%".$request->cari."%");
                            });

                            $donations = $donations->where(\DB::raw('donations.id'), 0);
                            $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                            break;

                        case 'Nama Pemberi Infak':
                            $supporters = $supporters->where('fullname','like',"%".$request->cari."%");
                            $donations = $donations->where('fullname','like',"%".$request->cari."%");
                            $zakats = $zakats->where('fullname','like',"%".$request->cari."%");
                            break;

                        case 'No. WhatsApp':
                            $supporters = $supporters->where('phone','like',"%".$request->cari."%");
                            $donations = $donations->where('phone','like',"%".$request->cari."%");
                            $zakats = $zakats->where('phone','like',"%".$request->cari."%");
                            break;

                        case 'Bank Tujuan':
                            $supporters = $supporters->where('payment_method','like',"%".$request->cari."%");
                            $donations = $donations->where('payment_method','like',"%".$request->cari."%");
                            $zakats = $zakats->where('payment_method','like',"%".$request->cari."%");
                            break;

                        case 'Nominal/Kode Unik':
                            $supporters = $supporters->where(function($q) use($request) {
                                $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            $donations = $donations->where(function($q) use($request) {
                                $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            $zakats = $zakats->where(function($q) use($request) {
                                $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            break;

                        case 'Email':
                            $supporters = $supporters->where('email','like',"%".$request->cari."%");
                            $donations = $donations->where('email','like',"%".$request->cari."%");
                            $zakats = $zakats->where('email','like',"%".$request->cari."%");
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

                $all = $donations
                       ->union($supporters)
                       ->union($zakats)
                       ->orderByRaw('(created_at) desc')
                       ->get();

                $arr =array();
                foreach($all as $item) {
                    if($item->is_checked == false){
                        $is_checked = 'Belum Dicek';
                    }else{
                        $is_checked = 'Sudah Dicek';
                    }
                    $projects = Project::where('id',$item->project_id)->first();
                    if($projects){
                        $data =  array($item->fullname, $projects->title,'-', priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city, $item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                        array_push($arr, $data);
                    }else{
                        if($item->akad == 'Zakat'){
                            $data =  array($item->fullname,'-', $item['type'], priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                            ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city,$item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                            array_push($arr, $data);
                        }else{
                            $data =  array($item->fullname,'-', '-', priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                            ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city,$item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                            array_push($arr, $data);
                        }
                    }
                }

                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                    'Nama Pemberi Infak','Nama Project/Campaign','Tipe','Nominal',  'Bank', 'Email','No. Whatsapp','Dukungan/Doa','Kota','Akad','Status', 'Kode Unik', 'Tanggal', 'Catatan', 'Status Check'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getPendingTransaksi(Request $request)
    {
        $supporters = Supporter::pending()
                    ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                    ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                    ->join('projects', 'projects.id', '=', 'supporters.project_id')
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::pending()
                    ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                    ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::pending()
                ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $categories = Category::all('id', 'category_name');

        if (!empty($request->from_date)) {
            $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
        }

        if (!empty($request->cari)) {
            switch ($request->type_cari) {
                case 'Judul Infak Terikat':
                    $supporters = $supporters->whereHas('project', function($query) use ($request) {
                        $query->where('title', 'like', "%".$request->cari."%");
                    });

                    $donations = $donations->where(\DB::raw('donations.id'), 0);
                    $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                    break;

                case 'Nama Pemberi Infak':
                    $supporters = $supporters->where('fullname','like',"%".$request->cari."%");
                    $donations = $donations->where('fullname','like',"%".$request->cari."%");
                    $zakats = $zakats->where('fullname','like',"%".$request->cari."%");
                    break;

                case 'No. WhatsApp':
                    $supporters = $supporters->where('phone','like',"%".$request->cari."%");
                    $donations = $donations->where('phone','like',"%".$request->cari."%");
                    $zakats = $zakats->where('phone','like',"%".$request->cari."%");
                    break;

                case 'Bank Tujuan':
                    $supporters = $supporters->where('payment_method','like',"%".$request->cari."%");
                    $donations = $donations->where('payment_method','like',"%".$request->cari."%");
                    $zakats = $zakats->where('payment_method','like',"%".$request->cari."%");
                    break;

                case 'Nominal/Kode Unik':
                    $supporters = $supporters->where(function($q) use($request) {
                        $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $donations = $donations->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $zakats = $zakats->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email','like',"%".$request->cari."%");
                    $donations = $donations->where('email','like',"%".$request->cari."%");
                    $zakats = $zakats->where('email','like',"%".$request->cari."%");
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

        $transactions = $donations
                        ->union($supporters)
                        ->union($zakats)
                        ->orderByRaw('(created_at) desc');

        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $page = $request->get('page') ?: 1;
        $paginator = (new Paginator())->setQuery($transactions)->setCurrentPage($page);

        $data = [
            'title' => 'Semua Transaksi Pending',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $paginator->getData(),
            'paginator' => $paginator,
            'categories' => $categories,
        ];

        return view('admin::contents.transaksi.index', $data);
    }

    public function getJsonPendingTransaksi(Request $request)
    {
        if(request()->ajax())
        {
            $supporter = Supporter::pending()
                        ->select(\DB::raw("id, fullname, reward_id, project_id, NULL as type, money as amount, payment_method, unique_code, email, phone,notes,created_at.status, created_at, expired_at, is_checked, check_note"))
                        ->selectRaw("'Infak Terikat' as akad")
                        ->orderByRaw('(created_at) desc');
            $donation = Donation::pending()
                        ->select(\DB::raw("id, fullname, NULL as reward_id, NULL as project_id, NULL as type, amount, payment_method, unique_code, email, phone,notes,created_at.status, created_at,expired_at, is_checked, check_note"))
                        ->selectRaw("'Infak Umum' as akad")
                        ->orderByRaw('(created_at) desc');
            $zakat = Zakat::pending()
                    ->select(\DB::raw("id, fullname, NULL as reward_id, NULL as project_id, type, amount, payment_method, unique_code, email, phone,notes,created_at.status, created_at,expired_at, is_checked, check_note"))
                    ->selectRaw("'Zakat' as akad")
                    ->orderByRaw('(created_at) desc');
            $supporter = $this->filterCategories($request, $supporter);
            if(!empty($request->from_date) && empty($request->cari))
            {
                $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
            }elseif(!empty($request->cari)){
                if(!empty($request->from_date))
                {
                    $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                }
                if($request->type_cari == 'Nama Pemberi Infak'){
                    $supporter->where('fullname','like',"%".$request->cari."%");
                    $donation->where('fullname','like',"%".$request->cari."%");
                    $zakat->where('fullname','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'No. WhatsApp'){
                    $supporter->where('phone','like',"%".$request->cari."%");
                    $donation->where('phone','like',"%".$request->cari."%");
                    $zakat->where('phone','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Bank Tujuan'){
                    $supporter->where('payment_method','like',"%".$request->cari."%");
                    $donation->where('payment_method','like',"%".$request->cari."%");
                    $zakat->where('payment_method','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Nominal/Kode Unik'){
                    $supporter->where(function($q) use($request) {
                        $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $donation->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $zakat->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Email'){
                    $supporter->where('email','like',"%".$request->cari."%");
                    $donation->where('email','like',"%".$request->cari."%");
                    $zakat->where('email','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Kota'){
                    $supporter->where('city','like',"%".$request->cari."%");
                    $donation->where('city','like',"%".$request->cari."%");
                    $zakat->where('city','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Akad'){
                    if(strtolower($request->cari) == 'infak terikat'){
                        $data = $supporter->orderByRaw('(created_at) desc')->get();
                    }elseif(strtolower($request->cari) == 'infak umum'){
                        $data = $donation->orderByRaw('(created_at) desc')->get();
                    }elseif(strtolower($request->cari) == 'zakat'){
                        $data = $zakat->orderByRaw('(created_at) desc')->get();
                    }
                }
            } else{
                $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
            }

            return Datatables::of($data)
                ->editColumn('details', function ($data){
                    $project = Project::where('id',$data['project_id'])->first();
                    if($project){
                        $html = '<div><label>Nama Project/Campaign : </label>'.$project->title.'</div>';
                        if ($data['reward_id']) {
                            $reward = json_decode($data['reward_id'], true);
                            $html .= '<div><label>Opsi Dipilih : </label><br>';
                            foreach($reward as $item) {
                                $html .= $item['desc'].'('.$item['price'].' x '.$item['qty'].')<br>';
                            }
                            $html .= '</div>';
                        }
                        $html .= '<div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>';
                        $html .= '<div><label>Bank : </label>'. $data['data_payment_method'].'</div>';
                        $html .= '<div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa/Niat Atas Nama : </label><br>'.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        return $html;
                    }else{
                        if($data['akad'] == 'Zakat'){
                            return '<div><label>Tipe : </label> '.$data['type'].'</div><div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>
                            <div><label>Bank : </label>'. $data['data_payment_method'].'</div>
                            <div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa : </label> '.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        }else{
                            return '<div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>
                            <div><label>Bank : </label>'. $data['data_payment_method'].'</div>
                            <div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa : </label> '.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        }
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
    }

    public function getPendingTransaksiExport(Request $request)
	{
		Excel::create('Semua Transaksi Pending', function($excel) use ($request) {
            $excel->sheet('Sheet1', function($sheet) use ($request) {
                $supporters = Supporter::pending()
                            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                            ->join('projects', 'projects.id', '=', 'supporters.project_id')
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->orderByRaw('(supporters.created_at) desc');
        
                $donations = Donation::pending()
                            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->orderByRaw('(donations.created_at) desc');
        
                $zakats = Zakat::pending()
                        ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                        ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(zakat.created_at) desc');

                $supporters = $this->filterCategories($request, $supporters);
                if (!empty($request->from_date)) {
                    $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                }

                if (!empty($request->cari)) {
                    switch ($request->type_cari) {
                        case 'Judul Infak Terikat':
                            $supporters = $supporters->whereHas('project', function($query) use ($request) {
                                $query->where('title', 'like', "%".$request->cari."%");
                            });

                            $donations = $donations->where(\DB::raw('donations.id'), 0);
                            $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                            break;

                        case 'Nama Pemberi Infak':
                            $supporters = $supporters->where('fullname','like',"%".$request->cari."%");
                            $donations = $donations->where('fullname','like',"%".$request->cari."%");
                            $zakats = $zakats->where('fullname','like',"%".$request->cari."%");
                            break;

                        case 'No. WhatsApp':
                            $supporters = $supporters->where('phone','like',"%".$request->cari."%");
                            $donations = $donations->where('phone','like',"%".$request->cari."%");
                            $zakats = $zakats->where('phone','like',"%".$request->cari."%");
                            break;

                        case 'Bank Tujuan':
                            $supporters = $supporters->where('payment_method','like',"%".$request->cari."%");
                            $donations = $donations->where('payment_method','like',"%".$request->cari."%");
                            $zakats = $zakats->where('payment_method','like',"%".$request->cari."%");
                            break;

                        case 'Nominal/Kode Unik':
                            $supporters = $supporters->where(function($q) use($request) {
                                $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            $donations = $donations->where(function($q) use($request) {
                                $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            $zakats = $zakats->where(function($q) use($request) {
                                $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            break;

                        case 'Email':
                            $supporters = $supporters->where('email','like',"%".$request->cari."%");
                            $donations = $donations->where('email','like',"%".$request->cari."%");
                            $zakats = $zakats->where('email','like',"%".$request->cari."%");
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

                $all = $donations
                       ->union($supporters)
                       ->union($zakats)
                       ->orderByRaw('(created_at) desc')
                       ->get();

                $arr =array();
                foreach($all as $item) {
                    if($item->is_checked == false){
                        $is_checked = 'Belum Dicek';
                    }else{
                        $is_checked = 'Sudah Dicek';
                    }
                    $projects = Project::where('id',$item->project_id)->first();
                    if($projects){
                        $data =  array($item->fullname, $projects->title,'-', priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city, $item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                        array_push($arr, $data);
                    }else{
                        if($item->akad == 'Zakat'){
                            $data =  array($item->fullname,'-', $item['type'], priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                            ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city,$item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                            array_push($arr, $data);
                        }else{
                            $data =  array($item->fullname,'-', '-', priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                            ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city,$item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                            array_push($arr, $data);
                        }
                    }
                }

                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                    'Nama Pemberi Infak','Nama Project/Campaign','Tipe','Nominal',  'Bank', 'Email','No. Whatsapp','Dukungan/Doa','Kota','Akad','Status', 'Kode Unik', 'Tanggal', 'Catatan', 'Status Check'
                    )
                );
            });
        })->export('xlsx');
    }

    public function getExpiredTransaksi(Request $request)
    {
        $supporters = Supporter::expired()
                    ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                    ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                    ->join('projects', 'projects.id', '=', 'supporters.project_id')
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $donations = Donation::expired()
                    ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                    ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $zakats = Zakat::expired()
                ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                ->join('payment_methods', 'payment_methods.code', '=', 'payment_method');

        $supporters = $this->filterCategories($request, $supporters);

        if (!empty($request->from_date)) {
            $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
            $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
            $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
        }

        if (!empty($request->cari)) {
            switch ($request->type_cari) {
                case 'Judul Infak Terikat':
                    $supporters = $supporters->whereHas('project', function($query) use ($request) {
                        $query->where('title', 'like', "%".$request->cari."%");
                    });

                    $donations = $donations->where(\DB::raw('donations.id'), 0);
                    $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                    break;

                case 'Nama Pemberi Infak':
                    $supporters = $supporters->where('fullname','like',"%".$request->cari."%");
                    $donations = $donations->where('fullname','like',"%".$request->cari."%");
                    $zakats = $zakats->where('fullname','like',"%".$request->cari."%");
                    break;

                case 'No. WhatsApp':
                    $supporters = $supporters->where('phone','like',"%".$request->cari."%");
                    $donations = $donations->where('phone','like',"%".$request->cari."%");
                    $zakats = $zakats->where('phone','like',"%".$request->cari."%");
                    break;

                case 'Bank Tujuan':
                    $supporters = $supporters->where('payment_method','like',"%".$request->cari."%");
                    $donations = $donations->where('payment_method','like',"%".$request->cari."%");
                    $zakats = $zakats->where('payment_method','like',"%".$request->cari."%");
                    break;

                case 'Nominal/Kode Unik':
                    $supporters = $supporters->where(function($q) use($request) {
                        $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $donations = $donations->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $zakats = $zakats->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    break;

                case 'Email':
                    $supporters = $supporters->where('email','like',"%".$request->cari."%");
                    $donations = $donations->where('email','like',"%".$request->cari."%");
                    $zakats = $zakats->where('email','like',"%".$request->cari."%");
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

        $transactions = $donations
                        ->union($supporters)
                        ->union($zakats)
                        ->orderByRaw('(created_at) desc');

        $total = $transactions->get();
        $total = $total->sum('amount') + $total->sum('unique_code');

        $page = $request->get('page') ?: 1;
        $paginator = (new Paginator())->setQuery($transactions)->setCurrentPage($page);
        $categories = Category::all('id' , 'category_name');
        $data = [
            'title' => 'Semua Transaksi Expired',
            'total' => $total,
            'count' => $transactions->get()->count(),
            'transactions' => $paginator->getData(),
            'paginator' => $paginator,
            'categories' => $categories,
        ];

        return view('admin::contents.transaksi.index', $data);
    }

    public function getJsonExpiredTransaksi(Request $request)
    {
        if(request()->ajax())
        {
            $supporters = Supporter::expired()
                        ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                        ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                        ->join('projects', 'projects.id', '=', 'supporters.project_id')
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(supporters.created_at) desc');
    
            $donations = Donation::expired()
                        ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                        ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(donations.created_at) desc');
    
            $zakats = Zakat::expired()
                    ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                    ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                    ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                    ->orderByRaw('(zakat.created_at) desc');
            $supporter = $this->filterCategories($request, $supporter);
            if(!empty($request->from_date) && empty($request->cari))
            {
                $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
            }elseif(!empty($request->cari)){
                if(!empty($request->from_date))
                {
                    $supporter->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $donation->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $zakat->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                }
                if($request->type_cari == 'Nama Pemberi Infak'){
                    $supporter->where('fullname','like',"%".$request->cari."%");
                    $donation->where('fullname','like',"%".$request->cari."%");
                    $zakat->where('fullname','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'No. WhatsApp'){
                    $supporter->where('phone','like',"%".$request->cari."%");
                    $donation->where('phone','like',"%".$request->cari."%");
                    $zakat->where('phone','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Bank Tujuan'){
                    $supporter->where('payment_method','like',"%".$request->cari."%");
                    $donation->where('payment_method','like',"%".$request->cari."%");
                    $zakat->where('payment_method','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Nominal/Kode Unik'){
                    $supporter->where(function($q) use($request) {
                        $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $donation->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $zakat->where(function($q) use($request) {
                        $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                    });
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Email'){
                    $supporter->where('email','like',"%".$request->cari."%");
                    $donation->where('email','like',"%".$request->cari."%");
                    $zakat->where('email','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Kota'){
                    $supporter->where('city','like',"%".$request->cari."%");
                    $donation->where('city','like',"%".$request->cari."%");
                    $zakat->where('city','like',"%".$request->cari."%");
                    $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
                }elseif($request->type_cari == 'Akad'){
                    if(strtolower($request->cari) == 'infak terikat'){
                        $data = $supporter->orderByRaw('(created_at) desc')->get();
                    }elseif(strtolower($request->cari) == 'infak umum'){
                        $data = $donation->orderByRaw('(created_at) desc')->get();
                    }elseif(strtolower($request->cari) == 'zakat'){
                        $data = $zakat->orderByRaw('(created_at) desc')->get();
                    }
                }
            } else{
                $data = $donation->union($supporter)->union($zakat)->orderByRaw('(created_at) desc')->get();
            }

            return Datatables::of($data)
                ->editColumn('details', function ($data){
                    $project = Project::where('id',$data['project_id'])->first();
                    if($project){
                        $html = '<div><label>Nama Project/Campaign : </label>'.$project->title.'</div>';
                        if ($data['reward_id']) {
                            $reward = json_decode($data['reward_id'], true);
                            $html .= '<div><label>Opsi Dipilih : </label><br>';
                            foreach($reward as $item) {
                                $html .= $item['desc'].'('.$item['price'].' x '.$item['qty'].')<br>';
                            }
                            $html .= '</div>';
                        }
                        $html .= '<div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>';
                        $html .= '<div><label>Bank : </label>'. $data['data_payment_method'].'</div>';
                        $html .= '<div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa/Niat Atas Nama : </label><br>'.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        return $html;
                    }else{
                        if($data['akad'] == 'Zakat'){
                            return '<div><label>Tipe : </label> '.$data['type'].'</div><div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>
                            <div><label>Bank : </label>'. $data['data_payment_method'].'</div>
                            <div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa : </label> '.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        }else{
                            return '<div><label>Nominal : </label> '.priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']).'</div>
                            <div><label>Bank : </label>'. $data['data_payment_method'].'</div>
                            <div>Email : '.$data['email'] .' <br> No. WhatsApp : '. $data['phone'] .' <br> <label>Dukungan/Doa : </label> '.$data['notes'].'</div> <br> <label>Kota : </label> '.$data['city'].'</div>';
                        }
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
    }

    public function getExpiredTransaksiExport(Request $request)
	{
        Excel::create('Semua Transaksi Expired', function($excel) use ($request) {
            $excel->sheet('Sheet1', function($sheet) use ($request) {
                $supporters = Supporter::expired()
                            ->select(\DB::raw("supporters.id, fullname, project_id, reward_id, NULL as type, money as amount, projects.title AS project_title, payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,supporters.status, supporters.created_at, expired_at, is_checked, check_note"))
                            ->selectRaw("'Infak Terikat' as akad, 'project' as endpoint")
                            ->join('projects', 'projects.id', '=', 'supporters.project_id')
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->orderByRaw('(supporters.created_at) desc');
        
                $donations = Donation::expired()
                            ->select(\DB::raw("donations.id, fullname,NULL as project_id, NULL as reward_id, NULL as type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,donations.status, donations.created_at,expired_at, is_checked, check_note"))
                            ->selectRaw("'Infak Umum' as akad, 'donation' as endpoint")
                            ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                            ->orderByRaw('(donations.created_at) desc');
        
                $zakats = Zakat::expired()
                        ->select(\DB::raw("zakat.id, fullname,NULL as project_id, NULL as reward_id, type, amount, NULL AS project_title, NULL as payment_method, payment_methods.name AS data_payment_method, unique_code, email, phone,notes,city,zakat.status, zakat.created_at,expired_at, is_checked, check_note"))
                        ->selectRaw("'Zakat' as akad, 'zakat' as endpoint")
                        ->join('payment_methods', 'payment_methods.code', '=', 'payment_method')
                        ->orderByRaw('(zakat.created_at) desc');

                $supporters = $this->filterCategories($request, $supporters);

                if (!empty($request->from_date)) {
                    $supporters = $supporters->whereBetween('supporters.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $donations = $donations->whereBetween('donations.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                    $zakats = $zakats->whereBetween('zakat.created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))));
                }

                if (!empty($request->cari)) {
                    switch ($request->type_cari) {
                        case 'Judul Infak Terikat':
                            $supporters = $supporters->whereHas('project', function($query) use ($request) {
                                $query->where('title', 'like', "%".$request->cari."%");
                            });

                            $donations = $donations->where(\DB::raw('donations.id'), 0);
                            $zakats = $zakats->where(\DB::raw('zakat.id'), 0);
                            break;

                        case 'Nama Pemberi Infak':
                            $supporters = $supporters->where('fullname','like',"%".$request->cari."%");
                            $donations = $donations->where('fullname','like',"%".$request->cari."%");
                            $zakats = $zakats->where('fullname','like',"%".$request->cari."%");
                            break;

                        case 'No. WhatsApp':
                            $supporters = $supporters->where('phone','like',"%".$request->cari."%");
                            $donations = $donations->where('phone','like',"%".$request->cari."%");
                            $zakats = $zakats->where('phone','like',"%".$request->cari."%");
                            break;

                        case 'Bank Tujuan':
                            $supporters = $supporters->where('payment_method','like',"%".$request->cari."%");
                            $donations = $donations->where('payment_method','like',"%".$request->cari."%");
                            $zakats = $zakats->where('payment_method','like',"%".$request->cari."%");
                            break;

                        case 'Nominal/Kode Unik':
                            $supporters = $supporters->where(function($q) use ($request) {
                                $q->where('money',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('money + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            $donations = $donations->where(function($q) use ($request) {
                                $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            $zakats = $zakats->where(function($q) use ($request) {
                                $q->where('amount',request('cari'))->orWhere('unique_code','like',"%".$request->cari."%")->orWhereRaw('amount + unique_code LIKE "%'.$request->cari.'%"');
                            });
                            break;

                        case 'Email':
                            $supporters = $supporters->where('email','like',"%".$request->cari."%");
                            $donations = $donations->where('email','like',"%".$request->cari."%");
                            $zakats = $zakats->where('email','like',"%".$request->cari."%");
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

                $all = $donations
                        ->union($supporters)
                        ->union($zakats)
                        ->orderByRaw('(created_at) desc')
                        ->get();

                $arr =array();
                foreach($all as $item) {
                    if($item->is_checked == false){
                        $is_checked = 'Belum Dicek';
                    }else{
                        $is_checked = 'Sudah Dicek';
                    }
                    $projects = Project::where('id',$item->project_id)->first();
                    if($projects){
                        $data =  array($item->fullname, $projects->title,'-', priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                        ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city, $item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                        array_push($arr, $data);
                    }else{
                        if($item->akad == 'Zakat'){
                            $data =  array($item->fullname,'-', $item['type'], priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                            ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city,$item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                            array_push($arr, $data);
                        }else{
                            $data =  array($item->fullname,'-', '-', priceFormat($item['unique_code'] ? $item['amount'] + $item['unique_code'] : $item['amount']),
                            ($item['data_payment_method'] ? $item['data_payment_method'] : ''), $item->email, $item->phone, $item->notes, $item->city,$item['akad'], $item->status,(string)$item->unique_code,$item->created_at,$item->check_note,$is_checked);
                            array_push($arr, $data);
                        }
                    }
                }

                //set the titles
                $sheet->fromArray($arr,null,'A1',false,false)->prependRow(array(
                    'Nama Pemberi Infak','Nama Project/Campaign','Tipe','Nominal',  'Bank', 'Email','No. Whatsapp','Dukungan/Doa','Kota','Akad','Status', 'Kode Unik', 'Tanggal', 'Catatan', 'Status Check'
                    )
                );
            });
        })->export('xlsx');
    }

    public function filterCategories($request, $supporters) {
        if(!empty($request->category_ids)) {
            // lakukan filter category
            $category_ids = $request->category_ids;
            $supporters = $supporters->whereHas('project', function($query) use ($category_ids) {
                $query->whereIn('category_id', $category_ids);
            });
        }
        return $supporters;
    }
}

