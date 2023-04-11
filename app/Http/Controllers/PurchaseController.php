<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PurchaseService;
use App\Libraries\ApiResponse;

class PurchaseController extends Controller {
    private $_purchase;

    public function __construct(
        PurchaseService $purchase
    ) {
        $this->_purchase = $purchase;
    }

    public function list(Request $request){
        $result = $this->_purchase->list($request);
        return response()->json($result, $result['code']);
    }
    public function create(Request $request){
        $result = $this->_purchase->create($request);
        return response()->json($result, $result['code']);
    }

}