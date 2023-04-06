<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Libraries\ApiResponse;

class ProductController extends Controller {
    private $_service;

    public function __construct(
        ProductService $service
    ) {
        $this->_service = $service;
    }

    public function create(Request $request){
        $result = $this->_service->create($request);
        return response()->json($result, $result['code']);
    }
    public function edit(Request $request){
        $result = $this->_service->edit($request);
        return response()->json($result, $result['code']);
    }
    
}