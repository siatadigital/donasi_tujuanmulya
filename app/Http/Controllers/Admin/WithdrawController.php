<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectWithdraw;
use App\Models\Update;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Datatables;
use DB;

class WithdrawController extends Controller
{
    public function getJsonSuccessWithdraw(Request $request)
    {
        if (request()->ajax()) {
            $data = ProjectWithdraw::success();
            $data = $this->filterCategories($request, $data);

            if (!empty($request->from_date) && empty($request->cari)) {
                $data = $data
                        ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                        ->orderBy('created_at','DESC')
                        ->get();
            } elseif (!empty($request->from_date) && !empty($request->cari) && !empty($request->type_cari)) {
                if ($request->type_cari == 'Nama Project') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->whereHas('project', function($query) {
                                $query->where('title','like',"%".$request->cari."%");
                            })
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->where('account_bank','like',"%".$request->cari."%")
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif($request->type_cari == 'Nominal') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->where('amount',request('cari'))
                            ->orderBy('created_at','DESC')
                            ->get();
                }
            } elseif(empty($request->from_date) && !empty($request->cari) && !empty($request->type_cari)) {
                if ($request->type_cari == 'Nama Project') {
                    $data = $data
                            ->whereHas('project', function($query) {
                                $query->where('title','like',"%".$request->cari."%");
                            })
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data = $data
                            ->where('account_bank','like',"%".$request->cari."%")
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif($request->type_cari == 'Nominal') {
                    $data = $data
                            ->where('amount',request('cari'))
                            ->orderBy('created_at','DESC')
                            ->get();
                }
            } else {
                $data = $data->orderBy('created_at','DESC')->get();
            }

            return Datatables::of($data)
                ->editColumn('project_name', function ($data) {
                    return $data['project']['title'];
                })
                ->editColumn('details', function ($data) {
                    $amount = 'Rp.' . number_format($data['amount']);

                    return "
                        <div>
                            <label>Nominal : </label> {$amount}
                            <br>
                            <label>Nama Bank : </label> {$data['account_bank']}
                            <br>
                            <label>Nama Pemilik Rekening : </label> {$data['account_name']}
                            <br>
                            <label>Nomor Rekening : </label> {$data['account_number']}
                            <br>
                            <label>Deskripsi : </label>
                            <p>{$data['description']}</p>
                        </div>
                    ";
                })
                ->editColumn('status', function ($data) {
                    return strtoupper($data['status']);
                })
                ->editColumn('tanggal', function ($data) {
                    return formatTime($data['created_at'], 'd F Y, H:i');
                })
                ->make(true);
        }
    }

    public function getJsonPendingWithdraw(Request $request)
    {
        if (request()->ajax()) {
            $data = ProjectWithdraw::pending();
            $data = $this->filterCategories($request, $data);
            if (!empty($request->from_date) && empty($request->cari)) {
                $data = $data
                        ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                        ->orderBy('created_at','DESC')
                        ->get();
            } elseif (!empty($request->from_date) && !empty($request->cari) && !empty($request->type_cari)) {
                if ($request->type_cari == 'Nama Project') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->whereHas('project', function($query) {
                                $query->where('title','like',"%".$request->cari."%");
                            })
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->where('account_bank','like',"%".$request->cari."%")
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif($request->type_cari == 'Nominal') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->where('amount',request('cari'))
                            ->orderBy('created_at','DESC')
                            ->get();
                }
            } elseif(empty($request->from_date) && !empty($request->cari) && !empty($request->type_cari)) {
                if ($request->type_cari == 'Nama Project') {
                    $data = $data
                            ->whereHas('project', function($query) {
                                $query->where('title','like',"%".$request->cari."%");
                            })
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data = $data
                            ->where('account_bank','like',"%".$request->cari."%")
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif($request->type_cari == 'Nominal') {
                    $data = $data
                            ->where('amount',request('cari'))
                            ->orderBy('created_at','DESC')
                            ->get();
                }
            } else {
                $data = $data->orderBy('created_at','DESC')->get();
            }

            return Datatables::of($data)
                ->editColumn('project_name', function ($data) {
                    return $data['project']['title'];
                })
                ->editColumn('details', function ($data) {
                    $amount = 'Rp.' . number_format($data['amount']);

                    return "
                        <div>
                            <label>Nominal : </label> {$amount}
                            <br>
                            <label>Nama Bank : </label> {$data['account_bank']}
                            <br>
                            <label>Nama Pemilik Rekening : </label> {$data['account_name']}
                            <br>
                            <label>Nomor Rekening : </label> {$data['account_number']}
                            <br>
                            <label>Deskripsi : </label>
                            <p>{$data['description']}</p>
                        </div>
                    ";
                })
                ->editColumn('status', function ($data) {
                    return strtoupper($data['status']);
                })
                ->editColumn('tanggal', function ($data) {
                    return formatTime($data['created_at'], 'd F Y, H:i');
                })
                ->make(true);
        }
    }

