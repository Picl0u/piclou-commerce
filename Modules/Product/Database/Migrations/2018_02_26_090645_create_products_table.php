<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Table produit */
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->boolean('published');
            $table->string('name');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->integer('shop_category_id');
            $table->foreign('shop_category_id')->references('id')->on('shopCategories');
            $table->integer('order')->nullable()->default(0);
            $table->string('reference');
            $table->string('isbn_code')->nullable();
            $table->string('ean_code')->nullable();
            $table->string('upc_code')->nullable();
            $table->integer('vat_id')->nullable();
            $table->float('price_ht');
            $table->float('price_ttc');
            $table->dateTime('reduce_date_begin')->nullable();
            $table->dateTime('reduce_date_end')->nullable();
            $table->float('reduce_price')->nullable();
            $table->float('reduce_percent')->nullable();
            $table->integer('stock_brut')->nullable();
            $table->integer('stock_booked')->nullable();
            $table->integer('stock_available')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::create('products_has_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->integer('shop_category_id');
            $table->foreign('shop_category_id')->references('id')->on('shopCategories');
        });

        Schema::create('products_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('image');
            $table->integer('order')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
