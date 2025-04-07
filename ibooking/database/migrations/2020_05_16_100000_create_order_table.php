<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('gmz_order', function (Blueprint $table) {
         $table->increments('id');
         $table->string('sku', 50);
         $table->string('order_token')->nullable();
         $table->string('description')->nullable()->default(NULL);
         $table->unsignedInteger('post_id');
         $table->float('total', 16, 5);
         $table->unsignedSmallInteger('number');
         $table->unsignedInteger('buyer');
         $table->unsignedInteger('owner');
         $table->string('payment_type', 50);
         $table->mediumText('checkout_data');
         $table->string('token_code', 255)->nullable()->default(NULL);
         $table->string('currency', 255);
         $table->unsignedTinyInteger('commission');
         $table->unsignedInteger('start_date');
         $table->unsignedInteger('end_date');
          $table->unsignedInteger('start_time')->nullable();
          $table->unsignedInteger('end_time')->nullable();
         $table->string('post_type', 30);
         $table->boolean('payment_status');
         $table->string('transaction_id', 100)->nullable()->default(NULL);
         $table->string('status', 30)->nullable();
         $table->boolean('money_to_wallet')->default(0);
         $table->string('first_name', 50)->nullable()->default(NULL);
         $table->string('last_name', 50)->nullable()->default(NULL);
         $table->string('email', 100);
         $table->string('phone', 20)->nullable()->default(NULL);
         $table->string('address', 255)->nullable()->default(NULL);
         $table->string('city', 100)->nullable()->default(NULL);
         $table->string('country', 100)->nullable()->default(NULL);
         $table->string('postcode', 10)->nullable()->default(NULL);
         $table->string('note', 500)->nullable()->default(NULL);
         $table->text('change_log')->nullable()->default(NULL);
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
		Schema::drop('gmz_order');
	}
}
