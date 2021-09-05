<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDotaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string("gender");
        });

        Schema::create('dota_users_personal_information', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->timestamp("date_of_birth")->nullable();
            $table->unsignedBigInteger("gender_id");
            $table->foreign("gender_id")->references("id")->on("genders");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genders');
    }
}
