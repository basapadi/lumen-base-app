<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->index();
            $table->string('firstname',50);
            $table->string('lastname',50)->nullable();
            $table->string('phone',16)->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->text('shipping_address')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
