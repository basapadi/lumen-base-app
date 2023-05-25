<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    public $timestamps = false;

    public function category(){
        return $this->hasOne(UnitCategory::class,'id','category_id');
    }
}
