<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuStructureTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_menu_structure', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_id')->nullable();
            $table->string('parent_id')->nullable();
            $table->longText('depth')->nullable();
            $table->string('left')->nullable();
            $table->string('right')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('post_id')->nullable();
            $table->string('post_title')->nullable();
            $table->string('url')->nullable();
            $table->string('class')->nullable();
            $table->string('menu_id')->nullable();
            $table->string('menu_lang')->nullable()->default(get_current_language());
            $table->integer('target_blank')->nullable();
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
		Schema::drop('gmz_menu_structure');
	}
}
