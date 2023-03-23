<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\{
    User,
    Product
};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\StaticResponseTrait;

class TestService {

    use StaticResponseTrait;

    public function respon(Request $request){

        $products = Product::with('unit')->get();
        // dd($products);
        return ApiResponse::make(true, 'BERHASIL LOAD DATA',$products);
        // return $this->response500('MAAF tidak sekarang');
    }
}