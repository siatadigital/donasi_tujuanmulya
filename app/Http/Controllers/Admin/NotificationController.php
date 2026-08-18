<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getIndexNotifWa()
    {
        $data = [
            'title' => 'Konten Notifikasi WhatsApp',
            'notif' => Option::where('key', 'notif_wa')->get(),
        ];
        return view('admin::contents.notif.index-wa', $data);
    }

    public function getEditNotifWa($id)
    {
        $data = [
            'title' => 'Edit Konten Notifikasi WhatsApp',
            'notif' => app('App\Models\Option')->where('key','notif_wa')->where('id', $id)->first(),
        ];
        return view('admin::contents.notif.edit-wa', $data);
    }

    public function postEditNotifWa($id, Request $request){ 
        
        Option::where('id',$id)->update([
            'value'=> $request->value
        ]);

        return redirectMessage(
            route('admin.notif.getIndexNotifWa'),
            'Notification successfully Updated !!',
            '',
            'success'
        );
    }

    public function getIndexNotifEmail()
    {
        $data = [
            'title' => 'Konten Notifikasi Email',
            'notif' => Option::where('key', 'notif_email')->get(),
        ];
        return view('admin::contents.notif.index-email', $data);
    }

    public function getEditNotifEmail($id)
    {
        $data = [
            'title' => 'Edit Konten Notifikasi Email',
            'notif' => app('App\Models\Option')->where('key','notif_email')->where('id', $id)->first(),
        ];
        return view('admin::contents.notif.edit-email', $data);
    }

    public function postEditNotifEmail($id, Request $request){ 
        
        Option::where('id',$id)->update([
            'value'=> $request->value
        ]);

        return redirectMessage(
            route('admin.notif.getIndexNotifEmail'),
            'Notification successfully Updated !!',
            '',
            'success'
        );
    }

    public function getIndexPopupDoaZakat()
    {
        $data = [
            'title' => 'Konten Popup Doa Zakat',
            'notif' => Option::where('key', 'doa_zakat')->get(),
        ];
        return view('admin::contents.notif.index-doa-zakat', $data);
    }

    public function getEditPopupDoaZakat($id)
    {
        $data = [
            'title' => 'Edit Konten Popup Doa Zakat',
            'notif' => app('App\Models\Option')->where('key','doa_zakat')->where('id', $id)->first(),
        ];
        return view('admin::contents.notif.edit-doa-zakat', $data);
    }

    public function postEditPopupDoaZakat($id, Request $request){ 
        
        Option::where('id',$id)->update([
            'value'=> $request->value
        ]);

        return redirectMessage(
            route('admin.popup.getIndexPopupDoaZakat'),
            'Popup successfully Updated !!',
            '',
            'success'
        );
    }


    public function getIndexPopupTransaksi()
    {
        $data = [
            'title' => 'Konten Popup Transaksi',
            'notif' => Option::where('key', 'transaksi')->get(),
        ];
        return view('admin::contents.notif.index-transaksi', $data);
    }

    public function getEditPopupTransaksi($id)
    {
        $data = [
            'title' => 'Edit Konten Popup Transaksi',
            'notif' => app('App\Models\Option')->where('key','transaksi')->where('id', $id)->first(),
        ];
        return view('admin::contents.notif.edit-transaksi', $data);
    }

    public function postEditPopupTransaksi($id, Request $request){ 
        
        Option::where('id',$id)->update([
            'value'=> $request->value
        ]);

        return redirectMessage(
            route('admin.popup.getIndexPopupTransaksi'),
            'Popup successfully Updated !!',
            '',
            'success'
        );
    }

}
