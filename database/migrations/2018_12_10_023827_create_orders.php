<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->text('note');
            $table->integer('total_money');
            $table->string('payment_type')->comment('kiểu thanh toán');
            $table->timestamp('start_date')->nullable()->comment('ngày sản xuất');
            $table->timestamp('shipped_date')->nullable()->comment('ngày giao hàng');
            $table->string('status')->comment('trạng thái đơn hàng');
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
        Schema::dropIfExists('orders');
    }
}
