<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Btx\{
    Common\SpellNumber,
    File\Upload,
    Query\Transformer,
    Http\Response
};
use Illuminate\Support\Facades\Validator;
use Btx\Http\Traits\StaticResponse;
use App\Models\{
    Product
};

class TestController extends Controller{
    use StaticResponse;


    public function bilangan(Request $req) {
        $validated = Validator::make($req->all(), [
            'value' => 'required|numeric',
        ],[
            'required' => ':attribute cannot be null'
        ]);
       
        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }
        $string = SpellNumber::generate($req->value);
        return response()->json($string, 200);
    }

    public function uploadImage(Request $req){
        $path = Upload::image([
            'file' => 'file',
            'size' => [500,500],
            'path' => 'uploads/'
        ]);
        return response()->json($path, 200);
    }

    public function uploadFile(Request $req){
        $path = Upload::file([
            'file' => 'file',
            'path' => 'uploads/'
        ]);
        return response()->json($path, 200);
    }

    public function columnTransformer(Request $request){
        $products = Product::with('unit')->filter()->get();
        $count = Product::with('unit')->filter(false)->count();
        $columns = [
            ['value' => 'id', 'label' => 'ID', 'align' => 'center'],
            ['value' => 'name', 'label' => 'Name']
        ];

        return Response::ok('OK',$products,['columns' => Transformer::quasarColumn($columns),'total' => $count]);
    }

}