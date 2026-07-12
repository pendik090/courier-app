<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Courier\StoreCourierRequest;
use App\Http\Requests\Api\V1\Courier\UpdateCourierRequest;
use App\Http\Resources\Api\V1\CourierResource;
use App\Models\Courier;
use App\Services\CourierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourierController extends Controller
{
    public function __construct(
        private readonly CourierService $service
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $couriers = $this->service->buildIndexQuery(
            Courier::query(),
            $request->only(['search', 'level', 'sort'])
        )->paginate();

        return CourierResource::collection($couriers);
    }

    public function store(StoreCourierRequest $request): JsonResponse
    {
        $courier = Courier::create($request->validated());

        return (new CourierResource($courier))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Courier $courier): CourierResource
    {
        return new CourierResource($courier);
    }

    public function update(UpdateCourierRequest $request, Courier $courier): CourierResource
    {
        $courier->update($request->validated());

        return new CourierResource($courier);
    }

    public function destroy(Courier $courier): JsonResponse
    {
        $courier->delete();

        return response()->json(null, 204);
    }
}
