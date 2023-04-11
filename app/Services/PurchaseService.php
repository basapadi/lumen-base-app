<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\{
    StaticResponseTrait,
    DbTrait
};
use App\Models\{
    Purchase,
    PurchaseDetail
};
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseService {

    use StaticResponseTrait,DbTrait;

    public function list(Request $request){
       
        $preload = Purchase::with(['details'=>function ($query) use($request){
            //return $this->queryFilters($query);
            return $query->where('product_id',$request->product_id);
        }]);
        
        //dd($preload->toSql());
        $total = Purchase::get()->count();
       /*  $this->queryFilters($preload);
        $this->limiter($preload);  */
       // dd($preload->toSql());
        $purchases = $preload->get();
        ApiResponse::setIncludeData(['jumlah'=>$purchases->count(),'total'=>$total]);
        return ApiResponse::make(true, 'BERHASIL LOAD '.count($purchases). ' DATA',$purchases);
    }

    public function create(Request $req){
        $validated = Validator::make($req->all(), [
            'contact_id' => 'required|numeric',
            'code' => 'required|string',
            'status' => 'required',
        ]);
       
        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }

        // $products = Product::all()->toArray();
        /**
         * Cara menggunakan mapping apabila datanya berbentuk array collection
         */
        // $modificationProducts = $products->map(function($product){
        //     if($product->id == 1) {
        //         $product->description = 'Ini udah di modif y';
        //         $product->hasModified = true;
        //     } else $product->hasModified = false;
        //     return $product;
        // });


        /**
         * Cara menggunakan mapping apabila datanya berbentuk array
         */
        // $modificationProducts = collect($products)->map(function($product){
        //     if($product['id'] == 1) {
        //         $product['description'] = 'Ini udah di modif y';
        //         $product['hasModified'] = true;
        //     } else $product['hasModified'] = false;
        //     return $product;
        // });

        try {
            DB::beginTransaction();
            $preInsert = [
                'purchase_date' => Carbon::now()->format('Y-m-d'),
                'contact_id' => $req->contact_id,
                'code' => $req->code,
                'status' => $req->status,
                'description' => $req->description,
                'down_payment' => $req->down_payment
            ];
            $purchase = Purchase::create($preInsert);

            $purchaseDetails = collect($req->products)->map(function($p) use ($purchase){
                $p['purchase_id'] = $purchase->id;
                return $p;
            })->toArray();

            foreach ($purchaseDetails as $key => $pu) {
                PurchaseDetail::create($pu);
            }
            DB::commit();
            return ApiResponse::make(true,'Data Inserted',$purchase);

        }catch(Exception $e){
            return $this->response500($e);
        }


    }
}