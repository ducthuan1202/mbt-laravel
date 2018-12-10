<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('product_id');
            $table->timestamp('quotations_date')->nullable()->comment('ngày báo giá');
            $table->integer('amount');
            $table->integer('price');
            $table->integer('total_money');
            $table->string('setup_at')->comment('địa chỉ lắp đặt');
            $table->string('delivery_at')->comment('địa chỉ giao hàng');
            $table->string('customer_status')->comment('trạng thái khách hàng');
            $table->string('guarantee')->comment('thời gian bảo hành');
            $table->text('note')->comment('ghi chú');
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
        Schema::dropIfExists('price_quotations');
    }
}
