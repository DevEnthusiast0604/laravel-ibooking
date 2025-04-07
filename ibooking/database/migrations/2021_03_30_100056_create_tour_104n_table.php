<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTour104nTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('gmz_tour')){
            $this->down();
        }
		Schema::create('gmz_tour', function (Blueprint $table) {
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
            $table->float('adult_price')->nullable();
            $table->float('children_price')->nullable();
            $table->float('infant_price')->nullable();
            $table->string('booking_form', 20)->nullable()->default('instant');
            $table->integer('group_size')->nullable();
            $table->string('duration')->nullable();
            $table->string('booking_type', 20)->nullable()->default('date');
            $table->string('external_link')->nullable();
            $table->longText('extra_services')->nullable();
            $table->string('tour_type')->nullable();
            $table->string('tour_include')->nullable();
            $table->string('tour_exclude')->nullable();
            $table->longText('highlight')->nullable();
            $table->longText('itinerary')->nullable();
            $table->longText('faq')->nullable();
            $table->string('enable_cancellation', 10)->nullable();
            $table->integer('cancel_before')->nullable();
            $table->longText('cancellation_detail')->nullable();
            $table->float('rating', 8, 1)->nullable();
            $table->string('is_featured', 3)->nullable()->default(0);
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
		Schema::drop('gmz_tour');
	}
}
