<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    

    protected $fillable = [
        'purchase_date', 'code','description','contact_id','down_payment','status'
    ];

    public function details(){
        return $this->hasMany(PurchaseDetail::class);
    }
}
