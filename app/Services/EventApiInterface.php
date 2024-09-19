<?php

namespace App\Services;

interface EventApiInterface
{
    public function getShows();
    public function getEventsForShow($showId);
    public function getPlacesForEvent($eventId);
    public function reservePlacesForEvent($eventId, $data);
}
