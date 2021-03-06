<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->boolean('free')->nullable()->default(0);
            $table->boolean('price')->nullable()->default(0);
            $table->boolean('weight')->nullable()->default(0);
            $table->string('name');
            $table->string('delay');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->float('max_weight')->nullable();
            $table->float('max_width')->nullable();
            $table->float('max_height')->nullable();
            $table->float('max_length')->nullable();
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
        Schema::dropIfExists('carriers');
    }
}
