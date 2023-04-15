<?php
namespace App\Services;

use App\Libraries\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Traits\{
    StaticResponseTrait,
};
use App\Models\{
    Purchase,
    PurchaseDetail
};
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Btx\QueryFilter\Traits\QueryFilter;

class PurchaseService {

    use StaticResponseTrait,QueryFilter;

    public function list(Request $request){
       
        $preload = Purchase::with('details');
        $this->filter($preload);
        $total = Purchase::get()->count();
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