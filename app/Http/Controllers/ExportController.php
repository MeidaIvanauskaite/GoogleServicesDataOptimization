<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use App\Models\PropertyMetadata;
    use Google\Client;
    use Google\Service\GoogleAnalyticsAdmin;
    use Barryvdh\DomPDF\Facade\Pdf;

    class ExportController extends Controller {
        public function exportCSV(Request $request) {
            $properties = $this->getFilteredProperties($request);

            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=analytics_export.csv",
            ];

            $callback = function () use ($properties) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'Property Name', 'Time Zone', 'Currency', 'Industry', 'Tag', 'Status', 'Note', 'URL', 'PageSpeed Score', 'LCP', 'FID', 'CLS'
                ]);

                foreach ($properties as $prop) {
                    fputcsv($handle, [
                        $prop['name'],
                        $prop['timeZone'],
                        $prop['currency'],
                        $prop['industry'],
                        $prop['tag'],
                        $prop['status'],
                        $prop['note'],
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        public function exportPDF(Request $request) {
            $properties = $this->getFilteredProperties($request);
            $pdf = Pdf::loadView('exports.analytics_pdf', compact('properties'));

            return $pdf->download('analytics_export.pdf');
        }

        private function getFilteredProperties(Request $request) {
            $search = $request->input('search');
            $status = $request->input('status');
            $tag = $request->input('tag');

            $client = new Client();
            $client->setAuthConfig(storage_path('credentials/google_credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
            $adminService = new GoogleAnalyticsAdmin($client);

            $accounts = $adminService->accounts->listAccounts();
            $results = [];

            foreach ($accounts->getAccounts() as $account) {
                $properties = $adminService->properties->listProperties([
                    'filter' => 'parent:' . $account->getName(),
                ]);

                foreach ($properties->getProperties() as $property) {
                    $pagespeed = \App\Models\PageSpeedResult::where('property_id', $property->getName())->first();
                    $meta = PropertyMetadata::where('property_id', $property->getName())->first();

                    if ($search && !str_contains(strtolower($property->getDisplayName()), strtolower($search))) {
                        continue;
                    }

                    if ($status && (!$meta || $meta->status !== $status)) {
                        continue;
                    }

                    if ($tag && (!$meta || $meta->tag !== $tag)) {
                        continue;
                    }

                    $results[] = [
                        'name' => $property->getDisplayName(),
                        'timeZone' => $property->getTimeZone(),
                        'currency' => $property->getCurrencyCode(),
                        'industry' => $property->getIndustryCategory(),
                        'tag' => $meta->tag ?? '',
                        'status' => $meta->status ?? '',
                        'note' => $meta->note ?? '',
                        'url' => $pagespeed->url ?? '',
                        'pagespeed_score' => $pagespeed->performance_score ?? '',
                        'lcp' => $pagespeed->metrics['LCP'] ?? '',
                        'fid' => $pagespeed->metrics['FID'] ?? '',
                        'cls' => $pagespeed->metrics['CLS'] ?? '',
                    ];
                }
            }

            return $results;
        }
    }
