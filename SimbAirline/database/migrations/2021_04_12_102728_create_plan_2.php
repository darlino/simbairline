<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlan2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('routing',255)->nullable();
            $table->integer('pax')->nullable();
            $table->date('date')->nullable();
            $table->string('from',255)->nullable();
            $table->time('etd')->nullable();
            $table->time('eta')->nullable();
            $table->string('to',255)->nullable();
            $table->time('eet')->nullable();
            $table->time('ground_time')->nullable();
            $table->time('night_stop')->nullable();
            $table->string('routing_nature1',255)->nullable();
            $table->string('routing_nature2',255)->nullable();
            $table->string('call_sign',255)->nullable();
            $table->string('flight_number',255)->nullable();
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
        Schema::dropIfExists('plan');
    }
}
