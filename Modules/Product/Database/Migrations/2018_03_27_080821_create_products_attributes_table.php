<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->json('declinaisons');
            $table->integer('stock_brut')->nullable();
            $table->string('reference')->nullable();
            $table->string('ean_code')->nullable();
            $table->string('upc_code')->nullable();
            $table->string('isbn_code')->nullable();
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
        Schema::dropIfExists('products_attributes');
    }
}
