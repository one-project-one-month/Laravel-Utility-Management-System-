<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TotalUnit;

class TotalUnitController extends Controller
{
    // index
    public function index(){
        return response()->json(TotalUnit::with('bill')->get());
    }
    
    // create
    public function store(Request $request) {
        $data = $request->validate([
            'billId' => 'required|exists:bills,id',
            'electricityUnits' => 'required|integer',
            'waterUnits' => 'required|integer',
        ]);

        $unit = TotalUnit::create([
            'bill_id' => $data['billId'],
            'electricity_units' => $data['electricityUnits'],
            'water_units' => $data['waterUnits'],
        ]);

        return response()->json($unit, 201);
    }

    // (show) 
    public function show($id) {
        return response()->json(TotalUnit::with('bill')->findOrFail($id));
    }

    // (update)
    public function update(Request $request, $id) {
        $unit = TotalUnit::findOrFail($id);

        $data = $request->validate([
            'billId' => 'sometimes|exists:bills,id',
            'electricityUnits' => 'sometimes|integer',
            'waterUnits' => 'sometimes|integer',
            'createdAt' => 'sometimes|date'
        ]);

        $unit->update([
            'bill_id' => $data['billId'] ?? $unit->bill_id,
            'electricity_units' => $data['electricityUnits'] ?? $unit->electricity_units,
            'water_units' => $data['waterUnits'] ?? $unit->water_units,
            'created_at' => $data['createdAt'] ?? $unit->created_at
        ]);

        return response()->json($unit);
    }

}
