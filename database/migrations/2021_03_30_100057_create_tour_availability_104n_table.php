<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourAvailability104nTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('gmz_tour_availability')){
            $this->down();
        }
		Schema::create('gmz_tour_availability', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->integer('check_in');
            $table->integer('check_out')->nullable();
            $table->float('adult_price')->nullable();
            $table->float('children_price')->nullable();
            $table->float('infant_price')->nullable();
            $table->integer('group_size')->nullable();
            $table->integer('booked')->nullable();
            $table->string('status')->nullable();
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
		Schema::drop('gmz_tour_availability');
	}
}
