<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeo106Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_seo', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('post_id');
			$table->string('post_type', 50)->default('post');
			$table->string('seo_title')->nullable();
			$table->text('meta_description')->nullable();
			$table->string('seo_image_facebook')->nullable();
			$table->string('seo_title_facebook')->nullable();
			$table->text('meta_description_facebook')->nullable();
			$table->string('seo_image_twitter')->nullable();
			$table->string('seo_title_twitter')->nullable();
			$table->text('meta_description_twitter')->nullable();
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
		Schema::drop('gmz_seo');
	}
}
