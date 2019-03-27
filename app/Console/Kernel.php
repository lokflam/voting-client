<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '* * * * *')->get();
            process_jobs($jobs);
        })->cron('* * * * *');

        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '*/5 * * * *')->get();
            process_jobs($jobs);
        })->cron('*/5 * * * *');

        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '*/10 * * * *')->get();
            process_jobs($jobs);
        })->cron('*/10 * * * *');

        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '*/15 * * * *')->get();
            process_jobs($jobs);
        })->cron('*/15 * * * *');

        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '*/30 * * * *')->get();
            process_jobs($jobs);
        })->cron('*/30 * * * *');

        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '0 * * * *')->get();
            process_jobs($jobs);
        })->cron('0 * * * *');

        $schedule->call(function () {
            $jobs = DB::table('cron')->where('intervel', '0 0 * * *')->get();
            process_jobs($jobs);
        })->cron('0 0 * * *');
    }

    public function process_jobs($jobs) {
        foreach($jobs as $job) {
            if($job->job == 'count_ballot') {
                $url = url('ballot/count');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                    'private_key' => '793b849da13c4829693fa555c54686e44951f227637929e0997bd1b67705ecec',
                    'vote_id' => $job->data,
                ]));
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); 
                curl_exec($ch);
                curl_close($ch);
            }

            if($job->job == 'update_result') {
                $url = url('cron/result/update?vote_id='.$job->data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
