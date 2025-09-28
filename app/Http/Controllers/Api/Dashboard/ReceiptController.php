<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    use ApiResponse;
    public function index(){
          $receipts = Receipt::paginate(15);
         return $this->successResponse(content: $receipts);
    }
}
