<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\TotalUnitResource;
use App\Models\TotalUnit;


/**
 * @OA\Tag(
 * name="Total Units",
 * description="API Endpoints for managing total utility units"
 * )
 */
class TotalUnitController extends Controller
{
    use ApiResponse;



    /**
     * @OA\Get(
     * path="/api/v1/total-units",
     * summary="Get a list of total units",
     * description="Returns a paginated list of all total units records.",
     * tags={"Total Units"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Total units retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/TotalUnitResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="No total units found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    // index
    public function index(){
        $totalunits = TotalUnit::with(['bill.tenant', 'bill.room'])
            // ->orderBy('created_at','desc')
            // ->orderBy('id','desc')
            ->paginate(config('pagination.perPage'));

        if ($totalunits->isEmpty()) {
            return $this->errorResponse('No total units found', 404);
        }

        return $this->successResponse(
            'Total units retrieved successfully',
            $this->buildPaginatedResourceResponse(TotalUnitResource::class, $totalunits),
        );
    }



    // create
    public function store(Request $request) {
        $data = $request->validate([
            'billId' => 'required|exists:bills,id',
            'electricity_units' => 'nullable|integer',
            'water_units' => 'nullable|integer',
        ]);

        $totalunits = new TotalUnit();
        $totalunits->bill_id = $request["billId"];
        $totalunits->electricity_units = is_null($request["electricity_units"]) ?  $totalunits->generatetotalunit(3) : $request["electricity_units"];
        $totalunits->water_units = is_null($request["water_units"]) ?  $totalunits->generatetotalunit(2) : $request["water_units"];
        $totalunits->save();

        return response()->json($totalunits, 200);
    }



/**
     * @OA\Get(
     * path="/api/v1/total-units/{id}",
     * summary="Get a single total unit record",
     * description="Returns the details of a specific total unit record by its ID.",
     * tags={"Total Units"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the total unit record",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Total unit retrieved successfully",
     * @OA\JsonContent(ref="#/components/schemas/TotalUnitResource")
     * ),
     * @OA\Response(response=404, description="Total unit not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    // (show)
    public function show($id) {
        $totalunits = TotalUnit::find($id);

        if (!$totalunits) {
            return $this->errorResponse('Total unit not found', 404);
        }

        return $this->successResponse('Total unit retrieved successfully', new TotalUnitResource($totalunits));
    }


    // (update)
    public function update(Request $request, $id) {

        $data = $request->validate([
            'billId' => 'required|exists:bills,id',
            'electricity_units' => 'nullable|integer',
            'water_units' => 'nullable|integer',
        ]);

        $totalunits = TotalUnit::findOrFail($id);
        $totalunits->bill_id = $request["billId"];
        $totalunits->electricity_units = is_null($request["electricity_units"]) ?  $totalunits->generatetotalunit(3) : $request["electricity_units"];
        $totalunits->water_units = is_null($request["water_units"]) ?  $totalunits->generatetotalunit(2) : $request["water_units"];
        $totalunits->save();

        return response()->json($totalunits, 200);

    }

}
