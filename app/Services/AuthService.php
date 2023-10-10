<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Btx\Http\Response;

class AuthService extends Service {

    private $_passwordPattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/';
    // private $_PhoneNumberPattern = '/^([0-9\s\-\+\(\)]*)$/';
    // private $_emailPattern = '/(.+)@(.+)\.(.+)/i';

    public function login(Request $req) {
        $validated = Validator::make($req->all(), [
            'username' => 'required|string',
            'password' => "required|string:30|regex:{$this->_passwordPattern}"
        ],[
            'required' => ':attribute cannot be null',
            'string' => ':attribute must be string'
        ]);
        if ($validated->fails()) {
            return Response::badRequest($validated->errors()->first());
        }

        $user = User::where('username', $req->username)->first();
        if(empty($user)) return Response::badRequest('user not found');
        if(isset($user->is_active)){
            if(!$user->is_active) return Response::badRequest('Your account has been disabled');
        }
        if ($user) {

            if (Hash::check($req->password, $user->password)) {
                $token = auth()->login($user);
                $data = $this->_responseWithToken($token);
                return Response::ok('Token generated', $data);
            }
        }

        return Response::badRequest(false, 'Username or password not valid');
    }


    private function _responseWithToken($token) {
        $ttl = auth()->factory()->getTTL() * 60;
        $user = auth()->user();
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl,
            'user' => $user
        ];
    }

}