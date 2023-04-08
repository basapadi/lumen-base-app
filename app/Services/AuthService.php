<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\StaticResponseTrait;

class AuthService {

    use StaticResponseTrait;

    private $_passwordPattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/';
    private $_PhoneNumberPattern = '/^([0-9\s\-\+\(\)]*)$/';
    private $_emailPattern = '/(.+)@(.+)\.(.+)/i';

    public function register(Request $req) {
       
        $validated = Validator::make($req->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|string|unique:users',
            'password' => "required|string:30|regex:{$this->_passwordPattern}"
        ],[
            'required' => ':attribute cannot be null and Password must contain String and Number'
        ]);
        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }
        //dd($validated);
        $register =  new User;
        $register->name = $req->name;
        $register->username = $req->username;
        $register->password = Hash::make($req->password);
        $register->email = $req->email;
        
        if(!$register->save()){
            return $this->response400('Cannot Add New User');
        }
        return ApiResponse::make(true,'Data Inserted',$register);
    }

    public function login(Request $req) {
        $validated = Validator::make($req->all(), [
            'username' => 'required|string',
            'password' => "required|string:30|regex:{$this->_passwordPattern}"
        ]);
        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }

        $user = User::where('username', $req->username)->first();
       
        if(isset($user->is_active)){
            if(!$user->is_active) return $this->response400('Your account has been disabled');
        }
        if ($user) {

            if (Hash::check($req->password, $user->password)) {
                // dd($user);
                $token = auth()->login($user);
                $data = $this->_responseWithToken($token);
                return ApiResponse::make(true, 'Token generated', $data);
            }
        }

        return ApiResponse::make(false, 'Username or password not valid');
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