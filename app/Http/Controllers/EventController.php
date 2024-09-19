<?php

namespace App\Http\Controllers;

use App\Services\EventApiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="Booking System API", version="1.0")
 * @OA\Tag(name="Event API", description="Операции, связанные с мероприятиями")
 */
class EventController extends Controller
{
    protected EventApiService $eventApi;

    public function __construct(EventApiService $eventApiService)
    {
        $this->eventApi = $eventApiService;
    }

    /**
     * @OA\Get(
     *     path="/api/events",
     *     tags={"Event API"},
     *     summary="Получить список событий",
     *     description="Получите список всех мероприятий.",
     *     @OA\Response(
     *         response="200",
     *         description="Список мероприятий",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="response",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Show #1")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->eventApi->getShows());
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     tags={"Event API"},
     *     summary="Получите событие для конкретного мероприятия",
     *     description="Получите список событий для конкретного мероприятия по его идентификатору.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Идентификатор мероприятия",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Список мероприятий",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="response",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=46),
     *                     @OA\Property(property="showId", type="integer", example=10),
     *                     @OA\Property(property="date", type="string", example="2019-08-22 20:26:38")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Мероприятий не найдено"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     )
     * )
     */
    public function getEventsForShow(int $id): JsonResponse
    {
        return response()->json($this->eventApi->getEventsForShow($id));
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}/places",
     *     tags={"Event API"},
     *     summary="Получите места для конкретного мероприятия",
     *     description="Получите доступные места для конкретного мероприятия по его идентификатору.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Идентификатор мероприятия",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Список доступных мест для проведения мероприятия",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="response",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="x", type="number", format="float", example=0.0),
     *                     @OA\Property(property="y", type="number", format="float", example=0.0),
     *                     @OA\Property(property="width", type="number", format="float", example=20.0),
     *                     @OA\Property(property="height", type="number", format="float", example=20.0),
     *                     @OA\Property(property="is_available", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Мероприятие не найдено или места недоступны"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     )
     * )
     */
    public function getPlaces(int $id): JsonResponse
    {
        return response()->json($this->eventApi->getPlacesForEvent($id));
    }

    /**
     * @OA\Post(
     *     path="/api/events/{id}/reserve",
     *     tags={"Event API"},
     *     summary="Забронируйте места для мероприятия",
     *     description="Забронируйте места для конкретного мероприятия по его идентификатору.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Идентификатор мероприятия",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="places",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Подтверждение бронирования",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="response", type="object"),
     *             @OA\Property(property="response.success", type="boolean", example=true),
     *             @OA\Property(property="response.reservation_id", type="string", example="5d519fe58e3cf")
     *         )
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Неверный запрос на бронирование"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Событие не найдено"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Server error"
     *     )
     * )
     * @throws ConnectionException
     */
    public function reserve(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'places' => 'required|array',
        ]);

        return response()->json($this->eventApi->reservePlacesForEvent($id, $validated));
    }
}
