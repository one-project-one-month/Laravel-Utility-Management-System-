<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Bill;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Jobs\GenerateBillsJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\BillResource;


/**
 * @OA\Tag(
 * name="Bills",
 * description="API Endpoints for managing bills"
 * )
 */
class BillController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v1/bills
     *
     * @return JsonResponse
     */

    /**
     * @OA\Get(
     * path="/api/v1/bills",
     * summary="Get a list of bills",
     * description="Returns a paginated list of all bills.",
     * tags={"Bills"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Bills retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/BillResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Bills not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index()
    {
        $bills = Bill::with('totalUnit', 'invoice')
            ->orderBy('bills.created_at','desc')
            ->orderBy('bills.id','desc')
            ->paginate(config('pagination.perPage'));

        if ($bills->isEmpty()) {
            return $this->errorResponse('Bills not found', 404);
        }

        return $this->successResponse(
            'Bills retrieved successfully',
            $this->buildPaginatedResourceResponse(BillResource::class, $bills)
        );
    }

    /**
     * GET /api/v1/bills/{id}
     *
     * @return JsonResponse
     */


     /**
     * @OA\Get(
     * path="/api/v1/bills/{id}",
     * summary="Get a single bill",
     * description="Returns the details of a specific bill by its ID.",
     * tags={"Bills"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the bill",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Bill retrieved successfully",
     * @OA\JsonContent(ref="#/components/schemas/BillResource")
     * ),
     * @OA\Response(response=404, description="Bill not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
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


     /**
     * @OA\Post(
     * path="/api/v1/bills",
     * summary="Generate monthly bills",
     * description="Triggers a job to generate monthly bills for all applicable tenants.",
     * tags={"Bills"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=201,
     * description="Bill generation process started successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Monthly bill created successfully. Auto-calculates units and generates invoice. Email feature coming next.")
     * )
     * ),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
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
                'rentalFee'      => 'bail|sometimes|required|numeric|min:0',
                'electricityFee' => 'bail|sometimes|required|numeric|min:0',
                'waterFee'       => 'bail|sometimes|required|numeric|min:0',
                'fineFee'        => 'bail|nullable|numeric|min:0',
                'serviceFee'     => 'bail|sometimes|required|numeric|min:0',
                'groundFee'      => 'bail|sometimes|required|numeric|min:0',
                'carParkingFee'  => 'bail|nullable|numeric|min:0',
                'wifiFee'        => 'bail|nullable|numeric|min:0',
                'totalAmount'    => 'bail|sometimes|required|numeric|min:0',
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
            return $this->successResponse('Bill updated successfully',new BillResource($bill));
        }

        return $this->errorResponse('Failed to update bill', 422);
    }
}
