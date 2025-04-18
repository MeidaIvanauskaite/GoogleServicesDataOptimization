<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;
    use Google\Client;
    use Google\Service\GoogleAnalyticsAdmin;
    use App\Models\PropertyMetadata;

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
            $search = $request->input('search');
            $filterStatus = $request->input('status');
            $filterTag = $request->input('tag');

            $client = new Client();
            $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
            $adminService = new GoogleAnalyticsAdmin($client);

            try {
                $accounts = $adminService->accounts->listAccounts();
                $accountsWithProperties = [];

                foreach ($accounts->getAccounts() as $account) {
                    $properties = $adminService->properties->listProperties([
                        'filter' => 'parent:' . $account->getName(),
                    ]);

                    $propertyList = [];

                    foreach ($properties->getProperties() as $property) {
                        $meta = PropertyMetadata::where('property_id', $property->getName())->first();

                        if ($search && !str_contains(strtolower($property->getDisplayName()), strtolower($search))) {
                            continue;
                        }

                        if ($filterStatus && (!$meta || $meta->status !== $filterStatus)) {
                            continue;
                        }

                        if ($filterTag && (!$meta || $meta->tag !== $filterTag)) {
                            continue;
                        }

                        $propertyList[] = [
                            'raw' => $property,
                            'meta' => $meta,
                        ];
                    }

                    if (!empty($propertyList)) {
                        $accountsWithProperties[] = [
                            'account' => [
                                'id' => $account->getName(),
                                'name' => $account->getDisplayName(),
                            ],
                            'properties' => $propertyList,
                        ];
                    }
                }

                return view('services', [
                    'accounts' => $accountsWithProperties,
                    'search' => $search,
                    'filterStatus' => $filterStatus,
                    'filterTag' => $filterTag,
                ]);
            } catch (\Exception $e) {
                return view('services', ['accounts' => [], 'error' => $e->getMessage()]);
            }
        }
    }
