<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerm104Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gmz_term', function (Blueprint $table) {
            if (!Schema::hasColumn("gmz_term", 'author')) {
                $table->bigInteger('author')->default(0);
            }
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('gmz_term', function (Blueprint $table) {
            $table->dropColumn([
                'author'
            ]);
        });
	}
}
