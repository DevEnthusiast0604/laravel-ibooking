<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerm102Table extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('gmz_term', function (Blueprint $table) {
            if (!Schema::hasColumn("gmz_term", 'parent')) {
                $table->bigInteger('parent')->default(0);
            }
           if (!Schema::hasColumn("gmz_term", 'term_location')) {
              $table->text('term_location')->nullable();
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
                'parent',
                'term_location'
            ]);
        });
	}
}
