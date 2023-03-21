<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TestService;
use App\Libraries\ApiResponse;

class TestController extends Controller {
    private $_test;

    public function __construct(TestService $test) {
        $this->_test = $test;
    }

    public function respon(Request $req) {
        $result = $this->_test->respon($req);
        return response()->json($result, $result['code']);
    }

}