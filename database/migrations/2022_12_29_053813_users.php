<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->text('password');
            $table->boolean('verified')->default(false);
            $table->text('avatar')->nullable();
            $table->tinyInteger('role')->nullable();
            $table->tinyInteger('level')->default(1);
            $table->string('zip_code',6)->nullable();
            $table->string('adm_1',15)->nullable();
            $table->string('adm_2',15)->nullable();
            $table->boolean('is_active')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
