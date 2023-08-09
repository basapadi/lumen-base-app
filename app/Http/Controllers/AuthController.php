<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Btx\Http\Response;

class AuthController extends Controller {
    private $_auth;

    public function __construct(
        AuthService $auth
    ) {
        $this->_auth = $auth;
    }

    public function login(Request $req) {
        $result = $this->_auth->login($req);
        return response()->json($result, $result['code']);
    }

    public function logout() {
        auth()->logout();
        $logout = Response::ok('Successfully logged out');
        return response()->json($logout);
    }

}