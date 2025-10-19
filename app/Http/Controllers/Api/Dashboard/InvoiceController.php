<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Invoice;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Dashboard\InvoiceResource;

/**
 * @OA\Tag(
 * name="Invoices",
 * description="API Endpoints for managing invoices"
 * )
 */
class InvoiceController extends Controller
{
    use ApiResponse;


    /**
     * @OA\Get(
     * path="/api/v1/invoices",
     * summary="Get a list of invoices",
     * description="Returns a paginated list of all invoices.",
     * tags={"Invoices"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Invoices retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/InvoiceResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Invoices not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'billId'   => 'required',
            'status'   => 'required'
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
        }

        try
        {
            $validatedData = $validator->validated();

            $data = Invoice::create([
                'invoice_no' =>  $this->customInvoiceGenerator(),
                'bill_id'    => $validatedData['billId'],
                'status'     => $validatedData['status']
            ]);

            return $this->successResponse('Invoice created successfully!',new InvoiceResource($data),201);
        } catch (\Exception $e)
        {
            return $this->errorResponse($e->getMessage(),500);
        }
    }


     /**
     * @OA\Get(
     * path="/api/v1/invoices/{id}",
     * summary="Get a single invoice",
     * description="Returns the details of a specific invoice by its ID.",
     * tags={"Invoices"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the invoice",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Invoice retrieved successfully",
     * @OA\JsonContent(ref="#/components/schemas/InvoiceResource")
     * ),
     * @OA\Response(response=404, description="Invoice not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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




     /**
     * @OA\Put(
     * path="/api/v1/invoices/{id}",
     * summary="Update an existing invoice",
     * description="Updates the status or bill ID of an existing invoice.",
     * tags={"Invoices"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the invoice to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="billId", type="integer", description="ID of the associated bill", example=1),
     * @OA\Property(property="status", type="string", enum={"Pending", "Paid", "Overdue"}, example="Paid")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Invoice updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/InvoiceResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="Invoice not found"),
     * @OA\Response(response=500, description="Internal Server Error"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
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

    private function customInvoiceGenerator() {
        $customInvoice = "INV".'-'.fake()->randomNumber(8, true);
        return  $customInvoice;
    }
}
