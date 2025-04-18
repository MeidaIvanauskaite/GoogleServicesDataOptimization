<?php
    namespace App\Console;
    use Illuminate\Console\Scheduling\Schedule;
    use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
    use Illuminate\Support\Facades\Artisan;

    class Kernel extends ConsoleKernel {
        protected function schedule(Schedule $schedule): void {
            // Clear cache every minute (for testing)
            $schedule->call(function () {
                Artisan::call('cache:clear');
                \App\Jobs\FetchAnalyticsData::dispatch();
            })->everyMinute();
        }

        protected function commands(): void {
            $this->load(__DIR__ . '/Commands');
            require base_path('routes/console.php');
        }
    }
