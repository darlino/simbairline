<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('booking_id')->unsigned();
            $table->float('commission_rate')->default(0.0);
            $table->unsignedFloat('amount')->default(0.0);
            $table->string('unit')->default('');
            $table->timestamps();
            $table->foreign("booking_id")->references('id')->on("bravo_bookings")->onDelete('cascade');
            $table->foreign("user_id")->references('id')->on("users")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commission');
    }
}
