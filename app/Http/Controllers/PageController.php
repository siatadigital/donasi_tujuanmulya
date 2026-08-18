<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;
use App\Models\Project;
use App\Models\Zakat;
use App\Models\Supporter;
use App\Models\Banner;
use App\Models\Option;
use App\Models\PaymentMethodGroup;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function getIndex(Request $request)
    {
        if ($request->has('r')) {
            $code = $request->r;
            $me = auth()->user();

            $isCodeReferralValid = User::where('is_internal', TRUE)
                ->whereRaw("BINARY code_referral = '$code'")
                ->exists();

            if ($isCodeReferralValid) {
                if ($me) {
                    $isMyCode = $me->code_referral === $code;

                    if ($isMyCode) {
                        return redirect()->route('page.getIndex');
                    }
                }
            } else {
                return redirect()->route('page.getIndex');
            }
        }

        $data = [
            'title' => 'Homepage',
            'banners' => Banner::all(),
            'banner_modal_popup' => Banner::where('is_modal_popup', 1)->first(),
            'supporterTerakhirList' => Supporter::where('status', 'accept')->orderBy('id', 'DESC')->take(20)->get(),
            'donaturTerakhirList' => Donation::where('status', 'success')->orderBy('id', 'DESC')->take(20)->get(),
            'zakatTerakhirList' => Zakat::where('status', 'success')->orderBy('id', 'DESC')->take(20)->get(),
            'donation_total_amount' => Donation::where('status', 'success')->sum('amount'),
            'supporter_total_amount' => Supporter::where('status', 'accept')->sum('money'),
            'project_total' => Zakat::with('user')
                ->success()
                ->sum('amount'),
            'project_featured' => app('ProjectRepository')->getFeatured(4),
            'projects' => app('ProjectRepository')->getActive(6),
            'blogs' => app('BlogRepository')->getLatest(6),
            'payment_group' => PaymentMethodGroup::where('is_active', 1)->get(),
            'transaksi_city_input' => Option::where('type', 'string')->where('key', 'transaksi_city_input')->first()->value,
        ];
        return view('contents.page.index', $data);
    }

    public function getAkun()
    {
        return view('contents.page.akun');
    }

    public function getAbout()
    {
        return view('contents.page.about');
    }

    public function getSyarat()
    {
        $data['content'] = Option::where('type', 'page')->where('key', 'syarat_ketentuan')->first()->value;
        return view('contents.page.syarat', $data);
    }

    public function getBantuan()
    {
        $data['content'] = Option::where('type', 'page')->where('key', 'bantuan')->first()->value;
        return view('contents.page.bantuan', $data);
    }

    public function getTentang()
    {
        $data['content'] = Option::where('type', 'page')->where('key', 'tentang')->first()->value;
        return view('contents.page.tentang', $data);
    }

    public function getFaq()
    {
        return view('contents.page.faq');
    }

    public function getKebijakan()
    {
        return view('contents.page.kebijakan');
    }

    public function getPeraturan()
    {
        return view('contents.page.peraturan');
    }

    public function getTutorial()
    {
        return view('contents.page.tutorial');
    }

    public function getVisiMisi()
    {
        return view('contents.page.visi_misi');
    }

    public function getResiko()
    {
        return view('contents.page.resiko');
    }
}
