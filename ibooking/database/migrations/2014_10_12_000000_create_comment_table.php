<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmz_comment', function (Blueprint $table) {
            $table->bigIncrements('comment_id');
            $table->integer('post_id');
            $table->string('comment_title')->nullable();
            $table->longText('comment_content');
            $table->string('comment_name');
            $table->string('comment_email');
            $table->integer('comment_author');
            $table->string('comment_rate')->nullable();
            $table->string('post_type');
            $table->integer('parent');
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
        Schema::dropIfExists('gmz_comment');
    }
}
