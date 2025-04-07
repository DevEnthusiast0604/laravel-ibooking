<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpaceAvailability107Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gmz_space_availability', function (Blueprint $table) {
            if (!Schema::hasColumn("gmz_space_availability", 'is_base')) {
                $table->integer('is_base')->default(0);
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
        Schema::table('gmz_space_availability', function (Blueprint $table) {
            $table->dropColumn([
                'is_base'
            ]);
        });
	}
}
