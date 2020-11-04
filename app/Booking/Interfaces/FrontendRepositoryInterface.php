<?php

namespace App\Booking\Interfaces;

interface FrontendRepositoryInterface {

    public function getObjectsForMainPage();

    public function getObject($id);

    public function getArticle($id);

    public function getPerson($id);

    public function getRoom($id);

    public function getReservationsByRoomId($id);

    public function like($likeable_id, $type, \Illuminate\Http\Request $request);

    public function unlike($likeable_id, $type, \Illuminate\Http\Request $request);
}
