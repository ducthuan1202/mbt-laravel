<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('money');
            $table->timestamp('payment_date')->nullable()->comment('ngày thanh toán'); // ngày
            $table->string('status')->comment('trạng thái thanh toán'); // [đã thanh toán, hẹn thanh toán, chậm thanh toán]
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
        Schema::dropIfExists('payment_schedules');
    }
}
