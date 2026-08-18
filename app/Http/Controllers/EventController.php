<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Event\EventRepository;
use Illuminate\Http\Request;
use App\Models\EventUser;

class EventController extends Controller
{
	protected $event;

	public function __construct(EventRepository $event)
	{
		return $this->event = $event;
	}

    public function getIndex()
    {
        $data = [
            'events' => $this->event->getPaginate(9),
        ];
        return view('contents.event.index',$data);
    }

    public function getShow($slug)
    {
        // return view('contents.event.show');
        $event = $this->event->findBySlug($slug);
        $data = [
            'title' => $event->title,
			'event' => $event,
            'related' => $this->event->related($slug),
        ];
        return view('contents.event.show',$data);
    }

    public function getCreate()
    {
		if(auth()->user()->is_superadmin != 1) {
			return redirect('/');
		}

    	return view('contents.event.create');
    }

    public function postCreate(Request $request)
    {
		if(auth()->user()->is_superadmin != 1) {
			return redirect('/');
		}

        // return $request->all();
    	$event = $this->event->create($request->all());
    	return redirect()->route('event.getShow', $event['slug']);
    }

    public function getEdit($slug)
    {
		if(auth()->user()->is_superadmin != 1) {
			return redirect('/');
		}

		$event = $this->event->findBySlug($slug);
		$data = [
			'title' => $event->title,
			'event' => $event,
		];
		return view('contents.event.edit',$data);
    }

    public function putEdit(Request $request, $slug)
    {
		if(auth()->user()->is_superadmin != 1) {
			return redirect('/');
		}

		$event = $this->event->findBySlug($slug);
		$event = $this->event->update($event, $request->all());
		return redirect()->route('admin.events.getIndex');
    }

    public function destroy($slug)
    {
		if(auth()->user()->is_superadmin != 1) {
			return redirect('/');
		}

		$event = $this->event->findBySlug($slug);
		$event = $this->event->delete($event->id);
		return redirect()->route('admin.events.getIndex');
    }

    public function registration(Request $request)
    {
    	$eventUser = EventUser::create($request->all());
    	return redirect()->back();
    }
}
