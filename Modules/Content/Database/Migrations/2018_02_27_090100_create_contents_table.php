<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->boolean('published')->default(0);
            $table->boolean('on_menu')->default(0);
            $table->boolean('on_footer')->default(0);
            $table->boolean('on_homepage')->default(0);
            $table->string('name');
            $table->string('slug');
            $table->integer('content_category_id')->nullable()->default(0)->unsigned();
            $table->foreign('content_category_id')->references('id')->on('content_categories');
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
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
        Schema::dropIfExists('contents');
    }
}
