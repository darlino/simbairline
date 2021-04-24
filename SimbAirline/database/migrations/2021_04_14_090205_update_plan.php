<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan', function (Blueprint $table) {
//            $table->dropForeign(['flight_id']);
//            $table->dropColumn('flight_id');
            $table->string('flight_id', 255)->charset('utf8')->nullable();
            $table->foreign("flight_id")->references('slug')->on("bravo_spaces")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
