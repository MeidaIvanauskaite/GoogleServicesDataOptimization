<?php
    namespace App\Jobs;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Google\Client;
    use Google\Service\Analytics;

    class FetchAnalyticsData implements ShouldQueue {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public function handle() {
            Log::info('FetchAnalyticsData job started.');
            $client = new \Google\Client();
            $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
            $client->addScope(\Google\Service\Analytics::ANALYTICS_READONLY);
            $analytics = new \Google\Service\Analytics($client);

            try {
                $accounts = $analytics->management_accounts->listManagementAccounts();
                $accountData = [];
                foreach ($accounts->getItems() as $account) {
                    $accountData[] = [
                        'id' => $account->getId(),
                        'name' => $account->getName(),
                        'created' => $account->getCreated(),
                        'permissions' => $account->getPermissions(),
                        'childLink' => $account->getChildLink(),
                    ];
                }

                Log::info('Fetched Data:', $accountData);
                Cache::put('google_analytics_accounts', $accountData, now()->addMinutes(30));
                Log::info('Data cached successfully');
            } catch (\Exception $e) {
                Log::error('Error fetching Analytics Data: ' . $e->getMessage());
            }
        }

        //docker exec -it php_app php artisan queue:work
        //docker exec -it php_app tail -f storage/logs/laravel.log
        //docker exec -it php_app php artisan tinker
        //Cache::get('google_analytics_accounts');

        private function fetchAnalyticsData() {
            $client = new Client();
            $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/analytics.readonly');

            $analytics = new Analytics($client);
            $accounts = $analytics->management_accounts->listManagementAccounts();
            $result = [];

            foreach ($accounts->getItems() as $account) {
                $result[] = [
                    'id' => $account->getId(),
                    'name' => $account->getName(),
                    'created' => $account->getCreated(),
                ];
            }

            return $result;
        }

        public function triggerJob() {
            FetchAnalyticsData::dispatch();
            return response()->json(['message' => 'Job dispatched!']);
        }
    }
