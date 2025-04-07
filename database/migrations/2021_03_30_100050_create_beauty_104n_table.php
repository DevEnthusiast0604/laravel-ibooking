<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeauty104nTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      if (Schema::hasTable('gmz_beauty')) {
         $this->down();
      }
      Schema::create('gmz_beauty', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->text('post_title');
         $table->string('post_slug');
         $table->longText('post_content')->nullable();
         $table->float('location_lat', 10, 6)->default(21.0239852)->nullable();
         $table->float('location_lng', 10, 6)->default(105.791488)->nullable();
         $table->string('location_address')->nullable();
         $table->string('location_zoom')->nullable()->default(12);
         $table->string('location_state')->nullable();
         $table->string('location_postcode', 15)->nullable();
         $table->string('location_country', 50)->nullable();
         $table->string('location_city', 50)->nullable();
         $table->string('thumbnail_id')->nullable();
         $table->string('gallery')->nullable();
         $table->float('base_price')->nullable();
         $table->string('booking_form', 20)->nullable()->default('instant');
         $table->string('enable_cancellation', 10)->nullable();
         $table->integer('cancel_before')->nullable();
         $table->longText('cancellation_detail')->nullable();
         $table->integer('quantity')->nullable();
         $table->float('rating', 8, 1)->nullable();
         $table->string('is_featured', 3)->nullable()->default(0);
         $table->string('video')->nullable();

         $table->unsignedBigInteger('service')->nullable();
         $table->unsignedSmallInteger('available_space')->nullable();
         $table->integer('service_starts')->nullable();
         $table->integer('service_ends')->nullable();
         $table->integer('service_duration')->nullable();
         $table->unsignedBigInteger('branch')->nullable();
         $table->string('day_off_week',191)->nullable();

         $table->bigInteger('author');
         $table->string('status');
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
      Schema::dropIfExists('gmz_beauty');
   }
}
