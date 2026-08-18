<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {

    }

    public function getIndex()
    {
        $data = [
            'title' => 'All Event',
            'events' => Event::orderBy('created_at', 'desc')->paginate(20),
        ];
        return view('admin::contents.events.index', $data);
    }

    public function getIndexUser($eventID)
    {
        $data = [
            'events_users' => EventUser::where('event_id', $eventID)->get(),
        ];
        return view('admin::contents.events.participants',$data);
    }

    public function getEdit($id)
    {
        $data = [
            'title' => 'Edit Event Post',
            'blog' => app('App\Models\Event')->where('status', 'publish')->where('id', $id)->first(),
        ];
        return view('admin::contents.events.edit', $data);
    }

}
