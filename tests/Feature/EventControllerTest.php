<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EventControllerTest extends TestCase
{

    public function testGetEvents()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/shows' => Http::response(['shows' => []]),
        ]);

        $response = $this->get('/api/events');

        $response->assertStatus(200);
        $response->assertJsonStructure(['shows']);
    }

    public function testGetEventsForShow()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/shows/1/events' => Http::response(['events' => []]),
        ]);

        $response = $this->get('/api/events/1');

        $response->assertStatus(200);
        $response->assertJsonStructure(['events']);
    }

    public function testGetPlaces()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/events/1/places' => Http::response(['places' => []]),
        ]);

        $response = $this->get('/api/events/1/places');

        $response->assertStatus(200);
        $response->assertJsonStructure(['places']);
    }

    public function testReservePlaces()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/events/1/reserve' => Http::response(['status' => 'success']),
        ]);

        $response = $this->post('/api/events/1/reserve', [
            'name' => 'John Doe',
            'places' => ['A1', 'A2'],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }
}
