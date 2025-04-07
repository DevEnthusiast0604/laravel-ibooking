<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('gmz_coupon', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('code');
          $table->string('description')->nullable();
          $table->string('start_date', 20);
          $table->string('end_date', 20);
          $table->float('percent', 16, 5);
          $table->integer('author');
          $table->string('status', 20);
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
		Schema::drop('gmz_coupon');
	}
}
