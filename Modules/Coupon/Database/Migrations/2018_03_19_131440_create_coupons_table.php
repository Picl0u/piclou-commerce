<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->string('name');
            $table->string('coupon');
            $table->float('percent')->nullable();
            $table->float('price')->nullable();
            $table->integer('use_max')->nullable();
            $table->float('amount_min')->nullable();
            $table->timestamp('begin')->nullable();
            $table->timestamp('end')->nullable();
            $table->timestamps();
        });

        Schema::create('coupon_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->integer('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->integer('user_id');
            $table->timestamp('use')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('coupon_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->integer('coupon_id');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('coupons');
    }
}
