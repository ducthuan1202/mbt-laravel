<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',63);
            $table->string('mobile',31);
            $table->string('email',127)->nullable();
            $table->string('password',127);
            $table->boolean('role')->comment('[1:admin, 2:quản lý, 3:nhân viên]');
            $table->boolean('status')->comment('[1:hoạt động, 2:bị khóa]');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
