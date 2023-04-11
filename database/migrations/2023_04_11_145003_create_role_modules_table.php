<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id')->index();
            $table->integer('module_id')->index();
            $table->boolean('view')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('create')->default(false);
            $table->boolean('delete')->default(false);
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
        Schema::dropIfExists('role_modules');
    }
}
