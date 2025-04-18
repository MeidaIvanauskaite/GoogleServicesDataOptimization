<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Google Services Data Optimization</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <button id="themeToggle" class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0" style="margin: 16px 100px 16px 16px">
            🌙 Dark Mode
        </button>

        <button class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-3 right-2" data-bs-toggle="modal" data-bs-target="#helpModal">
            ❓ Help
        </button>

        @yield('content')

        <!-- Help Modal -->
        <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="helpModalLabel">How to Use This Program</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Welcome to the <strong>Google Services Data Optimization Program</strong>.</p>
                        <ul>
                            <li>👥 <strong>Viewers</strong> can view Analytics accounts, properties, tags, and PageSpeed results.</li>
                            <li>🛠 <strong>Admins</strong> can also edit tags, notes, and optimization status for each property.</li>
                            <li>⚙️ Use the search & filter tools to find properties by name, tag, or status.</li>
                            <li>📄 Export filtered data as CSV or PDF using the export buttons.</li>
                            <li>⚡ Admins can also scan a PageSpeed score for each property using the URL input.</li>
                            <li>🌗 Toggle dark/light mode from the top-right corner for better visibility.</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
