<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgent104nTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('gmz_agent')){
            $this->down();
        }
       Schema::create('gmz_agent', function (Blueprint $table) {
          $table->id();
          $table->text('post_title');
          $table->longText('post_content')->nullable();
          $table->string('location_address')->nullable();
          $table->string('thumbnail_id')->nullable();
          $table->string('gallery')->nullable();
          $table->float('rating', 8, 1)->nullable();
          $table->string('is_featured', 3)->nullable()->default(0);
          $table->string('video')->nullable();
          $table->string('post_type')->nullable();
          $table->bigInteger('author');
          $table->string('status');
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
        Schema::disableForeignKeyConstraints();
       Schema::dropIfExists('gmz_agent');
        Schema::enableForeignKeyConstraints();
    }
}
