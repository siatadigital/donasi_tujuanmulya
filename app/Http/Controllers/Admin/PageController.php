<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Project;
use App\Models\Supporter;
use App\Models\Donation;
use App\Models\Zakat;
use App\Models\BlogViewer;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class PageController extends Controller
{
    public function getIndex(Request $request)
    {
        $periodTypes = [
            [
                'value' => 'date',
                'label' => 'Tanggal',
            ],
            [
                'value' => 'month',
                'label' => 'Bulan',
            ],
            [
                'value' => 'year',
                'label' => 'Tahun',
            ],
        ];

        $periodType = $request->get('period_type', 'date');
        $periodFrom = $request->get('period_from', Carbon::now()->subDays(6)->format('d/m/Y'));
        $periodTo = $request->get('period_to', Carbon::now()->format('d/m/Y'));
        $parsedFrom = '';
        $parsedTo = '';
        $periods = collect([]);
        $periodsArtikel = collect([]);

        switch ($periodType) {
            case 'date':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', $periodFrom);
                $parsedTo = Carbon::createFromFormat('d/m/Y', $periodTo);

                $periods = collect(CarbonPeriod::create($parsedFrom, $parsedTo)->toArray())
                            ->map(function($item) {
                                $getByDate = function($class, $amountColumn) use ($item) {
                                    return $class::whereDate('created_at', '=', $item->format('Y-m-d'))->sum($amountColumn) ?: 0;
                                };

                                $totalSupportersAmount = $getByDate(Supporter::class, 'money');
                                $totalDonationsAmount = $getByDate(Donation::class, 'amount');
                                $totalZakatsAmount = $getByDate(Zakat::class, 'amount');
                                $totalAmount = $totalSupportersAmount + $totalDonationsAmount + $totalZakatsAmount;

                                return (object) [
                                    'date' => $item->format('d/m/Y'),
                                    'amount' => $totalAmount,
                                ];
                            })
                            ->values();
                break;

            case 'month':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', "01/$periodFrom");
                $parsedTo = Carbon::createFromFormat('d/m/Y', "01/$periodTo");

                $periods = collect(CarbonPeriod::create($parsedFrom, $parsedTo)->toArray())
                            ->filter(function($item) {
                                return $item->day === 1;
                            })
                            ->map(function($item) {
                                $getByMonthYear = function($class, $amountColumn) use ($item) {
                                    return $class::whereMonth('created_at', '=', $item->month)
                                           ->whereYear('created_at', '=', $item->year)
                                           ->sum($amountColumn) ?: 0;
                                };

                                $totalSupportersAmount = $getByMonthYear(Supporter::class, 'money');
                                $totalDonationsAmount = $getByMonthYear(Donation::class, 'amount');
                                $totalZakatsAmount = $getByMonthYear(Zakat::class, 'amount');
                                $totalAmount = $totalSupportersAmount + $totalDonationsAmount + $totalZakatsAmount;

                                return (object) [
                                    'date' => $item->format('m/Y'),
                                    'amount' => $totalAmount,
                                ];
                            })
                            ->values();
                break;

            case 'year':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', "01/01/$periodFrom");
                $parsedTo = Carbon::createFromFormat('d/m/Y', "01/01/$periodTo");
                $diff = $parsedTo->diffInYears($parsedFrom);

                $periods = collect(range(0, $diff))
                            ->map(function($number) use ($parsedFrom) {
                                return $parsedFrom->year + $number;
                            })
                            ->map(function($year) {
                                $getByYear = function($class, $amountColumn) use ($year) {
                                    return $class::whereYear('created_at', '=', $year)->sum($amountColumn) ?: 0;
                                };

                                $totalSupportersAmount = $getByYear(Supporter::class, 'money');
                                $totalDonationsAmount = $getByYear(Donation::class, 'amount');
                                $totalZakatsAmount = $getByYear(Zakat::class, 'amount');
                                $totalAmount = $totalSupportersAmount + $totalDonationsAmount + $totalZakatsAmount;

                                return (object) [
                                    'date' => $year,
                                    'amount' => $totalAmount,
                                ];
                            })
                            ->values();
                break;
            
            default:
                break;
        }

        switch ($periodType) {
            case 'date':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', $periodFrom);
                $parsedTo = Carbon::createFromFormat('d/m/Y', $periodTo);

                $periodsArtikel = collect(CarbonPeriod::create($parsedFrom, $parsedTo)->toArray())
                            ->map(function($item) {
                                $getByDate = function($class) use ($item) {
                                    return $class::groupBy('blog_id')->whereDate('created_at', '=', $item->format('Y-m-d'))->sum('hit') ? : 0;
                                };

                                $totalHit= $getByDate(BlogViewer::class);

                                return (object) [
                                    'date' => $item->format('d/m/Y'),
                                    'data' => $totalHit,
                                ];
                            })
                            ->values();
                break;

            case 'month':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', "01/$periodFrom");
                $parsedTo = Carbon::createFromFormat('d/m/Y', "01/$periodTo");

                $periodsArtikel = collect(CarbonPeriod::create($parsedFrom, $parsedTo)->toArray())
                            ->filter(function($item) {
                                return $item->day === 1;
                            })
                            ->map(function($item) {
                                $getByMonthYear = function($class) use ($item) {
                                    return $class::groupBy('blog_id')->whereMonth('created_at', '=', $item->month)
                                           ->whereYear('created_at', '=', $item->year)
                                           ->selectRaw('*, sum(hit) as sumHit')->get();
                                };

                                $totalHit = $getByMonthYear(BlogViewer::class);

                                return (object) [
                                    'date' => $item->format('m/Y'),
                                    'data' => $totalHit,
                                ];
                            })
                            ->values();
                break;

            case 'year':
                $parsedFrom = Carbon::createFromFormat('d/m/Y', "01/01/$periodFrom");
                $parsedTo = Carbon::createFromFormat('d/m/Y', "01/01/$periodTo");
                $diff = $parsedTo->diffInYears($parsedFrom);

                $periodsArtikel = collect(range(0, $diff))
                            ->map(function($number) use ($parsedFrom) {
                                return $parsedFrom->year + $number;
                            })
                            ->map(function($year) {
                                $getByYear = function($class) use ($year) {
                                    return $class::groupBy('blog_id')->whereYear('created_at', '=', $year)->selectRaw('*, sum(hit) as sumHit')->get();
                                };

                                $totalHit = $getByYear(BlogViewer::class);

                                return (object) [
                                    'date' => $year,
                                    'data' => $totalHit,
                                ];
                            })
                            ->values();
                break;
            
            default:
                break;
        }

        $totalSupportersAmountSuccess = Supporter::success()->sum('money') ?: 0;
        $totalDonationsAmountSuccess = Donation::success()->sum('amount') ?: 0;  
        $totalZakatsAmountSuccess = Zakat::success()->sum('amount') ?: 0;

        $totalSupportersAmountFailed = Supporter::failed()->sum('money') ?: 0;
        $totalDonationsAmountFailed = Donation::failed()->sum('amount') ?: 0;
        $totalZakatsAmountFailed = Zakat::failed()->sum('amount') ?: 0;

        $autoPaymentKeys = ['permata_va', 'echannel', 'other_va'];
        $countPayAutoSupporters = Supporter::whereIn('payment_method', $autoPaymentKeys)->count();
        $countPayAutoDonations = Donation::whereIn('payment_method', $autoPaymentKeys)->count();
        $countPayAutoZakats = Zakat::whereIn('payment_method', $autoPaymentKeys)->count();
        $countPayAuto = $countPayAutoSupporters + $countPayAutoDonations + $countPayAutoZakats;

        $eWalletPaymentKeys = ['gopay'];
        $countPayEWalletSupporters = Supporter::whereIn('payment_method', $eWalletPaymentKeys)->count();
        $countPayEWalletDonations = Donation::whereIn('payment_method', $eWalletPaymentKeys)->count();
        $countPayEWalletZakats = Zakat::whereIn('payment_method', $eWalletPaymentKeys)->count();
        $countPayEWallet = $countPayEWalletSupporters + $countPayEWalletDonations + $countPayEWalletZakats;

        $paymentMethod = PaymentMethod::where('group_id', 3)->get()->toArray(); //Manual Payment
        $manualPayments = collect($paymentMethod)->map(function($item, $key) {
                            $formattedKey = $item['code'];
                            $countPaySupporters = Supporter::where('payment_method', $formattedKey)->count();
                            $countPayDonations = Donation::where('payment_method', $formattedKey)->count();
                            $countPayZakats = Zakat::where('payment_method', $formattedKey)->count();
                            $countPay = $countPaySupporters + $countPayDonations + $countPayZakats;

                            return (object) [
                                'bank_name' => $item['name'],
                                'count' => $countPay,
                                'percent' => 0,
                            ];
                        });

        $countPayManual = $manualPayments->sum('count');
        $countPay = $countPayAuto + $countPayManual + $countPayEWallet;

        $manualPayments = $manualPayments
                        ->map(function($item) use ($countPay) {
                            $isValid = $item->count > 0 || $countPay > 0;
                            $item->percent = $isValid ? $item->count * 100 / $countPay : 0;

                            return $item;
                        })
                        ->values()
                        ->all();

        $totalAmountSuccess = $totalSupportersAmountSuccess + $totalDonationsAmountSuccess + $totalZakatsAmountSuccess;

        $countTransactionsSuccess = array_sum([
            Supporter::success()->count(),
            Donation::success()->count(),
            Zakat::success()->count()
        ]);

        $countTransactionsFailed = array_sum([
            Supporter::failed()->count(),
            Donation::failed()->count(),
            Zakat::failed()->count()
        ]);

        $countTransactions = $countTransactionsSuccess + $countTransactionsFailed;

        $user = auth()->user();
        $allDashboardPrivileges = $user->dashboardPrivileges;
        $dashboardPrivileges = $allDashboardPrivileges->where('can_access', 1);

        // Superadmins must always see the dashboard. Older records may contain
        // privilege rows with can_access = 0 from an incomplete setup.
        if ((int) $user->is_superadmin === 1) {
            $isChartAreaAccessible = true;
            $isChartAkadAccessible = true;
            $isChartMethodAccessible = true;
            $isChartTotalAccessible = true;
        } else {
            $isChartAreaAccessible = $dashboardPrivileges->where('dashboard_item_id', 1)->count() > 0;
            $isChartAkadAccessible = $dashboardPrivileges->where('dashboard_item_id', 2)->count() > 0;
            $isChartMethodAccessible = $dashboardPrivileges->where('dashboard_item_id', 3)->count() > 0;
            $isChartTotalAccessible = $dashboardPrivileges->where('dashboard_item_id', 4)->count() > 0;
        }
        // var_dump($periodsArtikel).die();
        $data = [
            'title' => 'Dashboard',
            'periodTypes' => $periodTypes,
            'periods' => $periods,
            'blogViewer' => BlogViewer::whereBetween('created_at', array($periodFrom, $periodTo))->groupBy('blog_id')->get(),
            'periodsArtikel' => $periodsArtikel,
            'totalSupportersAmountSuccess' => $totalSupportersAmountSuccess,
            'totalDonationsAmountSuccess' => $totalDonationsAmountSuccess,
            'totalZakatsAmountSuccess' => $totalZakatsAmountSuccess,
            'totalSupportersAmountFailed' => $totalSupportersAmountFailed,
            'totalDonationsAmountFailed' => $totalDonationsAmountFailed,
            'totalZakatsAmountFailed' => $totalZakatsAmountFailed,
            'totalAmountSuccess' => $totalAmountSuccess,
            'countTransactionsSuccess' => $countTransactionsSuccess,
            'countTransactionsFailed' => $countTransactionsFailed,
            'countTransactions' => $countTransactions,
            'countPayAuto' => $countPayAuto,
            'countPayManual' => $countPayManual,
            'countPayEWallet' => $countPayEWallet,
            'countPay' => $countPay,
            'manualPayments' => $manualPayments,
            'isChartAreaAccessible' => $isChartAreaAccessible,
            'isChartAkadAccessible' => $isChartAkadAccessible,
            'isChartMethodAccessible' => $isChartMethodAccessible,
            'isChartTotalAccessible' => $isChartTotalAccessible,
        ];
        
    	return view('admin::contents.page.index', $data);
    }

    public function getCustomPage()
    {
    	$data = [
    		'title' => 'Konten Halaman',
    		'option' => app('OptionData')->getAll(),
    	];
    	return view('admin::contents.page.custom_page', $data);
    }

    public function putCustomPage(Request $request)
    {
    	$input = $request->except(['_method', '_token']);

    	if (!empty($input)) {
	    	foreach ($input as $key => $value) {
	    		app('OptionData')->set($key, $value);
	    	}
    	}

    	return redirectMessage(
            route('admin.page.getCustomPage'),
            'Konten Halaman berhasil disimpan !!',
            '',
            'success'
        );
    }

    public function getSetting()
    {
    	$data = [
    		'title' => 'Setting',
    		'option' => app('OptionData')->getAll(),
    	];
    	return view('admin::contents.page.setting', $data);
    }

    public function putSetting(Request $request)
    {
    	$input = $request->except(['_method', '_token']);

    	if (!empty($input)) {
	    	foreach ($input as $key => $value) {
	    		app('OptionData')->set($key, $value);
	    	}
    	}

    	return redirectMessage(
            route('admin.page.getSetting'),
            'Setting Successfully Updated !!',
            '',
            'success'
        );
    }

    public function getCategories() 
    {
        $data = [
            'category' => Category::all(),
        ];
        return view('admin::contents.project.categories', $data);
    }

    public function postCategories(Request $request){ 
        $this->validate($request, [
            'category' => 'required',
        ]);
        
        $cat = new Category;
        $cat->category_name = $request['category'];
        $cat->save();

        return redirectMessage(
            route('admin.page.getCategories'),
            $cat->category_name . ' successfully Added !!',
            '',
            'success'
        );
    }

    public function deleteCategories($id){ 
        $category = Category::find($id);
        $category->delete();
        
        return redirectMessage(
            route('admin.page.getCategories'),
            $category->category_name . ' successfully Deleted !!',
            '',
            'success'
        );
    }


}
