<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeta108Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_meta', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->bigInteger('post_id');
            $table->string('post_type')->nullable();
            $table->string('meta_key')->nullable();
            $table->longText('meta_value')->nullable();
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
		Schema::drop('gmz_meta');
	}
}
