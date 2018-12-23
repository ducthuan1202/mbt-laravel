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
            $table->string('code', 11)->nullable()->comment('mã KH');
            $table->integer('user_id');
            $table->integer('city_id');
            $table->string('company')->nullable(); // 1 KH có thể ko thuộc 1 cty nào
            $table->string('address')->nullable()->comment('địa chỉ chi tiết');
            $table->string('name', 63);
            $table->string('position',63)->nullable()->comment('Chức vụ');
            $table->string('mobile');
            $table->integer('average_sale')->nullable()->comment('doanh số trung bình');
            $table->boolean('status')->comment('[1:đã mua, 2:chưa mua]');
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
