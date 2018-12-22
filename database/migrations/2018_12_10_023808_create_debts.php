<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('total_money');
            $table->boolean('status')->comment('[1:nợ cũ, 2:nợ mới]');
            $table->boolean('type')->comment('[1:đã thanh toán, 2:chưa thanh toán]');
            $table->date('date_create')->nullable();
            $table->date('date_pay')->nullable();
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
        Schema::dropIfExists('debts');
    }
}
