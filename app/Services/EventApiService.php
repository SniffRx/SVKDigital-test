<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Tag(name="Event API", description="Операции, связанные с мероприятиями")
 */
class EventApiService
{
    protected string $baseUrl = 'https://leadbook.ru/test-task-api';
    protected string $authToken = 'pmN3TQFQalcOhCwZc18KcPMWZyG2EQHz8al9sCYw';

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->authToken,
            'Accept' => 'application/json',
        ];
    }

    /**
     * @throws ConnectionException
     */
    public function getShows()
    {
        $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/shows");
        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function getEventsForShow(int $showId)
    {
        $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/shows/{$showId}/events");
        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function getPlacesForEvent(int $eventId)
    {
        $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/events/{$eventId}/places");
        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function reservePlacesForEvent(int $eventId, array $data)
    {
        $response = Http::withHeaders($this->getHeaders())->post("{$this->baseUrl}/events/{$eventId}/reserve", $data);
        return $response->json();
    }
}
