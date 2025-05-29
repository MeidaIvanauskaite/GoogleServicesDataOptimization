<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
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
                        $prop['name'], $prop['timeZone'], $prop['currency'], $prop['industry'], $prop['tag'], $prop['status'], $prop['note'],
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

            $query = \App\Models\GoogleProperty::with(['meta', 'pagespeed']);

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

            return $query->get()->map(function ($property) {
                return [
                    'name' => $property->display_name,
                    'timeZone' => $property->time_zone,
                    'currency' => $property->currency,
                    'industry' => $property->industry,
                    'tag' => $property->meta->tag ?? '',
                    'status' => $property->meta->status ?? '',
                    'note' => $property->meta->note ?? '',
                    'url' => $property->pagespeed->url ?? '',
                    'pagespeed_score' => $property->pagespeed->performance_score ?? '',
                    'lcp' => $property->pagespeed->metrics['LCP'] ?? '',
                    'fid' => $property->pagespeed->metrics['FID'] ?? '',
                    'cls' => $property->pagespeed->metrics['CLS'] ?? '',
                ];
            });
        }
    }
