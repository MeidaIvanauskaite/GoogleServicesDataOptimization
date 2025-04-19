<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Google Services Data Optimization</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles & Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>

    <body class="d-flex flex-column min-vh-100">
        <div id="app" class="flex-grow-1">

            <!-- Header -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
                <a class="navbar-brand" href="#">Google Services Data Optimizer</a>
                <div class="ms-auto d-flex align-items-center">
                    @auth
                        <span class="text-white me-3">
                            Hello, {{ Auth::user()->name }}
                            @if(Auth::user()->role === 'admin')
                                <span class="badge bg-warning text-black ms-2">Admin</span>
                            @endif
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                        @if(Auth::check())
                            <button id="themeToggle" class="btn btn-sm btn-outline-light ms-2">🌙 Dark Mode</button>
                        @endif
                    @endauth
                    <button class="btn btn-sm btn-outline-light ms-2" data-bs-toggle="modal" data-bs-target="#helpModal">
                        ❓ Help
                    </button>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md py-4">
                        @yield('analytics-content')
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <small>&copy; {{ now()->year }} Google Services Optimization Program. All rights reserved.</small>
        </footer>

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
