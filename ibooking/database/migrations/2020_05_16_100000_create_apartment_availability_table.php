<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentAvailabilityTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_apartment_availability', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->integer('check_in');
            $table->integer('check_out')->nullable();
            $table->string('price')->nullable();
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
		Schema::drop('gmz_apartment_availability');
	}
}
