<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;

class BannerController extends Controller
{
    public function __construct()
    {

    }

    public function getBanner() 
    {
        $data = [
            'banner' => Banner::all(),
        ];
        return view('admin::contents.banner.index', $data);
    }

    public function postBanner(Request $request){ 
        $this->validate($request, [
            'link' => 'required',
            'photo' => 'required',
        ]);
        
        $banner = new Banner;
        $banner->link = $request['link'];
        $banner->photo = $request['photo'];
        $banner->save();

        return redirectMessage(
            route('admin.banner.getBanner'),
            'Banner successfully Added !!',
            '',
            'success'
        );
    }

    public function deleteBanner($id){ 
        $banner = Banner::find($id);
        $banner->delete();
        
        return redirectMessage(
            route('admin.banner.getBanner'),
            'Banner successfully Deleted !!',
            '',
            'success'
        );
    }

    public function setModalPopup($id)
    {
        // update all true to false
        Banner::where('is_modal_popup', TRUE)->update(['is_modal_popup' => FALSE]);

        // update specific id to true
        $banner = Banner::findOrFail($id);
        $banner->is_modal_popup = TRUE;
        $banner->save();

        return redirectMessage(
            'back',
            'Banner successfully change Modal Popup !!',
            '',
            'success'
        );
    }

    public function removeModalPopup($id)
    {
        // update all true to false
        Banner::where('is_modal_popup', TRUE)->update(['is_modal_popup' => FALSE]);

        // update specific id to true
        $banner = Banner::findOrFail($id);
        $banner->is_modal_popup = FALSE;
        $banner->save();

        return redirectMessage(
            'back',
            'Banner successfully change Modal Popup !!',
            '',
            'success'
        );
    }


}