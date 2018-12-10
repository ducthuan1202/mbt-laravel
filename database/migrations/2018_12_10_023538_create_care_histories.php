<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('care_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('start_date')->nullable()->comment('ngày gọi chăm sóc');
            $table->timestamp('end_date')->nullable()->comment('ngày hẹn gọi lại');
            $table->text('content')->comment('nội dung');
            $table->text('customer_note')->comment('tình trạng khách hàng');
            $table->string('status')->comment('trạng thái chăm sóc');
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
        Schema::dropIfExists('care_histories');
    }
}
