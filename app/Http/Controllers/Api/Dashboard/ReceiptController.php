<?php

namespace App\Http\Controllers\Api\Dashboard;


use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Dashboard\ReceiptResource;
use App\Models\Receipt;
use App\Enums\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Helpers\ApiResponse;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 * name="Receipts",
 * description="API Endpoints for managing receipts"
 * )
 */
class ReceiptController extends Controller
{
    use ApiResponse;


     /**
     * @OA\Get(
     * path="/api/v1/receipts",
     * summary="Get a list of receipts",
     * description="Returns a paginated list of all receipts.",
     * tags={"Receipts"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="message", type="string", example="Receipts retrieved successfully"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/ReceiptResource")),
     * @OA\Property(property="pagination", type="object",
     * @OA\Property(property="total", type="integer", example=50),
     * @OA\Property(property="per_page", type="integer", example=15),
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=4)
     * )
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Receipts not found"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(){
        $receipts = Receipt::paginate(config('pagination.perPage'));

        if ($receipts->isEmpty()) {
            return $this->errorResponse('Receipts not found', 404);
        }

        return $this->successResponse(
                'Receipts retrieved successfully',
                $this->buildPaginatedResourceResponse(ReceiptResource::class, $receipts),
        );
    }


    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'invoiceId' => 'required|exists:invoices,id',
            'paymentMethod' => ['required', Rule::enum(PaymentMethod::class)],
            'paidDate' => 'required|date'
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
        }

        $validatedData = $validator->validated();

        try {
        $invoice = Invoice::findOrFail($validatedData['invoiceId']);

        if ($invoice->status == 'Paid') {
            return $this->errorResponse('This invoice has already been paid.', 409);
        }

        $newReceipt = Receipt::create([
            'invoice_id' => $validatedData['invoiceId'],
            'payment_method' => $validatedData['paymentMethod'],
            'paid_date' => $validatedData['paidDate'],
        ]);

        $invoice->update(['status' => 'Paid']);

        return $this->successResponse(
                'A new Receipt created successfully!',
                content: new ReceiptResource($newReceipt),
                status: 201);
        } catch(\Exception $e) {
            return $this->errorResponse(
                    $e->getMessage(),
                    500);
        }
    }


      /**
     * @OA\Get(
     * path="/api/v1/receipts/{id}",
     * summary="Get a single receipt",
     * description="Returns the details of a specific receipt by its ID.",
     * tags={"Receipts"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the receipt",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Receipt found successful",
     * @OA\JsonContent(ref="#/components/schemas/ReceiptResource")
     * ),
     * @OA\Response(response=404, description="The receipt you are looking for does not exist!"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function show($id){
        $receipt = Receipt::find($id);

        if (!$receipt) {
            return $this->errorResponse(
                message: 'The receipt you are looking for does not exist!',
                status: 404
            );
        }

        return $this->successResponse("Receipt found successful",
            new ReceiptResource($receipt)
        );
    }


    /**
     * @OA\Put(
     * path="/api/v1/receipts/{id}",
     * summary="Update an existing receipt",
     * description="Updates the details of an existing receipt.",
     * tags={"Receipts"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the receipt to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"invoiceId", "paymentMethod", "paidDate"},
     * @OA\Property(property="invoiceId", type="string", format="uuid", example="9a7c6f2c-5b8a-4f2a-8f2c-6d8b3a0c1e9f"),
     * @OA\Property(property="paymentMethod", type="string", enum={"Cash", "Bank Transfer"}, example="Bank Transfer"),
     * @OA\Property(property="paidDate", type="string", format="date", example="2023-10-29")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Receipt updated successfully!",
     * @OA\JsonContent(ref="#/components/schemas/ReceiptResource")
     * ),
     * @OA\Response(response=422, description="Validation error"),
     * @OA\Response(response=404, description="The receipt you are looking for does not exist!"),
     * @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function update(Request $request , $id){
        $validator = Validator::make($request->all(),[
            'invoiceId' => 'required|exists:invoices,id',
            'paymentMethod' => ['required', Rule::enum(PaymentMethod::class)],
            'paidDate' => 'required|date'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $validatedData = $validator->validated();
        try {
        $receipt = Receipt::findOrFail($id);

        if (!$receipt) {
            return $this->errorResponse(
                    message: 'The receipt you are looking for does not exist!',
                    status: 404
            );
        }

        $receipt->update([
            'invoice_id'     => $validatedData['invoiceId'],
            'payment_method' => $validatedData['paymentMethod'],
            'paid_date'      => $validatedData['paidDate'],
        ]);

        return $this->successResponse(
            'Receipt updated successfully!',
            new ReceiptResource($receipt)
        );
        } catch(\Exception $e) {
            return $this->errorResponse(
                    $e->getMessage(),
                    500);
        }
    }
}
