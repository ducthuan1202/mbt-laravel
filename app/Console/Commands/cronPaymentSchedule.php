<?php

namespace App\Console\Commands;

use App\PaymentSchedule;
use Illuminate\Console\Command;

class cronPaymentSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:paymentSchedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật trạng thái cho lịch trình thanh toán của đơn hàng và công nợ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * run demo: php artisan cron:paymentSchedule
     * @return mixed
     */
    public function handle()
    {
        $paymentScheduleModel = new PaymentSchedule();
        $paymentScheduleModel->updateStatusSchedule();
    }
}
