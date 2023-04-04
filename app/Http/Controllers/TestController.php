<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\TerbilangTrait;
use App\Traits\StaticResponseTrait;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller {

    use TerbilangTrait,StaticResponseTrait;

    public function bilangan(Request $req) {
        $validated = Validator::make($req->all(), [
            'value' => 'required|numeric',
        ],[
            'required' => ':attribute cannot be null'
        ]);
       
        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }
        $string = $this->terbilang($req->value);
        return response()->json($string, 200);
    }

}