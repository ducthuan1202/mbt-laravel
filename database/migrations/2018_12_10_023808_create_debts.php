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
            $table->integer('user_id');
            $table->timestamp('debt_date')->nullable()->comment('ngày công nợ');
            $table->text('content')->nullable();
            $table->integer('amount');
            $table->integer('price');
            $table->integer('vat')->comment('% VAT');
            $table->integer('residual')->comment('số dư');
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
        Schema::dropIfExists('debts');
    }
}
