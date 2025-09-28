<?php

namespace App\Http\Controllers\Api\Dashboard;


use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\User;
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
         return $this->successResponse(content: $receipts);


    //Create Receipt
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'payment_method' => ['required', Rule::enum(PaymentMethod::class)],
            'paid_date' => 'required|date'
        ]);
         if($validator->fails()) {
            return $this->errorResponse($validator->errors(),422);
        }
        $validatedData = $validator->validated();
        $invoice = Invoice::findOrFail($validatedData['invoice_id']);
        
        if ($invoice->status == 'Paid') {
            return $this->errorResponse('This invoice has already been paid.', 409);
        }

        Receipt::create([
            'invoice_id' => $validatedData['invoice_id'],
            'payment_method' => $validatedData['payment_method'],
            'paid_date' => $validatedData['paid_date'],
        ]);

        //update invoice status to 'Paid'
        $invoice->update(['status' => 'Paid']);
        return $this->successResponse('A new Receipt created successfully!', status: 201);

    }
}
