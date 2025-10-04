<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\TotalUnitResource;
use App\Models\TotalUnit;

class TotalUnitController extends Controller
{
    use ApiResponse;

    // index
    public function index(){
        $totalunits = TotalUnit::orderBy('created_at','desc')
            ->orderBy('id','desc')
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
