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
            $table->string('code')->nullable();
            $table->integer('user_id');
            $table->integer('customer_id');

            $table->integer('amount');
            $table->integer('price');
            $table->integer('total_money');

            $table->date('quotations_date')->comment('ngày báo giá');
            $table->string('power',15)->comment('công suất');
            $table->string('voltage_input',15)->comment('điện áp vào');
            $table->string('voltage_output',15)->comment('điện áp ra');
            $table->string('standard_output',31)->comment('tiêu chuẩn sản xuất');
            $table->string('standard_real',31)->comment('tiêu chuẩn xuất máy');
            $table->integer('guarantee')->comment('thời gian bảo hành (tháng)');

            $table->boolean('product_skin')->comment('ngoại hình máy');
            $table->boolean('product_type')->comment('[1:máy, 2:tủ/trạm]');
            $table->string('setup_at')->comment('địa chỉ lắp đặt');
            $table->string('delivery_at')->comment('địa chỉ giao hàng');

            $table->string('order_status')->comment('[1:đã ký, 2:chưa ký]');
            $table->text('note')->comment('ghi chú');

            $table->text('reason')->comment('lý do thất bại hoặc thành công');
            $table->boolean('status')->comment('[1:thành công, 2:thất bại, 3:đang theo]');
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
