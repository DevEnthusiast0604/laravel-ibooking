<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('users', function (Blueprint $table) {
         $table->id();
         $table->string('first_name')->nullable();
         $table->string('last_name')->nullable();
         $table->string('email', '128')->unique();
         $table->timestamp('email_verified_at')->nullable();
         $table->string('phone', '20')->nullable();
         $table->string('password')->nullable();
         $table->string('address')->nullable();
         $table->integer('request')->nullable()->default(0);
         $table->string('request_date')->nullable();
         $table->integer('avatar')->nullable();
         $table->string('provider')->nullable();
         $table->string('provider_id')->nullable();
         $table->string('payout',500)->nullable();
         $table->rememberToken();
         $table->timestamp('last_check_notification')->nullable();
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
