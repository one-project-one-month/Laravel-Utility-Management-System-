<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TotalUnit;

class TotalUnitController extends Controller
{
    // index
    public function index(){
        $totalunits = TotalUnit::orderBy('created_at','desc')->get();
        return response()->json($totalunits,200);
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
        $totalunits = TotalUnit::findOrFail($id);
        return response()->json($totalunits, 200);
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
