<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermRelationTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gmz_term_relation', function (Blueprint $table) {
			$table->bigIncrements('id');
            $table->foreignId('term_id')->constrained('gmz_term')->onDelete('cascade');
            $table->bigInteger('post_id');
			$table->string('post_type', 50)->nullable();
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
		Schema::drop('gmz_term_relation');
	}
}
