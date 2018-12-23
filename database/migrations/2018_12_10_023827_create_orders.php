<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('user_id');
            $table->integer('customer_id');
            $table->integer('amount');
            $table->integer('price');
            $table->integer('total_money');
            $table->string('power',15)->comment('công suất');
            $table->string('voltage_input',15)->comment('điện áp vào');
            $table->string('voltage_output',15)->comment('điện áp ra');
            $table->string('standard_output',31)->comment('tiêu chuẩn xuất máy');
            $table->string('standard_real',31)->comment('tiêu chuẩn xuất thực');
            $table->integer('guarantee')->comment('thời gian bảo hành (tháng)');
            $table->string('product_number', 31)->comment('số máy');
            $table->boolean('product_skin')->comment('ngoại hình máy');
            $table->boolean('product_type')->comment('[1:máy, 2:tủ/trạm]');
            $table->string('setup_at')->comment('địa chỉ nơi lắp');
            $table->string('delivery_at')->comment('địa chỉ giao hàng');
            $table->date('start_date')->nullable()->comment('ngày vào sản xuất');
            $table->date('shipped_date')->nullable()->comment('ngày dự kiến giao hàng');
            $table->date('shipped_date_real')->nullable()->comment('ngày dự kiến giao hàng');
            $table->text('note')->comment('ghi chú đơn hàng');
            $table->boolean('status')->comment('[1:đã giao, 2:chưa giao, 3:đã hủy]');

            $table->boolean('prepay')->comment('[1:có tạm ứng, 2:không tạm ứng]');
            $table->boolean('payment_pre_shipped')->comment('[1:thanh toán hết trước giao, 2:thanh toán sau khi giao]');
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
        Schema::dropIfExists('orders');
    }
}
