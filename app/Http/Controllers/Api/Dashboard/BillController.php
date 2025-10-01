<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Jobs\GenerateBillsJob;
use App\Http\Resources\Api\Dashboard\BillResource;
use App\Models\Bill;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BillController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v1/bills
     *
     * @return JsonResponse
     */
    public function index()
    {
        $bills = Bill::with('totalUnit', 'invoice')->get();

        if ($bills->isEmpty()) {
            return $this->errorResponse('Not found!', 404);
        }

        return $this->successResponse('Bills retrieved successfully', BillResource::collection($bills));
    }

    /**
     * GET /api/v1/bills/{id}
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        $bill = Bill::whereId($id)->with('totalUnit', 'invoice')->first();

        if (! $bill) {
            return $this->errorResponse('Not found!', 404);
        }

        return $this->successResponse('Bill retrieved successfully', new BillResource($bill),
        );
    }

    /**
     * POST /api/v1/bills
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        GenerateBillsJob::dispatch();

        return $this->successResponse('Monthly bill created successfully. Auto-calculates units and generates invoice. Email feature coming next.', null ,201);
    }

    /**
     * PUT /api/v1/bills/{id}
     *
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $bill = Bill::find($id);

        if (!$bill) {
            return $this->errorResponse('Bill not found', 404);
        }

        $validator = Validator::make($request->all(),
            [
                'roomId'         => 'bail|sometimes|required|uuid|exists:rooms,id',
                'rentalFee'      => 'bail|sometimes|required|numeric|min:0|decimal:2',
                'electricityFee' => 'bail|sometimes|required|numeric|min:0|decimal:2',
                'waterFee'       => 'bail|sometimes|required|numeric|min:0|decimal:2',
                'fineFee'        => 'bail|nullable|numeric|min:0|decimal:2',
                'serviceFee'     => 'bail|sometimes|required|numeric|min:0|decimal:2',
                'groundFee'      => 'bail|sometimes|required|numeric|min:0|decimal:2',
                'carParkingFee'  => 'bail|nullable|numeric|min:0|decimal:2',
                'wifiFee'        => 'bail|nullable|numeric|min:0|decimal:2',
                'totalAmount'    => 'bail|sometimes|required|numeric|min:0|decimal:2',
                'dueDate'        => 'bail|sometimes|required|date',
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $validated = $validator->validated();

        // transform camel-case to snake-case for array key
        $validated = collect($validated)->keyBy(fn ($value, $key) => Str::snake($key))->all();

        if ($bill->update($validated)) {
            return $this->successResponse('Bill updated successfully', status: 201);
        }

        return $this->errorResponse('Failed to update bill', 422);
    }
}
