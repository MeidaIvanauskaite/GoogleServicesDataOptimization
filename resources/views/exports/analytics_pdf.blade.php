<!DOCTYPE html>
<html>
    <head>
        <title>Analytics Export</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid #ccc;
                padding: 6px;
                vertical-align: top;
                text-align: left;
            }

            th {
                background-color: #f0f0f0;
            }

            .section-title {
                margin-top: 30px;
                font-size: 14px;
                font-weight: bold;
            }

            .subheading {
                font-size: 14px;
                color: #666;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <h2>Google Analytics Properties Export</h2>
        <div class="subheading">{{ now()->format('F j, Y') }}</div>

        <table>
            <thead>
                <tr>
                    <th>Property Name</th>
                    <th>Tag / Status</th>
                    <th>Note</th>
                    <th>PageSpeed</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $prop)
                    <tr>
                        <td>
                            <strong>{{ $prop['name'] }}</strong><br>
                            <small>{{ $prop['industry'] }} / {{ $prop['timeZone'] }} / {{ $prop['currency'] }}</small>
                        </td>
                        <td>
                            <strong>Tag:</strong> {{ $prop['tag'] ?? '-' }}<br>
                            <strong>Status:</strong> {{ $prop['status'] ?? '-' }}
                        </td>
                        <td>{{ $prop['note'] ?? '-' }}</td>
                        <td>
                            <strong>URL:</strong> {{ $prop['url'] ?? '-' }}<br>
                            <strong>Score:</strong> {{ $prop['pagespeed_score'] ?? '-' }}<br>
                            <strong>LCP:</strong> {{ $prop['lcp'] ?? '-' }}<br>
                            <strong>FID:</strong> {{ $prop['fid'] ?? '-' }}<br>
                            <strong>CLS:</strong> {{ $prop['cls'] ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
