<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;
    use Google\Client;
    use Google\Service\GoogleAnalyticsAdmin;
    use App\Models\GoogleAccount;

    class GoogleAnalyticsController extends Controller {
        public function index() {
            $pythonServiceUrl = env('PYTHON_SERVICE_URL', 'http://python_service:5000/analytics');
            $response = Http::get($pythonServiceUrl);
            $analyticsData = $response->json();
            return view('services', ['analytics' => $analyticsData]);
        }

        public function fetchWebProperties($accountId) {
            $client = new Client();
            $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
            $adminService = new GoogleAnalyticsAdmin($client);

            try {
                $properties = $adminService->properties->listProperties([
                    'filter' => 'parent:accounts/' . $accountId
                ]);

                return response()->json($properties->toSimpleObject());
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        public function fetchAccountsWithProperties(Request $request) {
            $useCache = !$request->has('refresh');
            $search = $request->input('search');
            $status = $request->input('status');
            $tag = $request->input('tag');
            
            if ($useCache && GoogleAccount::count() > 0) {
                $accounts = $this->filterAndSearch($search, $status, $tag);
            } else {
                $apiAccounts = $this->fetchAccountsFromAPI();
                $this->storeAccountsAndProperties($apiAccounts);
                $accounts = $this->filterAndSearch($search, $status, $tag);
            }

            return view('services', ['accounts' => $accounts]);
        }

        private function filterAndSearch($search, $status, $tag) {
            return GoogleAccount::with(['properties' => function ($query) use ($search, $status, $tag) {
                $query->with('meta');

                if ($search) {
                    $query->where('display_name', 'like', '%' . $search . '%');
                }

                if ($status || $tag) {
                    $query->whereHas('meta', function ($metaQuery) use ($status, $tag) {
                        if ($status) {
                            $metaQuery->where('status', $status);
                        }
                        if ($tag) {
                            $metaQuery->where('tag', $tag);
                        }
                    });
                }
            }])->get();
        }


        private function fetchAccountsFromAPI() {
            $client = new Client();
            $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
            $adminService = new GoogleAnalyticsAdmin($client);

            return $adminService->accounts->listAccounts();
        }

        private function storeAccountsAndProperties($apiAccounts) {
            foreach ($apiAccounts->getAccounts() as $account) {
                $ga = GoogleAccount::updateOrCreate(
                    ['ga_account_id' => $account->getName()],
                    ['name' => $account->getDisplayName()]
                );

                $client = new Client();
                $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
                $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
                $adminService = new GoogleAnalyticsAdmin($client);

                $apiProps = $adminService->properties->listProperties([
                    'filter' => 'parent:' . $account->getName(),
                ]);

                foreach ($apiProps->getProperties() as $property) {
                    $ga->properties()->updateOrCreate(
                        ['ga_property_id' => $property->getName()],
                        [
                            'display_name' => $property->getDisplayName(),
                            'currency' => $property->getCurrencyCode(),
                            'time_zone' => $property->getTimeZone(),
                            'industry' => $property->getIndustryCategory(),
                            'service_level' => $property->getServiceLevel(),
                        ]
                    );
                }
            }
        }
    }
