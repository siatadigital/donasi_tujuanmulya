<?php

namespace App\Http\Controllers\Api;

use App\Models\Donation;
use App\Models\Supporter;
use App\Models\Zakat;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SyncController extends Controller
{
    public function donation(Request $request)
    {
        $this->validate($request,[
            'last_synced_id' => 'numeric'
        ]);

        $data = Donation::where('id','>',$request->last_synced_id)
            ->whereIn('status',['success','expired'])
            ->orderBy('id')
            ->limit($request->limit)
            ->get();

        return response()->json(['data' => $data]);
    }

    public function supporter(Request $request)
    {
        $this->validate($request,[
            'last_synced_id' => 'numeric'
        ]);

        $data = Supporter::where('id','>',$request->last_synced_id)
            ->whereIn('status',['accept','expired'])
            ->orderBy('id')
            ->limit($request->limit)
            ->get();

        return response()->json(['data' => $data]);
    }

    public function zakat(Request $request)
    {
        $this->validate($request,[
            'last_synced_id' => 'numeric'
        ]);

        $data = Zakat::where('id','>',$request->last_synced_id)
            ->whereIn('status',['success', 'expired'])
            ->orderBy('id')
            ->limit($request->limit)
            ->get();

        return response()->json(['data' => $data]);
    }
}
