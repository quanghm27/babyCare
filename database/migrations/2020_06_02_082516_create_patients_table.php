<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('sharekey')->unique();
            $table->integer('deviceUserId');
            $table->string('medical_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('name');
            $table->string('roomNo');
            $table->string('bedNo');
            $table->dateTime('joinDate');
            $table->dateTime('releaseDate')->nullable();
            $table->integer('minTemp');
            $table->integer('maxTemp');
            $table->string('note')->nullable();
            $table->dateTime('expiredTime')->nullable();
            $table->integer('operator_id');
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
        Schema::dropIfExists('patients');
    }
}
