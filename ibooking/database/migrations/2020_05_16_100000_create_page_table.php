<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_page', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->text('post_title');
			$table->string('post_slug');
			$table->longText('post_content')->nullable();
			$table->string('thumbnail_id')->nullable();
			$table->string('status', 50)->nullable();
			$table->bigInteger('author')->default(0);
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
		Schema::drop('gmz_page');
	}
}
