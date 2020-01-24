<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tracking_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_address');
            $table->string('delivery_name');
            $table->string('delivery_phone');
            $table->string('delivery_address');
            $table->unsignedBigInteger('category_id');
            $table->integer('shipping_cost');
            $table->integer('item_price');
            $table->boolean('is_insurance')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->integer('user_fee');
            $table->string('note')->nullable();
            $table->string('shipping_photo');
            $table->string('delivered_photo');
            $table->char('status', 1)->comment('0: new, 1: shipping, 2: complete, 3: complaint, 4: cancel');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
