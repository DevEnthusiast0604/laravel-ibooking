<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlist105Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('gmz_wishlist')){
            $this->down();
        }
		Schema::create('gmz_wishlist', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->integer('post_id');
            $table->string('post_type');
            $table->integer('author');
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
		Schema::drop('gmz_wishlist');
	}
}