    public function getJsonFailedWithdraw(Request $request)
    {
        if (request()->ajax()) {
            $data = ProjectWithdraw::failed();
            $data = $this->filterCategories($request, $data);
            if (!empty($request->from_date) && empty($request->cari)) {
                $data = $data
                        ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                        ->orderBy('created_at','DESC')
                        ->get();
            } elseif (!empty($request->from_date) && !empty($request->cari) && !empty($request->type_cari)) {
                if ($request->type_cari == 'Nama Project') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->whereHas('project', function($query) {
                                $query->where('title','like',"%".$request->cari."%");
                            })
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->where('account_bank','like',"%".$request->cari."%")
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif($request->type_cari == 'Nominal') {
                    $data = $data
                            ->whereBetween('created_at', array(date('Y-m-d H:i:s',strtotime($request->from_date . ' 00:00:00')), date('Y-m-d H:i:s',strtotime($request->to_date . ' 23:59:00'))))
                            ->where('amount',request('cari'))
                            ->orderBy('created_at','DESC')
                            ->get();
                }
            } elseif(empty($request->from_date) && !empty($request->cari) && !empty($request->type_cari)) {
                if ($request->type_cari == 'Nama Project') {
                    $data = $data
                            ->whereHas('project', function($query) {
                                $query->where('title','like',"%".$request->cari."%");
                            })
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif ($request->type_cari == 'Bank Tujuan') {
                    $data = $data
                            ->where('account_bank','like',"%".$request->cari."%")
                            ->orderBy('created_at','DESC')
                            ->get();
                } elseif($request->type_cari == 'Nominal') {
                    $data = $data
                            ->where('amount',request('cari'))
                            ->orderBy('created_at','DESC')
                            ->get();
                }
            } else {
                $data = $data->orderBy('created_at','DESC')->get();
            }

            return Datatables::of($data)
                ->editColumn('project_name', function ($data) {
                    return $data['project']['title'];
                })
                ->editColumn('details', function ($data) {
                    $amount = 'Rp.' . number_format($data['amount']);

                    return "
                        <div>
                            <label>Nominal : </label> {$amount}
                            <br>
                            <label>Nama Bank : </label> {$data['account_bank']}
                            <br>
                            <label>Nama Pemilik Rekening : </label> {$data['account_name']}
                            <br>
                            <label>Nomor Rekening : </label> {$data['account_number']}
                            <br>
                            <label>Deskripsi : </label>
                            <p>{$data['description']}</p>
                        </div>
                    ";
                })
                ->editColumn('status', function ($data) {
                    return strtoupper($data['status']);
                })
                ->editColumn('tanggal', function ($data) {
                    return formatTime($data['created_at'], 'd F Y, H:i');
                })
                ->make(true);
        }
    }

    public function getSuccessWithdraw()
    {
        $sum = ProjectWithdraw::success()->sum('amount');
        $categories = Category::all('id', 'category_name');

        $data = [
            'title' => 'Success Withdraw',
            'total' => 'Total Withdraw : ' . priceFormat($sum),
            'datatableUrl' => route('admin.withdraw.getJsonSuccessWithdraw'),
            'categories' => $categories,
        ];

        return view('admin::contents.withdraw.index', $data);
    }

    public function getPendingWithdraw()
    {
        $sum = ProjectWithdraw::pending()->sum('amount');
        $categories = Category::all('id', 'category_name');

        $data = [
            'title' => 'Pending Withdraw',
            'total' => 'Total Withdraw : ' . priceFormat($sum),
            'datatableUrl' => route('admin.withdraw.getJsonPendingWithdraw'),
            'categories' => $categories,
        ];

        return view('admin::contents.withdraw.index', $data);
    }

    public function getFailedWithdraw()
    {
        $sum = ProjectWithdraw::failed()->sum('amount');
        $categories = Category::all('id', 'category_name');

        $data = [
            'title' => 'Failed Withdraw',
            'total' => 'Total Withdraw : ' . priceFormat($sum),
            'datatableUrl' => route('admin.withdraw.getJsonFailedWithdraw'),
            'categories' => $categories,
        ];

        return view('admin::contents.withdraw.index', $data);
    }

    public function putSuccessWithdraw($id)
    {
        $withdraw = ProjectWithdraw::findOrFail($id);

        DB::beginTransaction();

        try {
            $withdraw->update(['status' => 'accept']);

            $amount = number_format($withdraw->amount);
            $accountBank = strtoupper($withdraw->account_bank);
            $accountName = strtoupper($withdraw->account_name);

            $description = "
                <p>Ke rekening {$accountBank} *** **** **** **** **** a/n {$accountName}</p>
                <br>
                <p>Rencana penggunaan dana: {$withdraw->description}</p>
            ";

            Update::create([
                'project_id' => $withdraw->project_id,
                'title' => "Pencairan Dana Rp. {$amount}",
                'description' => trim($description),
            ]);

            DB::commit();

            $project = $withdraw->project;

            $emailPayload = [
                'project' => $project,
                'withdraw' => $withdraw,
            ];

            try {
                \Mail::queue('emails.project-withdraw', $emailPayload, function ($message) use ($project) {
                    $message->to($project->user->email)
                            ->subject('Dana Campaign Telah Dicairkan');
                });
            }catch(\Exception $e) {
                // failed send email
            }

            return redirectMessage(
                route('admin.withdraw.getPendingWithdraw'),
                'Successfully Accept !!',
                '',
                'success'
            );
        }catch(\Exception $e) {
            DB::rollback();

            return redirectMessage(
                route('admin.withdraw.getPendingWithdraw'),
                'System error !!',
                '',
                'error'
            );
        }
    }

    public function putRejectWithdraw($id)
    {
        $withdraw = ProjectWithdraw::findOrFail($id);

        DB::beginTransaction();

        try {
            $withdraw->update(['status' => 'failed']);

            DB::commit();

            return redirectMessage(
                route('admin.withdraw.getPendingWithdraw'),
                'Successfully Reject !!',
                '',
                'success'
            );
        }catch(\Exception $e) {
            DB::rollback();

            return redirectMessage(
                route('admin.withdraw.getPendingWithdraw'),
                'System error !!',
                '',
                'error'
            );
        }
    }


    public function filterCategories($request, $project_withdraw) {
        if(!empty($request->category_ids)) {
            // lakukan filter category
            $category_ids = $request->category_ids;
            $project_withdraw = $project_withdraw->whereHas('project', function($query) use ($category_ids) {
                $query->whereIn('category_id', $category_ids);
            });
        }
        return $project_withdraw;
    }


}
