<?php

namespace App\Http\Controllers;

use App\Booking\Repositories\FrontendRepository;
use App\Booking\Interfaces\FrontendRepositoryInterface;
use App\Events\OrderPlacedEvent;
use Illuminate\Http\Request;
use App\Booking\Gateways\FrontendGateway;
use Illuminate\Support\Facades\Cache;

/**
 * @property FrontendRepositoryInterface fR
 * @property FrontendGateway fG
 */
class FrontendController extends Controller
{
    public function __construct(FrontendRepositoryInterface $frontendRepository, FrontendGateway $frontendGateway)
    {
        $this->middleware($this->setMiddleware());
        $this->middleware($this->setMiddleware())->only(['makeReservation', 'addComment', 'like', 'unlike']);

        $this->fR = $frontendRepository;
        $this->fG = $frontendGateway;
    }

    public function index(){

        $objects = $this->fR->getObjectsForMainPage();
        return $this->makeResponse('frontend.index', ['objects' => $objects]);
    }

    public function article($id){

        $article = $this->fR->getArticle($id);
        return $this->makeResponse('frontend.article', compact('article'));
    }

    public function object($id){

        $object = $this->fR->getObject($id);

        return $this->makeResponse('frontend.object', compact('object'));
    }

    public function person($id){

        $user = $this->fR->getPerson($id);
        return view('frontend.person', ['user' => $user]);
    }

    public function room($id){

        $room = $this->fR->getRoom($id);
        return $this->makeResponse('frontend.room', compact('room'));
    }

    public function ajaxGetRoomReservations($id)
    {
        $reservations = $this->fR->getReservationsByRoomId($id);
        return response()->json([
            'reservation' => $reservations
        ]);
    }

    public function roomsearch(Request $request)
    {

        if ($city = $this->fG->getSearchResults($request)) {

            //dd($city);
            return $this->makeResponse('frontend.roomsearch', compact('city'));
        } else {
            if (!$request->ajax())
                return redirect('/')->with('norooms', __('No offers were matching the criteria'));
        }
    }

    public function searchCities(Request $request)
    {
        $results = $this->fG->searchCities($request);
        return response()->json($results);
    }

    public function like($likeable_id, $type, Request $request)
    {
        $this->fR->like($likeable_id, $type, $request);

        Cache::flush();

        return redirect()->back();
    }

    public function cities()
    {
        $results = $this->fR->cities();
        return response()->json($results);
    }

    public function unlike($likeable_id, $type, Request $request)
    {
        $this->fR->unlike($likeable_id, $type, $request);

        Cache::flush();

        return redirect()->back();
    }

    public function addComment($commentable_id, $type, Request $request)
    {
        $this->fG->addComment($commentable_id, $type, $request);

        Cache::flush();

        return redirect()->back();
    }

    public function makeReservation($room_id, $city_id, Request $request)
    {
        $available = $this->fG->checkAvailableReservations($room_id, $request);

        if (!$available)
        {
            if(!$request->ajax())
            {
                $request->session()->flash('reservationMsg', __('There are no vacancies.'));
                return redirect()->route('room', ['id'=>$room_id, '#reservation']);
            }

            return response()->json(['reservation' => false]);
        }
        else
        {
            $reservation = $this->fG->makeReservation($room_id, $city_id, $request);

            event(new OrderPlacedEvent($reservation));

            if (!$request->ajax())
                return redirect()->route('adminHome');
                else
                return response()->json(['reservation'=>$reservation]);
        }
    }
}
