<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;
    use App\Models\PageSpeedResult;

    class PageSpeedController extends Controller {
        public function scan(Request $request) {
            $request->validate([
                'url' => 'required|url',
                'property_id' => 'required',
            ]);

            $url = $request->url;
            $response = Http::get("https://www.googleapis.com/pagespeedonline/v5/runPagespeed", [
                'url' => $url,
                'strategy' => 'mobile',
                'key' => env('PAGESPEED_API_KEY')
            ]);

            if ($response->failed()) {
                return redirect()->back()->with('error', 'PageSpeed API failed: ' . $response->body());
            }

            $data = $response->json();
            $score = $data['lighthouseResult']['categories']['performance']['score'] * 100;
            $metrics = [
                'LCP' => $data['lighthouseResult']['audits']['largest-contentful-paint']['displayValue'] ?? null,
                'FID' => $data['lighthouseResult']['audits']['max-potential-fid']['displayValue'] ?? null,
                'CLS' => $data['lighthouseResult']['audits']['cumulative-layout-shift']['displayValue'] ?? null,
            ];

            PageSpeedResult::updateOrCreate(
                ['property_id' => $request->property_id],
                [
                    'url' => $url,
                    'metrics' => $metrics,
                    'performance_score' => $score,
                ]
            );

            return redirect()->back()->with([
                'success' => 'PageSpeed scan complete.',
                'expanded_property' => $request->property_id,
            ]);
            
        }
    }
