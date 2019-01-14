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

        $name = sprintf('cronjob/cron_job_%s.txt', date('Y_m_d_H_i_s'));
        $file = fopen(public_path($name), "w") or die("Unable to open file!");
        fwrite($file, sprintf('call task schedule at %s', date('Y-m-d H:i:s')));
        fclose($file);
        $this->info(sprintf('create file %s success', $name));

//        $this->info('START [cron:paymentSchedule]');
//
//        $paymentScheduleModel = new PaymentSchedule();
//        $paymentScheduleModel->updateStatusSchedule();
//
//        $this->info('END [cron:paymentSchedule]');

    }
}
