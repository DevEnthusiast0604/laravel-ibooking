<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmz_taxonomy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('taxonomy_title');
            $table->string('taxonomy_name');
            $table->string('taxonomy_description')->nullable();
            $table->string('post_type')->default('post');
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
        Schema::drop('gmz_taxonomy');
    }
}
