<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_car', function (Blueprint $table) {
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
         $table->longText('equipments')->nullable();
         $table->string('car_type')->nullable();
         $table->string('car_feature')->nullable();
         $table->string('car_equipment')->nullable();
         $table->float('rating', 8, 1)->nullable();
         $table->string('is_featured', 3)->nullable()->default(0);
         $table->text('discount_by_day')->nullable();
         $table->text('insurance_plan')->nullable();
         $table->integer('passenger')->nullable()->default(1);
         $table->string('gear_shift')->nullable();
         $table->integer('baggage')->nullable();
         $table->integer('door')->nullable();
         $table->string('video')->nullable();
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
		Schema::drop('gmz_car');
	}
}
