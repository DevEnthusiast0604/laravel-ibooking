<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentAvailability104nnTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
       if (Schema::hasTable('gmz_agent_availability')){
           $this->down();
       }
      Schema::create('gmz_agent_availability', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->foreignId('post_id')->constrained('gmz_agent')->onDelete('cascade');
         $table->integer('check_in');
         $table->integer('check_out')->nullable();
         $table->string('status')->nullable();
         $table->string('post_type')->nullable();
         $table->unsignedInteger('order_id')->nullable();
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
      Schema::drop('gmz_agent_availability');
   }
}
