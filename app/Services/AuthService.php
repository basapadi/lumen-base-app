<?php
namespace App\Services;

use App\Models\{
    User,
    UserToken
};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Btx\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mails\Register;
use DateTime;

class AuthService extends Service {

    private $_passwordPattern = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])/';
    private $_expiredResetLink = 5; // in minute
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

    public function register(Request $req) {
        try {
            $validated = Validator::make($req->all(), [
                'username' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|string|unique:users',
                'adm_1' => 'required|string',
                'adm_2' => 'required|string',
                'zip_code' => 'required|string',
                'password' => "required|same:re_password|string:30|regex:{$this->_passwordPattern}",
                're_password' => "required|string|max:30|regex:{$this->_passwordPattern}",
            ],[
                'required' => ':attribute cannot be null',
                'string' => ':attribute must be string',
                'email.unique' => 'your :attribute has been used before, please try another :attribute',
                'password.regex' => 'The password must contain lowercase letters, uppercase letters and numbers!',
            ]);
            if ($validated->fails()) {
                return Response::badRequest($validated->errors()->first());
            }
            $preCreate = [
                'name'  => strtolower($req->name),
                'username'  => strtolower($req->username),
                'password'  => Hash::make($req->password),
                'email'     => strtolower($req->email),
                'adm_1'     => $req->adm_1,
                'adm_2'     => $req->adm_2,
                'zip_code'  => $req->zip_code,
                'verified' => false,
                'role'  => (int) config('config.user_roles')['public'],
                'level'  => (int) config('config.user_levels')['default'],
                'is_active' => true,
            ];
            $resourced = User::create($preCreate);
            if($resourced){
                $credential = $this->_createCredential('register');
                $resData['user'] = $resourced; 
                $resData['link'] = config('app.main_api_url') . '/auth/register/verify?email='. $credential->email.'&token='.$credential->token;
                Mail::to(strtolower(trim($req->email)))->send(new Register($resData));
                return Response::ok( 'Account created, please check your email inbox to verify your account.', $resourced);
            } else return Response::badRequest('cannot create your account, please try again.');
        } catch (\Exception $e) {
           return Response::badRequest($e->getMessage());
        }

    }

    /**
     * verifikasi akun sync from email
     * @author bachtiarpanjaitan <bachtiarpanjaitan0@gmail.com>
     * @param Request $req
     * @return type
     */
    public function accountSyncVerify(Request $req) {
        // validating, makesure token and email parameter exists
        $validated = Validator::make($req->all(), [
            'token' => 'required|string|min:64|max:64',
            'email' => 'required|email',
        ],[
            'required' => ':attribute cannot be null',
            'email' => ':email must be email format',
            'token.min' => ':attribute minimal character 64',
            'token.max' => ':attribute maximal character 64',
            'string' => ':attribute must be as string'
        ]);
        if ($validated->fails()) {
            return Response::badRequest($validated->errors()->first());
        }

        $user = User::where('email', $req->email)->first();
        if($user){
            $user->verified = true;
            // token credential check
            $credentialObj = $this->_tokenValidation($req->email, $req->token, 'register');
            if (!$credentialObj) return Response::badRequest('Bad Request! Invalid token verification');
            if ($user->save()) {
                $credentialObj->is_accessed = true;
                $credentialObj->save();

                $resp = Response::ok("Your account is sorted and synced, you can log in now.");
                return $resp;
            }
        } else return Response::notFound("Your account cannot be found, please register a new account");

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

    /**
     * create token credential
     * @param string $type, tipe kredensial 'register' / 'fp' (forgot password)
     * @return type
     */
    private function _createCredential(string $type) {
        $token = \Illuminate\Support\Str::random(64);
        $expireModify = (new \DateTime)->modify("+{$this->_expiredResetLink} minutes");
        $expiredOn = new DateTime;
        $expiredOn->setTimestamp($expireModify->getTimestamp() * 1000);
        // dd($expiredOn);
        $resourced = UserToken::create([
            'email' => request('email'),
            'token' => $token,
            'type' => $type,
            'expire_on' => $expiredOn,
            'is_accessed' => false,
        ]);

        return $resourced;
    }

    /**
     * check if token exists
     * @param string $email
     * @param string $token
     * @param string $type
     * @return boolean
     */
    private function _tokenValidation(string $email, string $token, string $type) {
        // check if token valid by validating incoming token and email
        $tokenObj = UserToken::where([
            'email' => $email,
            'token' => $token,
            'type' => $type,
        ])->first();
        if (!$tokenObj) {
            return false;
        }

        return $tokenObj;
    }

    /**
     * check if token is still alive
     * @param type $tokenObj
     * @return boolean
     */
    private function _tokenExpireValidation($tokenObj) {
        // check expire date of link
        $datetime = (new \DateTime);
        $expiredOn = ($tokenObj->expire_on)->toDateTime();
        $isExpired = $datetime->diff($expiredOn);
        if ($isExpired->invert > 0 || $tokenObj->is_accessed) {
            return false;
        }

        return true;
    }


}