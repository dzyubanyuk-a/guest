<?php

namespace App\Http\Controllers\Api;

use App\DTO\GuestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGuestRequest;
use App\Http\Requests\GuestPaginationRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Http\Resources\GuestResource;
use App\Services\GuestService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\Info(title="API для ТЗ от компании Bnovo", version="0.1")
 */
class GuestController extends Controller
{
    public GuestService $guestService;

    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }

    /**
     * @OA\Get(
     *     path="/guests",
     *     summary="Получить список гостей с пагинацией",
     *     tags={"Гости"},
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=15),
     *         description="Количество элементов на странице (по умолчанию 15)"
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=1),
     *         description="Номер страницы (по умолчанию 1)"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Список гостей с метаданными пагинации",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/GuestResource")
     *             ),
     *
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Неверные параметры запроса",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Внутренняя ошибка сервера",
     *     )
     * )
     */
    public function index(GuestPaginationRequest $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $page = (int) $request->input('page', 1);

        $guests = $this->guestService->getPaginatedGuests($perPage, $page);

        return response()->json([
            'data' => GuestResource::collection($guests),
            'meta' => [
                'current_page' => $guests->currentPage(),
                'last_page' => $guests->lastPage(),
                'per_page' => $guests->perPage(),
                'total' => $guests->total(),
            ],
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/guests/{id}",
     *     summary="Получить информацию о госте по id",
     *     tags={"Гости"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer"),
     *         description="Id гостя"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Информация о госте",
     *
     *         @OA\JsonContent(ref="#/components/schemas/GuestResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Гость не найден",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Гость не найден")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Внутренняя ошибка сервера",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Произошла ошибка на сервере")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $guest = $this->guestService->getGuestById($id);

            return response()->json(GuestResource::make($guest), ResponseAlias::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                ['error' => __('exceptions.quest_not_found')], ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return response()->json(
                ['error' => __('exceptions.generic_error')], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/guests",
     *     summary="Создать нового гостя",
     *     tags={"Гости"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="first_name", type="string", example="Иван"),
     *             @OA\Property(property="last_name", type="string", example="Иванов"),
     *             @OA\Property(property="email", type="string", format="email", example="primer@mail.ru"),
     *             @OA\Property(property="phone", type="string", example="+78002000600"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Гость успешно создан",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка при создании гостя",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Не удалось создать гостя")
     *         )
     *     )
     * )
     */
    public function create(CreateGuestRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $guest = $this->guestService->createGuest(new GuestDTO(...$validatedData));

            return response()->json(null, ResponseAlias::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(
                ['error' => __('exceptions.guest_creation_failed')],
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/guests/{id}",
     *     summary="Обновить информацию о госте",
     *     tags={"Гости"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer"),
     *         description="Id гостя"
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="first_name", type="string", example="Иван"),
     *             @OA\Property(property="last_name", type="string", example="Иванов"),
     *             @OA\Property(property="email", type="string", format="email", example="primer@mail.ru"),
     *             @OA\Property(property="phone", type="string", example="+78002000600"),
     *             @OA\Property(property="country", type="string", example="RU"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Гость успешно обновлен",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Гость не найден",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Гость не найден")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка при обновлении гостя",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Не удалось обновить гостя")
     *         )
     *     )
     * )
     */
    public function update(UpdateGuestRequest $request, int $id): JsonResponse
    {
        try {
            $this->guestService->updateGuest($id, $request->validated());

            return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                ['error' => __('exceptions.quest_not_found')], ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return response()->json(['error' => __('exceptions.guest_update_failed')],
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/guests/{id}",
     *     summary="Удалить гостя",
     *     tags={"Гости"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *
     *         @OA\Schema(type="integer"),
     *         description="ID гостя"
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Гость успешно удален",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Гость не найден",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Гость не найден")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Ошибка при удалении гостя",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Не удалось удалить гостя")
     *         )
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->guestService->deleteGuest($id);

            return response()->json(null, ResponseAlias::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(
                ['error' => __('exceptions.quest_not_found')], ResponseAlias::HTTP_NOT_FOUND
            );
        } catch (Exception $e) {
            return response()->json(
                ['error' => __('exceptions.guest_delete_failed')], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
