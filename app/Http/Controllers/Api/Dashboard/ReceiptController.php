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

class ReceiptController extends Controller
{
    use ApiResponse;


    public function index(){
          $receipts = Receipt::paginate(15);
         return $this->successResponse(content: ReceiptResource::collection($receipts));
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
        return $this->successResponse('A new Receipt created successfully!', content: new ReceiptResource($newReceipt) , status: 201);

    }

    public function show($id){
        $receipt = Receipt::find($id);
        if (!$receipt) {
            return $this->errorResponse(
                message: 'The receipt you are looking for does not exist!',
                status: 404
            );
        }
        return $this->successResponse("Receipt found successful" ,
            new ReceiptResource($receipt),
            200
        );
    }
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
        $receipt = Receipt::find($id);
        if (!$receipt) {
            return $this->errorResponse(
                message: 'The receipt you are looking for does not exist!',
                status: 404
            );
        }
        $receipt->update($validatedData);
        return $this->successResponse(
            'Receipt updated successfully!',
            new ReceiptResource($receipt),
            200
        );
    }
}
