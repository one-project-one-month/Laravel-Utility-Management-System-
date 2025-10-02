<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\InvoiceResource;

class InvoiceController extends Controller
{
    use ApiResponse;

    // Display a listing of invoice
    public function index()
    {
        $invoices= Invoice::with(['bill'])->paginate(10);

        return $this->successResponse(
            'Invoices retrieved successfully',
            InvoiceResource::collection($invoices),
            200
        );
    }

    // Display a specific invoice
    public function show(String $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return $this->errorResponse(
                'Invoice not found',
                404
            );
        }

        return $this->successResponse(
            'Invoice retrieved successfully',
            new InvoiceResource($invoice),
            200
        );
    }

    // Update invoice
    public function update(Request $request, int $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return $this->errorResponse('Invoice not found', 404);
        }

        $validated = $request->validate([
            'bill_id' => 'sometimes|exists:bills,id',
            'status' => 'sometimes|string|in:Pending,Paid,Overdue',
        ]);

        $invoice->update($validated);

        return $this->successResponse(
            'Invoice updated successfully',
            new InvoiceResource($invoice),
            200
        );
    }

    // Delete invoice
    public function destroy(int $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return $this->errorResponse('Invoice not found', 404);
        }

        $invoice->delete();

        return $this->successResponse('Invoice deleted successfully', null, 200);
    }
}
