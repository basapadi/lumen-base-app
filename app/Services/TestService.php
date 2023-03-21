<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\StaticResponseTrait;

class TestService {

    use StaticResponseTrait;

    public function respon(Request $request){
        return ApiResponse::make(true, 'BERHASIL LOAD DATA',$request->all());
        // return $this->response500('MAAF tidak sekarang');
    }
}