<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCar106Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gmz_car', function (Blueprint $table) {
            if (!Schema::hasColumn("gmz_car", 'external_booking')) {
                $table->string('external_booking', 3)->nullable()->default('off');
            }
            if (!Schema::hasColumn("gmz_car", 'external_link')) {
                $table->string('external_link')->nullable();
            }
            if (!Schema::hasColumn("gmz_car", 'post_description')) {
                $table->text('post_description')->nullable();
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
        Schema::table('gmz_car', function (Blueprint $table) {
            $table->dropColumn([
                'external_booking',
                'external_link',
                'post_description'
            ]);
        });
    }
}
