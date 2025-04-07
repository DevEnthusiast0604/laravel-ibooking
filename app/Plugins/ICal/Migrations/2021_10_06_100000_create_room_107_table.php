<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoom107Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gmz_room', function (Blueprint $table) {
            if (!Schema::hasColumn("gmz_room", 'ical')) {
                $table->text('ical')->nullable();
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
        Schema::table('gmz_room', function (Blueprint $table) {
            $table->dropColumn([
                'ical'
            ]);
        });
	}
}
