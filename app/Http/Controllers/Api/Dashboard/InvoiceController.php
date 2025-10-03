<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Helpers\ApiResponse;
use App\Http\Resources\Api\Dashboard\InvoiceResource;

class InvoiceController extends Controller
{
    use ApiResponse;

    // Display a listing of invoice
    public function index()
    {
        $invoices= Invoice::with(['bill'])
            ->orderBy('invoices.created_at', 'desc')
            ->orderBy('invoices.id', 'desc')
            ->paginate(config('pagination.perPage'));

        if ($invoices->isEmpty()) {
            return $this->errorResponse('Invoices not found', 404);
        }

        return $this->successResponse(
            'Invoices retrieved successfully',
            $this->buildPaginatedResourceResponse(InvoiceResource::class, $invoices)
        );
    }

    // Display a specific invoice
    public function show(String $id)
    {
        $invoice = Invoice::with(['bill'])->find($id);

        if (!$invoice) {
            return $this->errorResponse(
                'Invoice not found',
                404
            );
        }

        return $this->successResponse(
            'Invoice retrieved successfully',
            new InvoiceResource($invoice)
        );
    }

    // Update invoice
    public function update(Request $request, int $id)
    {
        try{
            $invoice = Invoice::find($id);

            if (!$invoice) {
                return $this->errorResponse('Invoice not found', 404);
            }

            $validated = $request->validate([
                'billId' => 'sometimes|exists:bills,id',
                'status' => 'sometimes|string|in:Pending,Paid,Overdue',
            ]);

            $invoice->update($validated);

            return $this->successResponse(
                'Invoice updated successfully',
                new InvoiceResource($invoice)
            );
        }catch (\Exception $e) {
            return $this->errorResponse('Internal Server Error '.$e->getMessage(), 500);
        }
    }

    // Delete invoice
    // public function destroy(int $id)
    // {
    //     $invoice = Invoice::find($id);

    //     if (!$invoice) {
    //         return $this->errorResponse('Invoice not found', 404);
    //     }

    //     $invoice->delete();

    //     return $this->successResponse('Invoice deleted successfully', null, 200);
    // }
}
