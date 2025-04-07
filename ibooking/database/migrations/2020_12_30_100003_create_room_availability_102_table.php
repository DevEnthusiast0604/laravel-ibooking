<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomAvailability102Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('gmz_room_availability')){
            $this->down();
        }
		Schema::create('gmz_room_availability', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->bigInteger('hotel_id');
            $table->bigInteger('total_room')->nullable();
            $table->integer('adult_number')->nullable();
            $table->integer('child_number')->nullable();
            $table->integer('check_in')->nullable();
            $table->integer('check_out')->nullable();
            $table->string('number')->nullable();
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
		Schema::drop('gmz_room_availability');
	}
}
