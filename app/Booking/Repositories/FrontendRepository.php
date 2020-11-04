<?php

namespace App\Booking\Repositories;

use App\Article;
use App\City;
use App\Comment;
use App\Reservation;
use App\Room;
use App\TouristObject;
use App\User;
use App\Booking\Interfaces\FrontendRepositoryInterface;

class FrontendRepository implements FrontendRepositoryInterface
{

    public function getObjectsForMainPage()
    {
        return TouristObject::with(['city', 'photos'])->ordered()->paginate(8);
    }

    public function getObject($id)
    {
        return TouristObject::with(['city', 'photos', 'address', 'users.photos', 'rooms.photos', 'comments.user', 'articles.user', 'rooms.object.city'])->find($id);
    }

    public function getSearchCities(string $term)
    {
        return City::where('name', 'LIKE', $term . '%')->get();
    }

    public function cities()
    {
        return City::all();
    }

    public function getSearchResults(string $city)
    {
        return City::with(['rooms.reservations', 'rooms.photos', 'rooms.object.photos'])->where('name', $city)->first() ?? false;
    }

    public function getRoom($id)
    {
        return Room::with(['object.address'])->find($id);
    }

    public function getReservationsByRoomId($room_id)
    {
        return Reservation::where('room_id', $room_id)->get();
    }

    public function getArticle($id)
    {
        return Article::with(['object.photos', 'comments'])->find($id);
    }

    public function getPerson($id)
    {
        return User::with(['objects', 'articles', 'comments.commentable'])->find($id);
    }

    public function like($likeable_id, $type, $request)
    {
        $type='App\\'.$type;
        $likeable = $type::find($likeable_id);
        return $likeable->users()->attach($request->user()->id);
    }

    public function unlike($likeable_id, $type, $request)
    {
        $type='App\\'.$type;
        $likeable = $type::find($likeable_id);
        return $likeable->users()->detach($request->user()->id);
    }

    public function addComment($commentable_id, $type, $request)
    {
        $type='App\\'.$type;
        $commentable = $type::find($commentable_id);
        $comment = new Comment;
        $comment->content = $request->input('content');
        $comment->rating = $type == 'App\TouristObject' ? $request->input('rating') : 0;
        $comment->user_id = $request->user()->id;
        return $commentable->comments()->save($comment);
    }

    public function makeReservation($room_id, $city_id, $request)
    {
        return Reservation::create([
            'user_id' => $request->user()->id,
            'city_id' => $city_id,
            'room_id' => $room_id,
            'status' => 0,
            'day_in' => date('Y-m-d', strtotime($request->input('checkin'))),
            'day_out' => date('Y-m-d', strtotime($request->input('checkout')))
        ]);
    }
}
