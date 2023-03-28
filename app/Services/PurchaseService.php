<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\StaticResponseTrait;
use App\Models\{
    Purchase,
    PurchaseDetail
};
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseService {

    use StaticResponseTrait;

    public function create(Request $req){
        $validated = Validator::make($req->all(), [
            'contact_id' => 'required|numeric',
            'code' => 'required|string',
            'status' => 'required',
        ]);
       
        if ($validated->fails()) {
            return $this->response400($validated->errors()->first());
        }
        
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
            // dd($purchaseDetails);

            foreach ($purchaseDetails as $key => $pu) {
                PurchaseDetail::create($pu);
            }
            DB::commit();
            return ApiResponse::make(true,'Data Inserted',$purchase);

        }catch(Exception $e){
            DB::rollBack();
            return $this->response500($e);
        }


    }
}