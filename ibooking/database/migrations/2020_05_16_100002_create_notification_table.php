<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_notification', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->bigInteger('user_from')->nullable();
            $table->bigInteger('user_to');
            $table->string('title');
            $table->longText('message');
            $table->string('type', 50);
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
		Schema::drop('gmz_notification');
	}
}
