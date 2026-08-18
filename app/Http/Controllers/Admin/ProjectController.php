<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Supporter;
use App\Models\Donation;
use App\Models\Category;
use App\Models\Zakat;
use App\Models\User;
use App\Models\Option;
use Illuminate\Http\Request;
use Datatables;
use Excel;
use Illuminate\Support\Facades\Crypt;

class ProjectController extends Controller
{
    public function __construct() {}

    public function confirmCheck($id)
    {
        Supporter::where('id', $id)->update([
            'is_checked' => true
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function cancelCheck($id)

    {

        Supporter::where('id', $id)->update([
            'is_checked' => false
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function submitNote(Request $request)
    {

        Supporter::where('id', request('id'))->update([
            'check_note' => request('note')
        ]);

        return response()->json(['success' => "save sukses"]);
    }

    public function getTransaksiSuccess()
    {
        $sum1 = Supporter::success()->sum('money');
        $sum2 = Donation::success()->sum('amount');
        $sum3 = Zakat::success()->sum('amount');
        $data = [
            'title' => 'Success Infak Terikat',
            'total' => 'Total : ' . priceFormat($sum1 + $sum2 + $sum3),
        ];

        return view('admin::contents.transaksi.index', $data);
    }

    public function getTransaksiSuccessJson()
    {
        $supporter = Supporter::success()->select(\DB::raw("fullname, money as amount"));
        $data['data'] = Donation::success()->select(\DB::raw("fullname, amount"))->union($supporter)->get();

        return view('admin::contents.transaksi.index', $data);
    }

    public function getIndex(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $projects = Project::where('fundraiser_project_id', NULL);
        if (isset($keyword) and $keyword != '') {
            $projects = $projects->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $projects = $projects->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Semua Project/Campaign',
            'projects' => $projects,
        ];

        return view('admin::contents.project.index', $data);
    }

    public function getShow($id)
    {
        $data = [
            'title' => 'Project/Campaign Detail',
            'project' => Project::with('user')->findOrFail($id),
        ];
        return view('admin::contents.project.show', $data);
    }

    public function getActive(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $projects = Project::where('status', 'active')->where('fundraiser_project_id', NULL);
        if (isset($keyword) and $keyword != '') {
            $projects = $projects->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $projects = $projects->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Active Project/Campaign',
            'projects' => $projects,
        ];
        return view('admin::contents.project.index', $data);
    }

    public function getPending(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $projects = Project::where('status', 'pending')->where('fundraiser_project_id', NULL);
        if (isset($keyword) and $keyword != '') {
            $projects = $projects->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $projects = $projects->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Pending Project/Campaign',
            'projects' => $projects,
        ];
        return view('admin::contents.project.pending', $data);
    }

    public function getReject(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $projects = Project::where('status', 'reject')->where('fundraiser_project_id', NULL);
        if (isset($keyword) and $keyword != '') {
            $projects = $projects->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $projects = $projects->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Rejected Project/Campaign',
            'projects' => $projects,
        ];
        return view('admin::contents.project.pending', $data);
    }

    public function getFundraiser(Request $request)
    {
        $keyword = $request->get('keyword', '');

        $projects = Project::where('fundraiser_project_id', '<>', 'NULL')->where('fundraiser_project_id', '<>', '0');
        if (isset($keyword) and $keyword != '') {
            $projects = $projects->where('title', 'LIKE', '%' . $keyword . '%');
        }
        $projects = $projects->orderBy('created_at', 'desc')->paginate(20);

        $data = [
            'title' => 'Semua Fundraiser Project/Campaign',
            'projects' => $projects,
        ];

        return view('admin::contents.project.fundraiser', $data);
    }

    public function putAccept($id)
    {
        $project = Project::findOrFail($id);
        $project->status = 'active';
        $project->time_start = time();
        $project->save();

        try {
            \Mail::queue('emails.project-activated', ['project' => $project], function ($message) use ($project) {
                $message->to($project->user->email)
                    ->subject('Campaign Telah Diterbitkan');
            });
        } catch (\Exception $e) {
            // failed send email
        }

        return redirectMessage(
            route('admin.project.getPending'),
            $project['name'] . ' successfully Confirmed !!',
            '',
            'success'
        );
    }

    public function putReject($id)
    {
        $project = Project::findOrFail($id);
        $project->status = 'reject';
        $project->save();

        return redirectMessage(
            'back',
            $project['name'] . ' successfully Rejected !!',
            '',
            'success'
        );
    }

    public function getSuccessSupporterExport(Request $request)
    {
        Excel::create('Infak Terikat Success', function ($excel) use ($request) {
            $excel->sheet('Sheet1', function ($sheet) use ($request) {
                $supporter = Supporter::with('user', 'project', 'reward')
                    ->success();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $supporter->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $supporter->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Judul Infak Terikat') {
                        $supporter->whereHas('project', function ($query) {
                            $query->where('title', 'like', "%" . request('cari') . "%");
                        });
                    } elseif (request('type_cari') == 'Nama Pemberi Infak') {
                        $supporter->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $supporter->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $supporter->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $supporter->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('money + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $supporter->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $supporter->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $arr = array();
                $supporter = $this->filterCategories($request, $supporter);
                foreach ($supporter->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['money'] + $item['unique_code'] : $item['money']),
                        ($item['data_payment_method'] ? $item['data_payment_method']['name'] : ''),
                        $item['email'],
                        $item['phone'],
                        $item['notes'],
                        $item['city'],
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

    public function getPendingSupporterExport(Request $request)
    {
        Excel::create('Infak Terikat Pending', function ($excel) use ($request) {
            $excel->sheet('Sheet1', function ($sheet) use ($request) {
                $supporter = Supporter::with('user', 'project', 'reward')
                    ->pending();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $supporter->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $supporter->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Judul Infak Terikat') {
                        $supporter->whereHas('project', function ($query) {
                            $query->where('title', 'like', "%" . request('cari') . "%");
                        });
                    } elseif (request('type_cari') == 'Nama Pemberi Infak') {
                        $supporter->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $supporter->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $supporter->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $supporter->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('money + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $supporter->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $supporter->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $supporter = $this->filterCategories($request, $supporter);
                $arr = array();
                foreach ($supporter->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['money'] + $item['unique_code'] : $item['money']),
                        ($item['data_payment_method'] ? $item['data_payment_method']['name'] : ''),
                        $item['email'],
                        $item['phone'],
                        $item['notes'],
                        $item['city'],
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

    public function getExpiredSupporterExport(Request $request)
    {
        Excel::create('Infak Terikat Expired', function ($excel) use ($request) {
            $excel->sheet('Sheet1', function ($sheet) use ($request) {
                $supporter = Supporter::with('user', 'project', 'reward')
                    ->expired();
                if (!empty(request('from_date')) && empty(request('cari'))) {
                    $supporter->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                } elseif (!empty(request('cari'))) {
                    if (!empty(request('from_date'))) {
                        $supporter->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
                    }
                    if (request('type_cari') == 'Judul Infak Terikat') {
                        $supporter->whereHas('project', function ($query) {
                            $query->where('title', 'like', "%" . request('cari') . "%");
                        });
                    } elseif (request('type_cari') == 'Nama Pemberi Infak') {
                        $supporter->where('fullname', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'No. WhatsApp') {
                        $supporter->where('phone', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Bank Tujuan') {
                        $supporter->where('payment_method', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                        $supporter->where(function ($q) {
                            $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('money + unique_code LIKE "%' . request('cari') . '%"');
                        });
                    } elseif (request('type_cari') == 'Email') {
                        $supporter->where('email', 'like', "%" . request('cari') . "%");
                    } elseif (request('type_cari') == 'Kota') {
                        $supporter->where('city', 'like', "%" . request('cari') . "%");
                    }
                }
                $supporter = $this->filterCategories($request, $supporter);
                $arr = array();
                foreach ($supporter->orderBy('created_at', 'DESC')->get() as $item) {
                    if ($item->is_checked == false) {
                        $is_checked = 'Belum Dicek';
                    } else {
                        $is_checked = 'Sudah Dicek';
                    }
                    $data =  array(
                        $item->fullname,
                        priceFormat($item['unique_code'] ? $item['money'] + $item['unique_code'] : $item['money']),
                        ($item['data_payment_method'] ? $item['data_payment_method']['name'] : ''),
                        $item['email'],
                        $item['phone'],
                        $item['notes'],
                        $item['city'],
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

    public function getJsonSuccessSupporter(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->from_date) && empty($request->cari)) {
                $data = Supporter::success()->with('user', 'project', 'reward')
                    ->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:59'))));
            } elseif (!empty($request->cari)) {
                $data = Supporter::success()->with('user', 'project', 'reward');
                if (!empty($request->from_date)) {
                    $data->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:59'))));
                }
                if ($request->type_cari == 'Nama Pemberi Infak') {
                    $data->where('fullname', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'No. WhatsApp') {
                    $data->where('phone', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data->where('payment_method', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Nominal/Kode Unik') {
                    $data->where(function ($q) use ($request) {
                        $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . $request->cari . "%")->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                } elseif ($request->type_cari == 'Email') {
                    $data->where('email', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Kota') {
                    $data->where('city', 'like', "%" . $request->cari . "%");
                }
            } else {
                $data = Supporter::success()->with('user', 'project', 'reward');
            }

            return Datatables::of($data->orderBy('created_at', 'DESC')->get())
                ->editColumn('details', function ($data) {
                    return '<div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['money'] + $data['unique_code'] : $data['money']) . '</div>
                    <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                    <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                })
                ->editColumn('status_project', function ($data) {
                    return strtoupper($data['status']);
                })
                ->editColumn('kode_unik', function ($data) {
                    return $data['unique_code'] ? $data['money'] + $data['unique_code'] : '-';
                })
                ->editColumn('tanggal', function ($data) {
                    return formatTime($data['created_at'], 'd F Y, H:i');
                })
                ->make(true);
        }
    }

    public function getJsonPendingSupporter(Request $request)
    {
        if (request()->ajax()) {

            if (!empty($request->from_date) && empty($request->cari)) {
                $data = Supporter::pending()->with('user', 'project', 'reward')
                    ->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:59'))));
            } elseif (!empty($request->cari)) {
                $data = Supporter::pending()->with('user', 'project', 'reward');
                if (!empty($request->from_date)) {
                    $data->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:59'))));
                }
                if ($request->type_cari == 'Nama Pemberi Infak') {
                    $data->where('fullname', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'No. WhatsApp') {
                    $data->where('phone', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data->where('payment_method', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Nominal/Kode Unik') {
                    $data->where(function ($q) use ($request) {
                        $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . $request->cari . "%")->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                } elseif ($request->type_cari == 'Email') {
                    $data->where('email', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Kota') {
                    $data->where('city', 'like', "%" . $request->cari . "%");
                }
            } else {
                $data = Supporter::pending()->with('user', 'project', 'reward');
            }

            return Datatables::of($data->orderBy('created_at', 'DESC')->get())
                ->editColumn('details', function ($data) {
                    $html = '';
                    if ($data['reward_id']) {
                        $reward = json_decode($data['reward_id'], true);
                        $html .= '<div><label>Opsi Dipilih : </label><br>';
                        foreach ($reward as $item) {
                            $html .= $item['desc'] . '(' . $item['price'] . ' x ' . $item['qty'] . ')<br>';
                        }
                        $html .= '</div>';
                    }
                    $html .= '<div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['amount'] + $data['unique_code'] : $data['amount']) . '</div>';
                    $html .= '<div><label>Bank : </label>' . $data['data_payment_method'] . '</div>';
                    $html .= '<div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa/Niat Atas Nama : </label><br>' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                    return $html;
                })
                ->editColumn('status_project', function ($data) {
                    return strtoupper($data['status']);
                })
                ->editColumn('kode_unik', function ($data) {
                    return $data['unique_code'] ? $data['money'] + $data['unique_code'] : '-';
                })
                ->editColumn('tanggal', function ($data) {
                    return formatTime($data['created_at'], 'd F Y, H:i');
                })
                ->make(true);
        }
    }

    public function getJsonExpiredSupporter(Request $request)
    {
        if (request()->ajax()) {

            if (!empty($request->from_date) && empty($request->cari)) {
                $data = Supporter::expired()->with('user', 'project', 'reward')
                    ->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:59'))));
            } elseif (!empty($request->cari)) {
                $data = Supporter::expired()->with('user', 'project', 'reward');
                if (!empty($request->from_date)) {
                    $data->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to_date . ' 23:59:59'))));
                }
                if ($request->type_cari == 'Nama Pemberi Infak') {
                    $data->where('fullname', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'No. WhatsApp') {
                    $data->where('phone', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data->where('payment_method', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Nominal/Kode Unik') {
                    $data->where(function ($q) use ($request) {
                        $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . $request->cari . "%")->orWhereRaw('money + unique_code LIKE "%' . $request->cari . '%"');
                    });
                } elseif ($request->type_cari == 'Email') {
                    $data->where('email', 'like', "%" . $request->cari . "%");
                } elseif ($request->type_cari == 'Kota') {
                    $data->where('city', 'like', "%" . $request->cari . "%");
                }
            } else {
                $data = Supporter::expired()->with('user', 'project', 'reward');
            }

            return Datatables::of($data->orderBy('created_at', 'DESC')->get())
                ->editColumn('details', function ($data) {
                    return '<div><label>Nominal : </label> ' . priceFormat($data['unique_code'] ? $data['money'] + $data['unique_code'] : $data['money']) . '</div>
                    <div><label>Bank : </label>' . $data['data_payment_method'] . '</div>
                    <div>Email : ' . $data['email'] . ' <br> No. WhatsApp : ' . $data['phone'] . ' <br> <label>Dukungan/Doa : </label> ' . $data['notes'] . '</div> <br> <label>Kota : </label> ' . $data['city'] . '</div>';
                })
                ->editColumn('status_project', function ($data) {
                    return strtoupper($data['status']);
                })
                ->editColumn('kode_unik', function ($data) {
                    return $data['unique_code'] ? $data['money'] + $data['unique_code'] : '-';
                })
                ->editColumn('tanggal', function ($data) {
                    return formatTime($data['created_at'], 'd F Y, H:i');
                })
                ->make(true);
        }
    }

    public function getSuccessSupporter(Request $request)
    {
        $data['title'] = 'Success Infak Terikat';
        $supporters = Supporter::success();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }

            if (request('type_cari') == 'Judul Infak Terikat') {
                $supporters = $supporters->whereHas('project', function ($query) {
                    $query->where('title', 'like', "%" . request('cari') . "%");
                });
            } elseif (request('type_cari') == 'Nama Pemberi Infak') {
                $supporters = $supporters->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $supporters = $supporters->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $supporters = $supporters->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $supporters = $supporters->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('money + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $supporters = $supporters->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $supporters = $supporters->where('city', 'like', "%" . request('cari') . "%");
            }
        }

        $categories = Category::all('id', 'category_name');
        $supporters = $this->filterCategories($request, $supporters);

        $count = $supporters->get()->count();
        $total = $supporters->get();
        $total = $total->sum('money') + $total->sum('unique_code');

        $supporters = $supporters->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['supporters'] = $supporters;
        $data['total'] = $total;
        $data['count'] = $count;
        $data['categories'] = $categories;

        return view('admin::contents.project.supporter', $data);
    }

    public function getPendingSupporter(Request $request)
    {
        $data['title'] = 'Pending Infak Terikat';
        $supporters = Supporter::pending();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }

            if (request('type_cari') == 'Judul Infak Terikat') {
                $supporters = $supporters->whereHas('project', function ($query) {
                    $query->where('title', 'like', "%" . request('cari') . "%");
                });
            } elseif (request('type_cari') == 'Nama Pemberi Infak') {
                $supporters = $supporters->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $supporters = $supporters->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $supporters = $supporters->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $supporters = $supporters->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('money + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $supporters = $supporters->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $supporters = $supporters->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $categories = Category::all('id', 'category_name');
        $supporters = $this->filterCategories($request, $supporters);

        $count = $supporters->get()->count();
        $total = $supporters->get();
        $total = $total->sum('money') + $total->sum('unique_code');

        $supporters = $supporters->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['supporters'] = $supporters;
        $data['total'] = $total;
        $data['count'] = $count;
        $data['categories'] = $categories;

        return view('admin::contents.project.supporter', $data);
    }

    public function getExpiredSupporter(Request $request)
    {
        $data['title'] = 'Expired Infak Terikat';
        $supporters = Supporter::expired();
        if (!empty(request('from_date')) && empty(request('cari'))) {
            $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
        } elseif (!empty(request('cari'))) {
            if (!empty(request('from_date'))) {
                $supporters = $supporters->whereBetween('created_at', array(date('Y-m-d H:i:s', strtotime(request('from_date') . ' 00:00:00')), date('Y-m-d H:i:s', strtotime(request('to_date') . ' 23:59:59'))));
            }

            if (request('type_cari') == 'Judul Infak Terikat') {
                $supporters = $supporters->whereHas('project', function ($query) {
                    $query->where('title', 'like', "%" . request('cari') . "%");
                });
            } elseif (request('type_cari') == 'Nama Pemberi Infak') {
                $supporters = $supporters->where('fullname', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'No. WhatsApp') {
                $supporters = $supporters->where('phone', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Bank Tujuan') {
                $supporters = $supporters->where('payment_method', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Nominal/Kode Unik') {
                $supporters = $supporters->where(function ($q) {
                    $q->where('money', request('cari'))->orWhere('unique_code', 'like', "%" . request('cari') . "%")->orWhereRaw('money + unique_code LIKE "%' . request('cari') . '%"');
                });
            } elseif (request('type_cari') == 'Email') {
                $supporters = $supporters->where('email', 'like', "%" . request('cari') . "%");
            } elseif (request('type_cari') == 'Kota') {
                $supporters = $supporters->where('city', 'like', "%" . request('cari') . "%");
            }
        }
        $categories = Category::all('id', 'category_name');
        $supporters = $this->filterCategories($request, $supporters);

        $count = $supporters->get()->count();
        $total = $supporters->get();
        $total = $total->sum('money') + $total->sum('unique_code');

        $supporters = $supporters->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $data['supporters'] = $supporters;
        $data['total'] = $total;
        $data['count'] = $count;
        $data['categories'] = $categories;

        return view('admin::contents.project.supporter', $data);
    }

    public function putAcceptSupporter($supporter_id)
    {
        $supporter = Supporter::findOrFail($supporter_id);
        $project = $supporter->project;

        if ($supporter['user_id'] == 0) {
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


            try {
                // Tentukan lokasi untuk menyimpan file PDF
                $pdfPath = public_path("/pdf/" . $data['id'] . "-project.pdf");

                // Mengecek apakah file PDF sudah ada
                if (!file_exists($pdfPath)) {
                    $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]); // Timeout lebih lama

                    // Melakukan permintaan untuk membuat PDF
                    $res = $client->request('GET', url() . '/create-invoice/' . Crypt::encrypt($data['id'] . "-project"));
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
                    $this->SendMessagesData($data, url() . "/pdf/" . $data['id'] . "-project.pdf");
                }
            } catch (\Throwable $th) {
                // Log atau tangani error jika diperlukan
                // Log::error("Error occurred while generating PDF: " . $th->getMessage());
            }

            // 
            try {
                \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                    $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
                });
            } catch (\Exception $e) {
                // failed send email
            }

            try {
                if ($supporter->code_referral) {
                    $user = User::where('is_internal', TRUE)
                        ->where('code_referral', $supporter->code_referral)
                        ->first();

                    $emailPayload = [
                        'user' => $user,
                        'donorName' => !$supporter->is_anonim ? $supporter->fullname : 'Hamba Allah',
                        'type' => 'Penggalangan Dana',
                        'amount' => $supporter->money,
                        'projectTitle' => $project->title,
                    ];

                    \Mail::queue('emails.referral-donate', $emailPayload, function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Donasi Campaign masuk melalui link referral Anda');
                    });
                }

                $emailPayload = [
                    'project' => $project,
                    'supporter' => $supporter,
                ];

                \Mail::queue('emails.supporter-donate', $emailPayload, function ($message) use ($project) {
                    $message->to($project->user->email)
                        ->subject('Dana telah masuk di campaign Anda');
                });
            } catch (\Exception $e) {
                // failed send email
            }

            return redirectMessage(
                route('admin.project.getPendingSupporter'),
                'Successfully Accept !!',
                '',
                'success'
            );
        }

        $u = User::where('id', $supporter->user_id)->firstOrFail();

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

        try {
            // Tentukan lokasi untuk menyimpan file PDF
            $pdfPath = public_path("/pdf/" . $data['id'] . "-project.pdf");

            // Mengecek apakah file PDF sudah ada
            if (!file_exists($pdfPath)) {
                $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]); // Timeout lebih lama

                // Melakukan permintaan untuk membuat PDF
                $res = $client->request('GET', url() . '/create-invoice/' . Crypt::encrypt($data['id'] . "-project"));
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
                $this->SendMessagesData($data, url() . "/pdf/" . $data['id'] . "-project.pdf");
            }
        } catch (\Throwable $th) {
            // Log atau tangani error jika diperlukan
            // Log::error("Error occurred while generating PDF: " . $th->getMessage());
        }


        try {
            if ($supporter->code_referral) {
                $user = User::where('is_internal', TRUE)
                    ->where('code_referral', $supporter->code_referral)
                    ->first();

                $emailPayload = [
                    'user' => $user,
                    'donorName' => !$supporter->is_anonim ? $supporter->fullname : 'Hamba Allah',
                    'type' => 'Penggalangan Dana',
                    'amount' => $supporter->money,
                    'projectTitle' => $project->title,
                ];

                \Mail::queue('emails.referral-donate', $emailPayload, function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Donasi Campaign masuk melalui link referral Anda');
                });
            }

            $emailPayload = [
                'project' => $project,
                'supporter' => $supporter,
            ];

            \Mail::queue('emails.supporter-donate', $emailPayload, function ($message) use ($project) {
                $message->to($project->user->email)
                    ->subject('Dana telah masuk di campaign Anda');
            });

            \Mail::queue('emails.thanks', $data, function ($message) use ($supporter) {
                $message->to($supporter->email)->subject('Konfirmasi Infak pada Campaign Berhasil');
            });
        } catch (\Exception $e) {
            // failed send email
        }

        return redirectMessage(
            route('admin.project.getPendingSupporter'),
            'Successfully Accept !!',
            '',
            'success'
        );
    }

    public function putRejectSupporter($supporter_id)
    {
        $supporter = Supporter::findOrFail($supporter_id);
        $project = $supporter->project;

        app('ProjectRepository')->rejectSupporter($project, $supporter);

        return redirectMessage(
            route('admin.project.getPendingSupporter'),
            'Successfully Reject !!',
            '',
            'success'
        );
    }

    public function changeFeatured($id)
    {
        $totalFeatured = Project::where('is_featured', 1)->count();
        $project = Project::findOrFail($id);
        if ($totalFeatured >= 4 and $project->is_featured == 0) {
            return redirectMessage(
                'back',
                $project['name'] . ' failed change Featured, max 4 featured !!',
                '',
                'danger'
            );
        }

        $project->is_featured = !$project->is_featured;
        $project->save();

        return redirectMessage(
            'back',
            $project['name'] . ' successfully change Featured !!',
            '',
            'success'
        );
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
            // $phone =  $data['phone']; //'081232619333'; // $data['phone']; ganti dengan phone dari data jika diperlukan
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
