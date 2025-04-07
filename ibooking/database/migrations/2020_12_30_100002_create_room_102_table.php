<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoom102Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('gmz_room')){
            $this->down();
        }
		Schema::create('gmz_room', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->text('post_title');
            $table->longText('post_content')->nullable();
            $table->string('thumbnail_id')->nullable();
            $table->string('gallery')->nullable();
            $table->float('base_price')->nullable();
            $table->integer('number_of_room')->nullable();
            $table->float('room_footage')->nullable();
            $table->integer('number_of_bed')->nullable();
            $table->integer('number_of_adult')->nullable();
            $table->integer('number_of_children')->nullable();
            $table->string('room_facilities')->nullable();
            $table->bigInteger('hotel_id');
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
		Schema::drop('gmz_room');
	}
}
