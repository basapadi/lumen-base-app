<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false;

    public function unit(){
        return $this->hasOne(ProductUnit::class,'id','unit_id');
    }

    public function images(){
        return $this->hasMany(ProductImage::class, 'product_id','id');
    }
    
}