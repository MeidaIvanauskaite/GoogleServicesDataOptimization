@extends('layouts.app')

@section('analytics-content')
<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1 class="mb-4 text-center">Google Analytics Accounts and Properties</h1>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('services') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by property name" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="tag" class="form-control" placeholder="Filter by tag" value="{{ request('tag') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Filter by status</option>
                <option value="Needs Review" {{ request('status') === 'Needs Review' ? 'selected' : '' }}>Needs Review</option>
                <option value="Optimized" {{ request('status') === 'Optimized' ? 'selected' : '' }}>Optimized</option>
                <option value="Archived" {{ request('status') === 'Archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('services') }}" class="btn btn-outline-secondary w-100">Clear</a>
        </div>
    </form>

    <!-- Export files -->
    <div class="mb-3">
        <a href="{{ route('export.csv', request()->query()) }}" class="btn btn-outline-secondary btn-sm me-2">Export CSV</a>
        <a href="{{ route('export.pdf', request()->query()) }}" class="btn btn-outline-secondary btn-sm">Export PDF</a>
    </div>

    <!-- Accounts & Properties -->
    @if (isset($error))
        <div class="alert alert-danger">
            <strong>Error:</strong> {{ $error }}
        </div>
    @elseif (count($accounts) === 0)
        <div class="alert alert-warning text-center">
            No accounts or properties found.
        </div>
    @else
        <div class="accordion" id="analyticsAccountsAccordion">
            @foreach ($accounts as $index => $data)
                <div class="accordion-item mb-3">
                    <h2 class="accordion-header" id="heading-{{ $index }}">
                        <button class="accordion-button"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $index }}"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $index }}">
                            <p style="margin:0"><strong>Account: </strong>{{ $data['account']['name'] }}</p>
                        </button>
                    </h2>

                    <div id="collapse-{{ $index }}" class="accordion-collapse collapse show" aria-labelledby="heading-{{ $index }}">
                        <div class="accordion-body">
                            <p class="mb-0"><strong>Name: </strong>{{ $data['account']['name'] }}</p>
                            <p><strong>ID: </strong>{{ $data['account']['id'] }}</p>
                            @if (count($data['properties']) > 0)
                                <h6><strong>Properties:</strong></h6>
                                <ul class="list-group">
                                    @foreach ($data['properties'] as $propertyData)
                                        @php
                                            $property = $propertyData['raw'];
                                            $meta = $propertyData['meta'];
                                        @endphp
                                        <li class="list-group-item">
                                            @php
                                                $property = $propertyData['raw'];
                                                $meta = $propertyData['meta'];
                                                $uniqueId = str_replace(['/', '.'], '_', $property->name);
                                                $shouldBeOpen = session('expanded_property') === $property->name;
                                                $pagespeed = \App\Models\PageSpeedResult::where('property_id', $property->name)->first();
                                            @endphp

                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="text-primary mb-0">{{ $property->displayName }}</h5>
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#propertyDetails-{{ $uniqueId }}">
                                                    Show/Hide Details
                                                </button>
                                            </div>

                                            <p class="mb-2">
                                                <strong>Time Zone:</strong> {{ $property->timeZone }} <br>
                                                <strong>Currency:</strong> {{ $property->currencyCode }} <br>
                                                <strong>Industry:</strong> {{ $property->industryCategory }} <br>
                                                <strong>Service Level:</strong>
                                                <span class="badge bg-success">{{ $property->serviceLevel }}</span>
                                            </p>

                                            <div class="collapse {{ $shouldBeOpen ? 'show' : '' }}" id="propertyDetails-{{ $uniqueId }}">
                                                @if(Auth::user()->role === 'admin')
                                                    <form method="POST" action="{{ route('update.property.meta') }}" class="mb-3">
                                                        @csrf
                                                        <input type="hidden" name="property_id" value="{{ $property->name }}">

                                                        <div class="mb-2">
                                                            <label class="form-label">Tag</label>
                                                            <input type="text" name="tag" class="form-control" value="{{ $meta->tag ?? '' }}">
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select">
                                                                <option {{ ($meta->status ?? '') == 'Needs Review' ? 'selected' : '' }}>Needs Review</option>
                                                                <option {{ ($meta->status ?? '') == 'Optimized' ? 'selected' : '' }}>Optimized</option>
                                                                <option {{ ($meta->status ?? '') == 'Archived' ? 'selected' : '' }}>Archived</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-2">
                                                            <label class="form-label">Note</label>
                                                            <textarea name="note" class="form-control" rows="2">{{ $meta->note ?? '' }}</textarea>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                    </form>
                                                @else
                                                    @if ($meta)
                                                        <p class="mt-2">
                                                            <strong>Tag:</strong> {{ $meta->tag }}<br>
                                                            <strong>Status:</strong> {{ $meta->status }}<br>
                                                            <strong>Note:</strong> {{ $meta->note }}
                                                        </p>
                                                    @endif
                                                    <form method="POST" action="{{ route('pagespeed.scan') }}" class="mb-2">
                                                        <input type="hidden" name="property_id" value="{{ $property->name }}">
                                                        <div class="input-group">
                                                            <input type="text" name="url" placeholder="https://example.com" class="form-control" required>
                                                            <button class="btn btn-outline-success" type="submit">Scan PageSpeed</button>
                                                        </div>

                                                        @if ($errors->has('url'))
                                                            <div class="alert alert-danger mt-2"> {{ $errors->first('url') }}</div>
                                                        @endif
                                                    </form>
                                                    @if($pagespeed)
                                                        <div class="mt-2">
                                                            <h5><strong>PageSpeed Results:</strong></h5>
                                                            <p class="mb-0"><strong>URL:</strong> {{ $pagespeed->url }}</p>
                                                            <p class="mb-0"><strong>Performance Score:</strong> {{ $pagespeed->performance_score }}</p>
                                                            <ul class="">
                                                                <li>LCP (loading): {{ $pagespeed->metrics['LCP'] ?? 'N/A' }}</li>
                                                                <li>FID (interactivity): {{ $pagespeed->metrics['FID'] ?? 'N/A' }}</li>
                                                                <li>CLS: (visual stability) {{ $pagespeed->metrics['CLS'] ?? 'N/A' }}</li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No properties available for this account.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
