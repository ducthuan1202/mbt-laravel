<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_skin_id');
            $table->string('name');
            $table->string('capacity')->comment('công suất');
            $table->string('voltage_input')->comment('điện áp vào');
            $table->string('voltage_output')->comment('điện áp ra');
            $table->integer('price');
            $table->string('standard')->comment('tiêu chuẩn');
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
        Schema::dropIfExists('products');
    }
}
