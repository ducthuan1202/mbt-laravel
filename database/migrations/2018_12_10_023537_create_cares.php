<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->date('start_date')->nullable()->comment('ngày gọi chăm sóc');
            $table->date('end_date')->nullable()->comment('ngày hẹn gọi lại');
            $table->text('customer_note')->comment('mô tả về KH');
            $table->integer('status')->comment('[1:báo giá, 2:xin viêc, ..., 13:cmsn]');
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
        Schema::dropIfExists('cares');
    }
}
