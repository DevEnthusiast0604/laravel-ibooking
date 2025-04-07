<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_media', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('media_title')->nullable();
			$table->string('media_name');
			$table->string('media_url');
			$table->string('media_path');
			$table->string('media_description')->nullable();
			$table->string('media_size');
			$table->string('media_type', 50);
			$table->bigInteger('author');
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
		Schema::drop('gmz_media');
	}
}
