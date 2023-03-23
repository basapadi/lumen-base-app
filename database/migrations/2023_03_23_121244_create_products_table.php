<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->index();
            $table->tinyInteger('type');
            $table->string('imei')->nullable()->index();
            $table->string('barcode')->nullable();
            $table->string('sku');
            $table->string('name');
            $table->integer('unit_id')->index();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->double('width')->nullable();
            $table->longText('description')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
