<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('city_id');
            $table->string('name');
            $table->string('position')->comment('Chức vụ');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('address');
            $table->integer('total_sale')->nullable()->comment('tổng tiền đã giao dịch');
            $table->string('buy_status');
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
        Schema::dropIfExists('customers');
    }
}
