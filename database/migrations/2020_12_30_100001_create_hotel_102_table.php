<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotel102Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    if (Schema::hasTable('gmz_hotel')){
            $this->down();
        }
		Schema::create('gmz_hotel', function (Blueprint $table) {
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
            $table->longText('extra_services')->nullable();
            $table->integer('hotel_star')->nullable();
            $table->string('hotel_logo')->nullable();
            $table->string('video')->nullable();
            $table->longText('policy')->nullable();
            $table->string('checkin_time', 25)->nullable();
            $table->string('checkout_time', 25)->nullable();
            $table->integer('min_day_booking')->nullable();
            $table->integer('min_day_stay')->nullable();
            $table->longText('nearby_common')->nullable();
            $table->longText('nearby_education')->nullable();
            $table->longText('nearby_health')->nullable();
            $table->longText('nearby_top_attractions')->nullable();
            $table->longText('nearby_restaurants_cafes')->nullable();
            $table->longText('nearby_natural_beauty')->nullable();
            $table->longText('nearby_airports')->nullable();
            $table->longText('faq')->nullable();
            $table->string('enable_cancellation', 10)->nullable();
            $table->integer('cancel_before')->nullable();
            $table->longText('cancellation_detail')->nullable();
            $table->string('property_type')->nullable();
            $table->string('hotel_facilities')->nullable();
            $table->string('hotel_services')->nullable();
            $table->float('rating', 8, 1)->nullable();
            $table->string('is_featured', 3)->nullable()->default(0);
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
		Schema::drop('gmz_hotel');
	}
}
