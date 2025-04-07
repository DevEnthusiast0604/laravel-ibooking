<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmz_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->float('total')->default(0);
            $table->float('balance')->default(0);
            $table->float('net_earnings')->default(0);
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
        Schema::dropIfExists('earning');
    }
}
