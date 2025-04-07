<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotel106Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gmz_hotel', function (Blueprint $table) {
            if (!Schema::hasColumn("gmz_hotel", 'booking_form')) {
                $table->string('booking_form', 20)->nullable()->default('both');
            }
            if (!Schema::hasColumn("gmz_hotel", 'external_booking')) {
                $table->string('external_booking', 3)->nullable()->default('off');
            }
            if (!Schema::hasColumn("gmz_hotel", 'external_link')) {
                $table->string('external_link')->nullable();
            }
            if (!Schema::hasColumn("gmz_hotel", 'post_description')) {
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
        Schema::table('gmz_hotel', function (Blueprint $table) {
            $table->dropColumn([
                'booking_form',
                'external_booking',
                'external_link',
                'post_description'
            ]);
        });
    }
}
